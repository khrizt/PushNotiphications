<?php

namespace Khrizt\PushNotiphications\Exception\Gcm;

use Exception;

class InvalidJsonException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid JSON. Check that the JSON message is properly formatted and contains valid fields (for instance, making sure the right data type is passed in).');
    }
}
