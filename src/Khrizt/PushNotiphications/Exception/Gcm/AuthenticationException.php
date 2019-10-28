<?php

namespace Khrizt\PushNotiphications\Exception\Fcm;

use Exception;

class AuthenticationException extends Exception
{
    public function __construct()
    {
        parent::__construct('Authentication Error. The sender account used to send a message couldn\'t be authenticated. Possible causes are:

    Authorization header missing or with invalid syntax in HTTP request.
    Invalid project number sent as key.
    Key valid but with GCM service disabled.
    Request originated from a server not whitelisted in the Server Key IPs.

Check that the token you\'re sending inside the Authentication header is the correct API key associated with your project. See Checking the validity of an API Key for details.');
    }
}
