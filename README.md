# PushNotiphications
Push notifications library for Apns and Fcm for PHP

Using only cURL as library and Symfony Console for command-line commands this package supports Firebase Cloud Message (if you still use GCM, you can use it too) and APNS with HTTP/2 mode.

## Requirements

* PHP 7.1+
* PHP Curl and OpenSSL modules
* cURL with HTTP/2 support (check this for Debian/Ubuntu users: https://serversforhackers.com/video/curl-with-http2-support)

## Installation

```
composer require khrizt/push-notiphications
```

## Usage example

GCM

```php
use Khrizt\PushNotiphications\Client\Fcm;
use Khrizt\PushNotiphications\Collection\Collection;
use Khrizt\PushNotiphications\Model\Device;
use Khrizt\PushNotiphications\Model\Fcm\Message as FcmMessage;

$fcmMessage = new FcmMessage();
$fcmMessage->setBody($message)
           ->setTitle($title);

$device = new Device($token);
$collection = new Collection();
$collection->append($device);

$client = new Fcm($apiKey);
$responseCollection = $client->send($fcmMessage, $collection);

foreach ($responseCollection as $response) {
    echo 'Status for notification sent to '.$response->getToken().' was '.($response->isOk() ? 'OK' : ' Error. Error message: '.$response->getErrorMessage());
}

```

APNS

```php
use Khrizt\PushNotiphications\Client\Apns;
use Khrizt\PushNotiphications\Collection\Collection;
use Khrizt\PushNotiphications\Constants;
use Khrizt\PushNotiphications\Model\Device;
use Khrizt\PushNotiphications\Model\Apns\Message as ApnsMessage;

$apnsMessage = new ApnsMessage();
$apnsMessage->setBody($message)
            ->setTopic($topic);

$device = new Device($token);
$collection = new Collection();
$collection->append($device);

$client = new Apns(Constants::DEVELOPMENT, $certificate, $certificatePassphrase);
$responseCollection = $client->send($apnsMessage, $collection);

foreach ($responseCollection as $response) {
    echo 'Status for notification sent to '.$response->getToken().' was '.($response->isOk() ? 'OK' : '. Error message: '.$response->getErrorMessage());
}

```