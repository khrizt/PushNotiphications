<?php

namespace Khrizt\PushNotiphications\Model\Apns;

use Khrizt\PushNotiphications\Exception\Apns\InvalidResponseException;
use Datetime;
use Khrizt\PushNotiphications\Model\ResponseInterface;

class Response implements ResponseInterface
{
    protected $headers = [];

    protected $notificationId;

    protected $token;

    protected $status;

    protected $timestamp;

    protected $errorCode;

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

    /**
     * Parse APNS response.
     *
     * @param string $token   Device token
     * @param string $headers Raw headers
     * @param string $body    Response body
     *
     * @return self
     */
    public static function parse($token, $headers, $body)
    {
        $response = new self();
        $response->token = $token;
        $response->headers = self::parseHeaders($headers);
        $response->status = (int) $response->headers['httpCode'];

        if ($response->status == 200) {
            $response->notificationId = $response->headers['apns-id'];

            return $response;
        }

        $decodedBody = json_decode($body);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidResponseException(json_last_error_msg(), $body);
        }

        $response->errorCode = $decodedBody->reason;

        if ($response->status == 410) {
            $timestamp = Datetime::createFromFormat($decodedBody->timestamp, 'U');
            if ($timestamp === false) {
                throw new InvalidResponseException(json_last_error_msg(), $body);
            }
        }

        return $response;
    }

    /**
     * Parse response headers to obtain response status and other fields.
     *
     * @param string $rawHeaders Raw headers as returned by response
     *
     * @return array
     */
    protected static function parseHeaders($rawHeaders)
    {
        $headers = [];

        if (!empty($rawHeaders)) {
            foreach (explode("\r\n", $rawHeaders) as $key => $header) {
                if ($key === 0) {
                    // get status from headers
                    $headers['httpCode'] = trim(str_replace('HTTP/2 ', '', $header));
                } elseif (!empty(trim($header))) {
                    list($key, $value) = explode(': ', $header);
                    $headers[$key] = trim($value);
                }
            }
        }

        return $headers;
    }

    /**
     * Gets the error message value.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        if (is_null($this->errorCode)) {
            return '';
        }

        return $this->errorTexts[$this->errorCode];
    }

    /**
     * Gets the value of headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Gets the value of notificationId.
     *
     * @return string
     */
    public function getNotificationId()
    {
        return $this->notificationId;
    }

    /**
     * Gets the value of token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Returns if notification was sent ok.
     *
     * @return bool
     */
    public function getIsOk()
    {
        return $this->status === 200;
    }

    /**
     * Gets the value of status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Gets the value of timestamp.
     *
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Gets the value of errorCode.
     *
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
