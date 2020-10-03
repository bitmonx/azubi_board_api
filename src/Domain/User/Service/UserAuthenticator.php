<?php


namespace App\Domain\User\Service;


use App\Auth\LdapLogin;
use App\Domain\User\Data\UserData;
use App\Domain\User\Repository\UserRepository;

/**
 * Class UserAuthenticator.
 * Handles user authentication.
 * @package App\Domain\User\Service
 */
final class UserAuthenticator
{
    /**
     * @var LdapLogin $ldap
     */
    private $ldap;
    /**
     * @var UserRepository $repository
     */
    private $repository;

    /**
     * UserAuthenticator constructor.
     * @param LdapLogin $ldap
     * @param UserRepository $repository
     */
    public function __construct(LdapLogin $ldap, UserRepository $repository)
    {
        $this->ldap = $ldap;
        $this->repository = $repository;
    }

    /**
     * Calls the intern api to check validation of the
     * given username and password. Receives the user whether
     * user data is valid or null if it is not.
     * @param string $username
     * @param string $password
     * @return UserData|null
     */
    public function authenticate(string $username, string $password): ?UserData
    {
        if (!$this->ldap->ldapLogin($username, $password)) {
            return null;
        }

        return $this->repository->getUserByUsername($username);
    }
}
