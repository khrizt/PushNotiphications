<?php

namespace Khrizt\PushNotiphications\Exception\Apns;

use Exception;

class NoHttp2SupportException extends Exception
{
    public function __construct()
    {
        parent::__construct('Your curl library must have HTTP2 support. Take a look at this page to achieve that: https://serversforhackers.com/video/curl-with-http2-support');
    }
}
