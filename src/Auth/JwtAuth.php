<?php

namespace App\Auth;

use Cake\Chronos\Chronos;
use InvalidArgumentException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use UnexpectedValueException;

/**
 * Class JwtAuth.
 * Handles authorization by JWT (json web token)
 * @package App\Auth
 */
final class JwtAuth
{
    /**
     * @var string $issuer
     */
    private $issuer;
    /**
     * @var int $lifetime
     */
    private $lifetime;
    /**
     * @var string $privateKey
     */
    private $privateKey;
    /**
     * @var string $publicKey
     */
    private $publicKey;
    /**
     * @var Sha256 $signer
     */
    private $signer;

    /**
     * The constructor.
     * @param string $issuer The issuer name
     * @param int $lifetime The max lifetime
     * @param string $privateKey The private key as string
     * @param string $publicKey The public key as string
     */
    public function __construct(
        string $issuer,
        int $lifetime,
        string $privateKey,
        string $publicKey
    )
    {
        $this->issuer = $issuer;
        $this->lifetime = $lifetime;
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->signer = new Sha256();
    }

    /**
     * Get JWT max lifetime.
     * @return int The lifetime in seconds
     */
    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    /**
     * Create json web token.
     * @param array $claims The claims
     * @throws UnexpectedValueException
     * @return string The JWT
     */
    public function createJwt(array $claims): string
    {
        $issuedAt = Chronos::now()->getTimestamp();
        $builder = (new Builder())->issuedBy($this->issuer)
            ->identifiedBy(uuid_create(), true)
            ->issuedAt($issuedAt)
            ->canOnlyBeUsedAfter($issuedAt)
            ->expiresAt($issuedAt + $this->lifetime);
        foreach ($claims as $name => $value) {
            $builder = $builder->withClaim($name, $value);
        }
        return $builder->getToken($this->signer, new Key($this->privateKey));
    }

    /**
     * Validate the access token.
     * @param string $accessToken The JWT
     * @return bool The status
     */
    public function validateToken(string $accessToken): bool
    {
        $token = $this->createParsedToken($accessToken);
        if (!$token->verify($this->signer, $this->publicKey)) {
            return false;
        }
        // Check whether the token has not expired
        $data = new ValidationData();
        $data->setCurrentTime(Chronos::now()->getTimestamp());
        $data->setIssuer($token->getClaim('iss'));
        $data->setId($token->getClaim('jti'));
        return $token->validate($data);
    }

    /**
     * Parse token.
     * @param string $token The JWT
     * @throws InvalidArgumentException
     * @return Token The parsed token
     */
    public function createParsedToken(string $token): Token
    {
        return (new Parser())->parse($token);
    }
}
