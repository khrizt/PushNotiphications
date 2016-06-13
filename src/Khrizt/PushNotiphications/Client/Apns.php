<?php

namespace Khrizt\PushNotiphications\Client;

use Khrizt\PushNotiphications\Model\Apns\Message;
use Khrizt\PushNotiphications\Model\Apns\Response;
use Khrizt\PushNotiphications\Collection\Collection;
use Khrizt\PushNotiphications\Exceptions\NoHttp2SupportException;
use Khrizt\PushNotiphications\Constants;

class Apns
{
    protected $apnsUrl;

    protected $handler;

    protected $certificate;

    protected $passPhrase;

    public function __construct($environment, $certificate, $passPhrase = null)
    {
        if ($environment == Constants::PRODUCTION) {
            $this->apnsUrl = 'https://api.push.apple.com';
        } else {
            $this->apnsUrl = 'https://api.development.push.apple.com';
        }
        $this->apnsUrl .= '/3/device/';
        $this->certificate = $certificate;
        $this->passPhrase = $passPhrase;
    }

    public function send(Message $message, Collection $deviceCollection) : Collection
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
        if (!is_null($this->passPhrase)) {
            curl_setopt($this->handler, CURLOPT_SSLCERTPASSWD, $this->passPhrase);
        }
        curl_setopt($this->handler, CURLOPT_VERBOSE, true);

        $responseCollection = new Collection();
        foreach ($deviceCollection as $device) {
            curl_setopt($this->handler, CURLOPT_URL, $this->apnsUrl.$device->getToken());

            $rawResponse = curl_exec($this->handler);
            var_dump('rawResponse', $rawResponse);

            $status = curl_getinfo($this->handler, CURLINFO_HTTP_CODE);

            $headerSize = curl_getinfo($this->handler, CURLINFO_HEADER_SIZE);
            $responseHeaders = substr($rawResponse, 0, $headerSize);
            $responseBody = substr($rawResponse, $headerSize);

            $responseCollection->append(Response::parse($status, $responseHeaders, $responseBody));
        }

        return $responseCollection;
    }
}
