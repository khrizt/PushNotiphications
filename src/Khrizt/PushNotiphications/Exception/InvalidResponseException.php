<?php

namespace Khrizt\PushNotiphications\Exceptions;

use Exception;

class InvalidResponseException extends Exception
{
    public function __construct($errorMessage = '', $rawResponse = null)
    {
        $text = 'The response received does not have a correct syntax';
        if (!empty($errorMessage)) {
            $text .= '. Error message: '.$errorMessage;
        }
        if (!is_null($rawResponse)) {
            $text .= '. Response: '.$rawResponse;
        }
        parent::__construct($text);
    }
}
