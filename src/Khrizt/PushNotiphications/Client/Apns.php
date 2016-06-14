<?php

namespace Khrizt\PushNotiphications\Client;

use Khrizt\PushNotiphications\Model\Message as MessageInterface;
use Khrizt\PushNotiphications\Model\Apns\Response;
use Khrizt\PushNotiphications\Collection\Collection;
use Khrizt\PushNotiphications\Exception\Apns\NoHttp2SupportException;
use Khrizt\PushNotiphications\Constants;

class Apns
{
    /**
     * Apple APNS url service.
     *
     * @var string
     */
    protected $apnsUrl;

    /**
     * Connection handler.
     *
     * @var resource
     */
    protected $handler;

    /**
     * Path to certificate.
     *
     * @var string
     */
    protected $certificate;

    /**
     * Certificate passphrase.
     *
     * @var string
     */
    protected $passphrase;

    /**
     * Constructor.
     *
     * @param string $environment Environment
     * @param string $certificate Path to certificate
     * @param string $passphrase  Certificate passphrase
     */
    public function __construct(string $environment, string $certificate, string $passphrase = null)
    {
        if ($environment == Constants::PRODUCTION) {
            $this->apnsUrl = 'https://api.push.apple.com';
        } else {
            $this->apnsUrl = 'https://api.development.push.apple.com';
        }
        $this->apnsUrl .= '/3/device/';
        $this->certificate = $certificate;
        $this->passphrase = $passphrase;
    }

    /**
     * Sends notification message to a list of devices.
     *
     * @param MessageInterface $message          Notification message
     * @param Collection       $deviceCollection List of devices
     *
     * @return Collection Response collection
     */
    public function send(MessageInterface $message, Collection $deviceCollection) : Collection
    {
        if (!defined('CURL_HTTP_VERSION_2_0')) {
            throw new NoHttp2SupportException();
        }

        // open connection handler if there's none
        if (is_null($this->handler)) {
            $this->handler = curl_init();
        }

        curl_setopt($this->handler, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->handler, CURLOPT_POST, 1);
        curl_setopt($this->handler, CURLOPT_HEADER, true);
        curl_setopt($this->handler, CURLOPT_POSTFIELDS, $message->getPayload());
        curl_setopt($this->handler, CURLOPT_HTTPHEADER, $message->getHeaders());
        curl_setopt($this->handler, CURLOPT_SSLCERT, $this->certificate);
        if (!is_null($this->passphrase)) {
            curl_setopt($this->handler, CURLOPT_SSLCERTPASSWD, $this->passphrase);
        }
        // curl_setopt($this->handler, CURLOPT_VERBOSE, true);

        $responseCollection = new Collection();
        foreach ($deviceCollection as $device) {
            curl_setopt($this->handler, CURLOPT_URL, $this->apnsUrl.$device->getToken());

            $rawResponse = curl_exec($this->handler);

            $headerSize = curl_getinfo($this->handler, CURLINFO_HEADER_SIZE);
            $responseHeaders = substr($rawResponse, 0, $headerSize);
            $responseBody = substr($rawResponse, $headerSize);

            $responseCollection->append(Response::parse($device->getToken(), $responseHeaders, $responseBody));
        }

        return $responseCollection;
    }
}
