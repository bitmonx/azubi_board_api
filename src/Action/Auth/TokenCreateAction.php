<?php

namespace App\Action\Auth;

use App\Action\AppAction;
use App\Auth\JwtAuth;
use App\Domain\User\Service\UserAuthenticator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class TokenCreateAction.
 * Handles post request over /login route.
 * @package App\Action\Auth
 */
final class TokenCreateAction extends AppAction
{
    /**
     * @var JwtAuth $jwtAuth
     */
    private $jwtAuth;
    /**
     * @var UserAuthenticator $userAuth
     */
    private $userAuth;

    /**
     * TokenCreateAction constructor.
     * @param JwtAuth $jwtAuth
     * @param UserAuthenticator $userAuth
     */
    public function __construct(JwtAuth $jwtAuth, UserAuthenticator $userAuth)
    {
        parent::__construct();
        $this->jwtAuth = $jwtAuth;
        $this->userAuth = $userAuth;
    }

    /**
     * Automatically called function of callable classes.
     * Handles the request by processing the incoming data
     * and replying a built response.
     * Receives login data and responds JWT (json web token)
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface
    {
        $data = (array)$request->getParsedBody();
        $username = (string)($data['username'] ?? '');
        $password = (string)($data['password'] ?? '');
        // Validate login (pseudo code)
        $userAuthData = $this->userAuth->authenticate($username, $password);
        $isValidLogin = ($userAuthData !== null);
        if (!$isValidLogin) {
        // Invalid authentication credentials
            return $this->respond($response, 401);
        }
        // Create a fresh token
        $token = $this->jwtAuth->createJwt([
            'uid' => $userAuthData->id,
            'username' => $userAuthData->username,
            'name' => $userAuthData->name,
            'firstname' => $userAuthData->firstname,
            'department' => $userAuthData->department,
            'isTrainee' => $userAuthData->isTrainee
        ]);
        $lifetime = $this->jwtAuth->getLifetime();
        // Transform the result into a OAuth 2.0 access token response
        $result = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $lifetime,
        ];
        // Build the HTTP response
        $this->responseData->build('Login successful', $result);
        return $this->respond($response, 201);
    }
}
