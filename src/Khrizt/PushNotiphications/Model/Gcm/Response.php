<?php

namespace Khrizt\PushNotiphications\Model\Gcm;

use Khrizt\PushNotiphications\Model\ResponseInterface;

class Response implements ResponseInterface
{
    /**
     * List of error messages from GCM api.
     *
     * @var array
     */
    protected $errorTexts = [
        'MissingRegistration' => 'Missing Registration Token. Check that the request contains a registration token (in the registration_id in a plain text message, or in the to or registration_ids field in JSON).',
        'InvalidRegistration' => 'Invalid Registration Token. Check the format of the registration token you pass to the server. Make sure it matches the registration token the client app receives from registering with GCM. Do not truncate or add additional characters.',
        'NotRegistered' => 'Unregistered Device. An existing registration token may cease to be valid in a number of scenarios, including:

    If the client app unregisters with GCM.
    If the client app is automatically unregistered, which can happen if the user uninstalls the application. For example, on iOS, if the APNS Feedback Service reported the APNS token as invalid.
    If the registration token expires (for example, Google might decide to refresh registration tokens, or the APNS token has expired for iOS devices).
    If the client app is updated but the new version is not configured to receive messages.

For all these cases, remove this registration token from the app server and stop using it to send messages.',
        'InvalidPackageName' => 'Invalid Package Name. Make sure the message was addressed to a registration token whose package name matches the value passed in the request.',
        'MismatchSenderId' => 'Mismatched Sender. A registration token is tied to a certain group of senders. When a client app registers for GCM, it must specify which senders are allowed to send messages. You should use one of those sender IDs when sending messages to the client app. If you switch to a different sender, the existing registration tokens won\'t work.',
        'MessageTooBig' => 'Message too big. Check that the total size of the payload data included in a message does not exceed GCM limits: 4096 bytes for most messages, or 2048 bytes in the case of messages to topics or notification messages on iOS. This includes both the keys and the values.',
        'InvalidDataKey' => 'Invalid data key. Check that the payload data does not contain a key (such as from, or gcm, or any value prefixed by google) that is used internally by GCM. Note that some words (such as collapse_key) are also used by GCM but are allowed in the payload, in which case the payload value will be overridden by the GCM value.',
        'InvalidTtl' => 'Invalid Time to Live. Check that the value used in time_to_live is an integer representing a duration in seconds between 0 and 2,419,200 (4 weeks).',
        'DeviceMessageRateExceeded' => 'Device Message Rate Exceeded. The rate of messages to a particular device is too high. Reduce the number of messages sent to this device and do not immediately retry sending to this device.',
        'TopicsMessageRateExceeded' => 'Topic Message Rate Exceeded. The rate of messages to subscribers to a particular topic is too high. Reduce the number of messages sent for this topic, and do not immediately retry sending.',
    ];

    /**
     * Sent notification id.
     *
     * @var string
     */
    protected $notificationId;

    /**
     * Device token.
     *
     * @var string
     */
    protected $token;

    /**
     * Device canonical id.
     *
     * @var string
     */
    protected $canonicalId;

    /**
     * Error code.
     *
     * @var string
     */
    protected $errorCode = '';

    /**
     * Parse GCM response body.
     *
     * @param string $token Device token
     * @param array  $body  Response body
     *
     * @return Response
     */
    public static function parse(string $token, array $body) : Response
    {
        $response = new self();
        $response->token = $token;
        if (array_key_exists('message_id', $body)) {
            $response->notificationId = $body['message_id'];
        }
        if (array_key_exists('registration_id', $body)) {
            $response->canonicalId = $body['registration_id'];
        }
        if (array_key_exists('error', $body)) {
            $response->errorCode = $body['error'];
        }

        return $response;
    }

    /**
     * Gets the value of notificationId.
     *
     * @return string
     */
    public function getNotificationId() : string
    {
        return $this->notificationId;
    }

    /**
     * Gets the value of token.
     *
     * @return string
     */
    public function getToken() : string
    {
        return $this->token;
    }

    /**
     * Returns if notification was sent ok.
     *
     * @return bool
     */
    public function getIsOk() : bool
    {
        return empty($this->errorCode);
    }

    /**
     * Gets the value of errorCode.
     *
     * @return string
     */
    public function getErrorCode() : string
    {
        return $this->errorCode;
    }

    /**
     * Gets the error message if there's any.
     *
     * @return string
     */
    public function getErrorMessage() : string
    {
        if (empty($this->errorCode)) {
            return '';
        }

        return $this->errorTexts[$this->errorCode];
    }

    /**
     * Gets the Device canonical id.
     *
     * @return string
     */
    public function getCanonicalId() : string
    {
        return $this->canonicalId;
    }
}
