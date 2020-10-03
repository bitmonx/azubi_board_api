<?php


namespace App\Auth;


use Cake\Http\Client;
use Cake\Http\Client\Response;

/**
 * Class LdapLogin
 * Managing the connection to the intern api server
 * @package App\Auth
 */
final class LdapLogin
{
    /**
     * Url of the intern api sever
     * @var string $url
     */
    private $url;

    /**
     * LdapLogin constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Checks if the api server is reachable
     */
    public function test(): array
    {
        $data = [];
        $res = $this->call('get', '/1.0/status/check');
        if (is_object($res)) {
            $data = $res->getJson();
        }

        return $data;
    }

    /**
     * LDAP Login Check
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function ldapLogin(string $username, string $password = ''): bool
    {
        $apiResult = $this->call(
            'post', '/1.1/ldap/login', [
            'username' => $username,
            'password' => $password
        ] // Force to use Live API Server
        );

        $body = [];
        if (!empty($apiResult) && is_object($apiResult)) {
            $body = $apiResult->getJson();
        }

        return !empty($body) && isset($body['result']) && (bool)$body['result'] === true;
    }

    /**
     * Default API Call
     * @param string $type
     * @param string $url
     * @param array $data
     * @return Response | array
     */
    private function call(
        string $type = 'get',
        string $url = '',
        array $data = [])
    {
        $apiDomain = $this->url;

        $http = new Client([
            'timeout' => 5,
            'host' => $apiDomain,
            'scheme' => 'http'
        ]);

        $response = [];
        switch (strtolower($type)) {
            case 'get':
                $response = $http->get($url, $data);
                break;
            case 'post':
                $response = $http->post($url, $data);
                break;
        }

        return $response;
    }
}
