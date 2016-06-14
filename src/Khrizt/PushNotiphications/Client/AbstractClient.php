<?php

namespace Khrizt\PushNotiphications\Client;

use Khrizt\PushNotiphications\Model\Message as MessageInterface;
use Khrizt\PushNotiphications\Collection\Collection;

abstract class AbstractClient
{
    abstract public function send(MessageInterface $message, Collection $collection) : Collection;
}
