<?php

namespace Codeception\Module\Tests\Service;

use Codeception\Module\Service\CleverReachService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class CleverReachServiceTest
 * @package Codeception\Module\Tests
 */
class CleverReachServiceTest extends TestCase
{
    /**
     * @var CleverReachService|PHPUnit_Framework_MockObject_MockObject
     */
    protected $cleverReachService;

    const FIXTURE_EMAIL = 'foo@bar.de';
    const FIXTURE_GROUP_ID = '123456';
    const FIXTURE_TOKEN = 'abcdef';
    /**
     * @test
     */
    public function isSubscribed()
    {
        $config = [
            'client_id' => 'foo',
            'login_name' => 'bar',
            'password' => 'baz'
        ];

        $responseReceiver = [
            0 => [
                'active' => true
            ]
        ];

        $clientMock = new MockHandler([
            new Response(200, [], json_encode(self::FIXTURE_TOKEN)),
            new Response(200, [], json_encode($responseReceiver))
        ]);

        $handler = HandlerStack::create($clientMock);
        $client = new Client(['handler' => $handler]);

        $this->cleverReachService = $this
            ->getMockBuilder(CleverReachService::class)
            ->setMethods(
                [
                    'dummy',
                ]
            )
            ->setConstructorArgs(
                [
                    $config,
                    $client
                ]
            )
            ->getMock();

        $isSubscribed = $this->cleverReachService->isSubscribed(self::FIXTURE_EMAIL, self::FIXTURE_GROUP_ID);

        static::assertEquals(self::FIXTURE_TOKEN, $this->cleverReachService->getToken());
        static::assertTrue($isSubscribed);
    }
}
