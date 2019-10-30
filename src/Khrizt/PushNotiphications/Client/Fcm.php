<?php

namespace Khrizt\PushNotiphications\Client;

use Khrizt\PushNotiphications\Model\Message as MessageInterface;
use Khrizt\PushNotiphications\Model\Fcm\Response;
use Khrizt\PushNotiphications\Exception\Fcm\InvalidJsonException;
use Khrizt\PushNotiphications\Exception\Fcm\AuthenticationException;
use Khrizt\PushNotiphications\Exception\Fcm\ServerNotAvailableException;
use Khrizt\PushNotiphications\Collection\Collection;

class Fcm extends AbstractClient
{
    /**
     * @const string Firebase Cloud Message URL
     */
    const GCM_URL = 'https://fcm.googleapis.com/fcm/send';

    /**
     * FCM Api key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Connection handler.
     *
     * @var resource
     */
    protected $handler;

    /**
     * Debug flag.
     *
     * @var bool
     */
    protected $debug = false;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Sends the notification message to the devices.
     *
     * @param MessageInterface $message          Notification message
     * @param Collection       $deviceCollection List of devices to notify
     *
     * @return Collection All message responses
     */
    public function send(MessageInterface $message, Collection $deviceCollection) : Collection
    {
        // open connection handler if there's none
        if (is_null($this->handler)) {
            $this->handler = curl_init();
        }

        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->handler, CURLOPT_POST, 1);
        curl_setopt($this->handler, CURLOPT_HEADER, true);
        curl_setopt($this->handler, CURLOPT_HTTPHEADER, [
            'Authorization: key='.$this->apiKey,
            'Content-Type: application/json',
        ]);
        curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->handler, CURLOPT_URL, self::GCM_URL);
        if ($this->debug) {
            curl_setopt($this->handler, CURLOPT_VERBOSE, true);
        }

        $responseCollection = new Collection();
        $count = 0;
        while ($count < $deviceCollection->count()) {
            $tokens = [];
            foreach ($deviceCollection as $device) {
                $tokens[] = $device->getToken();
                ++$count;
                if (count($tokens) === 1000) {
                    break;
                }
            }
            $payload = $message->getNoEncodedPayload();
            if (count($tokens) === 0) {
                break;
            } elseif (count($tokens) === 1) {
                $payload['to'] = $tokens[0];
            } else {
                $payload['registration_ids'] = $tokens;
            }

            curl_setopt($this->handler, CURLOPT_POSTFIELDS, json_encode($payload));

            $rawResponse = curl_exec($this->handler);

            $status = (int) curl_getinfo($this->handler, CURLINFO_HTTP_CODE);

            if ($status === 400) {
                throw new InvalidJsonException();
            } elseif ($status === 401) {
                throw new AuthenticationException();
            } elseif ($status > 500) {
                throw new ServerNotAvailableException();
            }

            $headerSize = curl_getinfo($this->handler, CURLINFO_HEADER_SIZE);
            // $responseHeaders = substr($rawResponse, 0, $headerSize);
            $responseBody = substr($rawResponse, $headerSize);

            $body = json_decode($responseBody, true);
            foreach ($body['results'] as $key => $result) {
                $responseCollection->append(Response::parse($deviceCollection->offsetGet($key)->getToken(), $result));
            }
        }

        return $responseCollection;
    }

    /**
     * Sets the Debug flag.
     *
     * @param bool $debug the debug
     *
     * @return self
     */
    public function setDebug(bool $debug) : Fcm
    {
        $this->debug = $debug;

        return $this;
    }
}
