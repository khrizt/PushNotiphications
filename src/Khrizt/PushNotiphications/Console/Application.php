<?php

namespace Khrizt\PushNotiphications\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Khrizt\PushNotiphications\Console\Commands\PushCommand;

/**
 * Application.
 *
 * @uses \Symfony\Component\Console\Application
 */
class Application extends BaseApplication
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('PushNotiphications');

        $this->add(new PushCommand());
    }
}
