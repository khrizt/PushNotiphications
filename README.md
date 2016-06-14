# PushNotiphications
Push notifications library for Apns and Gcm for PHP

Using only cURL as library and Symfony Console for command-line commands this package supports Google Cloud Message (not Firebase Cloud Message yet) and APNS with HTTP/2 mode.

## Requirements

* PHP 7.0+
* PHP Curl and OpenSSL modules
* cURL with HTTP/2 support (check this for Debian/Ubuntu users: https://serversforhackers.com/video/curl-with-http2-support)

## Example

GCM

```php
use Khrizt\PushNotiphications\Client\Gcm;
use Khrizt\PushNotiphications\Collection\Collection;
use Khrizt\PushNotiphications\Model\Device;
use Khrizt\PushNotiphications\Model\Gcm\Message as GcmMessage;

$gcmMessage = new GcmMessage();
$gcmMessage->setBody($message)
           ->setTitle($title);

$device = new Device($token);
$collection = new Collection();
$collection->append($device);

$client = new Gcm($apiKey);
$responseCollection = $client->send($gcmMessage, $collection);

foreach ($responseCollection as $response) {
    echo 'Status for notification sent to '.$response->getToken().' was '.($response->getIsOk() ? 'OK' : ' Error. Error message: '.$response->getErrorMessage());
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
    echo 'Status for notification sent to '.$response->getToken().' was '.($response->getIsOk() ? 'OK' : '. Error message: '.$response->getErrorMessage());
}

```