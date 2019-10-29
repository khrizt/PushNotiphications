<?php

namespace Khrizt\PushNotiphications\Model;

interface Message
{
    public function getPayload(): string;
}
