<?php

namespace Codeception\Module;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Codeception\Module\Service\CleverReachService;
use PHPUnit\Framework\Assert;

/**
 * Class CleverReach
 * @package Codeception\Module
 */
class CleverReach extends Module
{
    /**
     * @var array
     */
    protected $config = [
        'client_id' => '',
        'login_name' => '',
        'password' => ''
    ];

    /**
     * @var CleverReachService
     */
    protected $cleverReachService;

    /**
     * CleverReach constructor.
     * @param ModuleContainer $moduleContainer
     * @param null $config
     */
    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);
        $this->cleverReachService = new CleverReachService($this->config);
    }

    /**
     * @param string $email
     * @param string $groupId
     */
    public function seeUserIsSubscribedForNewsletter($email, $groupId)
    {
        $isSubscribed = $this->cleverReachService->isSubscribed($email, $groupId);
        Assert::assertTrue($isSubscribed);
    }

    /**
     * @param string $email
     * @param string $groupId
     */
    public function dontSeeUserIsSubscribedForNewsletter($email, $groupId)
    {
        $isSubscribed = $this->cleverReachService->isSubscribed($email, $groupId);
        Assert::assertFalse($isSubscribed);
    }
}
