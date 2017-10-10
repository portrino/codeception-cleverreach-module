<?php

namespace Codeception\Module\Service;

use Codeception\Util\HttpCode;
use GuzzleHttp\Client;

/**
 * Class CleverReachService
 */
class CleverReachService
{
    /**
     * @var Client
     */
    protected $client;

    const API_URL = 'https://rest.cleverreach.com/v2';

    /**
     * @var string
     */
    protected $token = '';

    /**
     * @var array
     */
    protected $config = [
        'client_id',
        'login_name',
        'password'
    ];

    /**
     * CleverReachService constructor.
     * @param Client|null $client
     * @param array $config
     */
    public function __construct($config, $client = null)
    {
        if ($client === null) {
            $this->client = new Client();
        } else {
            $this->client = $client;
        }

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

        $response = $this->client->get(
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
    protected function createToken()
    {
        $url = self::API_URL . '/login.json';
        $response = $this->client->post(
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

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}
