<?php

namespace Khrizt\PushNotiphications\Model\Apns;

use Khrizt\PushNotiphications\Model\BaseResponse;
use Khrizt\PushNotiphications\Exceptions\InvalidResponseException;
use Datetime;

class Response extends BaseResponse
{
    protected $errorTexts = [
        'PayloadEmpty' => 'The message payload was empty.',
        'PayloadTooLarge' => 'The message payload was too large. The maximum payload size is 4096 bytes.',
        'BadTopic' => 'The apns-topic was invalid.',
        'TopicDisallowed' => 'Pushing to this topic is not allowed.',
        'BadMessageId' => 'The apns-id value is bad.',
        'BadExpirationDate' => 'The apns-expiration value is bad.',
        'BadPriority' => 'The apns-priority value is bad.',
        'MissingDeviceToken' => 'The device token is not specified in the request :path. Verify that the :path header contains the device token.',
        'BadDeviceToken' => 'The specified device token was bad. Verify that the request contains a valid token and that the token matches the environment.',
        'DeviceTokenNotForTopic' => 'The device token does not match the specified topic.',
        'Unregistered' => 'The device token is inactive for the specified topic.',
        'DuplicateHeaders' => 'One or more headers were repeated.',
        'BadCertificateEnvironment' => 'The client certificate was for the wrong environment.',
        'BadCertificate' => 'The certificate was bad.',
        'Forbidden' => 'The specified action is not allowed.',
        'BadPath' => 'The request contained a bad :path value.',
        'MethodNotAllowed' => 'The specified :method was not POST.',
        'TooManyRequests' => 'Too many requests were made consecutively to the same device token.',
        'IdleTimeout' => 'Idle time out.',
        'Shutdown' => 'The server is shutting down.',
        'InternalServerError' => 'An internal server error occurred.',
        'ServiceUnavailable' => 'The service is unavailable.',
        'MissingTopic' => 'The apns-topic header of the request was not specified and was required. The apns-topic header is mandatory when the client is connected using a certificate that supports multiple topics.',
    ];

    protected $notificationId;

    protected $status;

    protected $timestamp;

    protected $errorCode;

    protected $errorMessage;

    public static function parse($status, $headers, $body)
    {
        var_dump($status, $headers, $body);
        $response = new self();
        $response->headers = parent::parseHeaders($headers);
        $response->status = $response->headers['httpCode'];

        if ($response->status == 200) {
            $response->notificationId = $response->headers['apns-id'];

            return $response;
        }

        $decodedBody = json_decode($body);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidResponseException(json_last_error_msg(), $body);
        }

        $response->errorCode = $decodedBody->reason;

        if ($status == 410) {
            $timestamp = Datetime::createFromFormat($decodedBody->timestamp, 'U');
            if ($timestamp === false) {
                throw new InvalidResponseException(json_last_error_msg(), $body);
            }
        }

        return $response;
    }

    public function getNotificationId()
    {
        return $this->notificationId;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getErrorMessage()
    {
        return $this->errorTexts[$this->errorCode];
    }
}
