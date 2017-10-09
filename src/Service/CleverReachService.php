<?php

namespace Codeception\Module\Service;

use Codeception\Util\HttpCode;
use GuzzleHttp;

/**
 * Class CleverReachService
 */
class CleverReachService
{
    protected $guzzleClient;

    const API_URL = 'https://rest.cleverreach.com/v2';

    protected $token = '';

    /**
     * @var array
     */
    protected $config = [
        'client_id',
        'login',
        'password'
    ];

    /**
     * CleverReachService constructor.
     * @param array $config
     */
    public function __construct($config)
    {
        $this->guzzleClient = new GuzzleHttp\Client();
        $this->config = $config;
    }

    /**
     * @param string $email
     * @param string $groupId
     * @return bool
     */
    public function isSubscribed($email, $groupId)
    {
        $result = false;
        $url = self::API_URL . '/groups.json/' . $groupId . '/receivers';

        $this->createToken();

        $response = $this->guzzleClient->get(
            $url,
            [
                'query' => [
                    'pagesize' => 1,
                    'page' => 0,
                    'type' => 'all',
                    'email_list' => $email,
                    'token' => $this->token
                ]
            ]
        );

        $cleverreachResponse = json_decode($response->getBody(), true);
        if (isset($cleverreachResponse[0]['active'])) {
            $result = (boolean)$cleverreachResponse[0]['active'];
        }
        return $result;
    }

    /**
     *
     */
    public function createToken()
    {
        $url = self::API_URL . '/login.json';
        $response = $this->guzzleClient->post(
            $url,
            [
                'json' => [
                    'client_id' => $this->config['client_id'],
                    'login' => $this->config['login_name'],
                    'password' => $this->config['password']
                ]
            ]
        );

        if ($response->getStatusCode() === HttpCode::OK) {
            $this->token = json_decode($response->getBody(), true);
        }
    }
}
