<?php

namespace Khrizt\PushNotiphications\Model\Apns;

use Khrizt\PushNotiphications\Exception\EmptyMessageException;

/**
 * Message object.
 */
class Message
{
    /**
     * Notification id.
     *
     * @var string
     */
    protected $notificationId;

    /**
     * Notification expiration time.
     *
     * @var Datetime
     */
    protected $expirationTime;

    /**
     * Notification priority.
     *
     * @var int
     */
    protected $priority;

    /**
     * Notification topic.
     *
     * @var string
     */
    protected $topic;

    /**
     * Title.
     *
     * @var string
     */
    protected $title;

    /**
     * Body.
     *
     * @var string
     */
    protected $body;

    /**
     * Action locale key.
     *
     * @var string
     */
    protected $actionLocaleKey;

    /**
     * Locale key.
     *
     * @var string
     */
    protected $localeKey;

    /**
     * Locale arguments.
     *
     * @var array
     */
    protected $localeArguments;

    /**
     * Launch image.
     *
     * @var string
     */
    protected $launchImage;

    /**
     * Title locale key.
     *
     * @var string
     */
    protected $titleLocaleKey;

    /**
     * Title locale arguments.
     *
     * @var array
     */
    protected $titleLocaleArguments;

    /**
     * Payload fields names' mapping.
     *
     * @var array
     */
    protected $payloadFieldMapping = [
        'title' => 'title',
        'body' => 'body',
        'titleLocaleKey' => 'title-loc-key',
        'titleLocaleArguments' => 'title-loc-args',
        'actionLocaleKey' => 'action-loc-key',
        'localeKey' => 'loc-key',
        'localeArguments' => 'loc-args',
        'launchImage' => 'launch-image',
    ];

    /**
     * Headers fields names' mapping.
     *
     * @var array
     */
    protected $headersFieldMapping = [
        'notificationId' => 'apns-id',
        'expirationTime' => 'apns-expiration',
        'priority' => 'apns-priority',
        'topic' => 'apns-topic',
    ];

    /**
     * Gets the Notification id.
     *
     * @return string
     */
    public function getNotificationId()
    {
        return $this->notificationId;
    }

    /**
     * Sets the Notification id.
     *
     * @param string $notificationId the notification id
     *
     * @return self
     */
    public function setNotificationId($notificationId)
    {
        $this->notificationId = $notificationId;

        return $this;
    }

    /**
     * Gets the Notification expiration time.
     *
     * @return Datetime
     */
    public function getExpirationTime()
    {
        return $this->expirationTime;
    }

    /**
     * Sets the Notification expiration time.
     *
     * @param Datetime $expirationTime the expiration time
     *
     * @return self
     */
    public function setExpirationTime(Datetime $expirationTime)
    {
        $this->expirationTime = $expirationTime;

        return $this;
    }

    /**
     * Gets the Notification priority.
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Sets the Notification priority.
     *
     * @param int $priority the priority
     *
     * @return self
     */
    public function setPriority($priority)
    {
        if (!in_array($priority, [5, 10])) {
            throw new \InvalidArgumentException('Priority values can only be 5: normal and 10: high');
        }

        $this->priority = $priority;

        return $this;
    }

    /**
     * Gets the Notification topic.
     *
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Sets the Notification topic.
     *
     * @param string $topic the topic
     *
     * @return self
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Returns message body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set message body.
     *
     * @param string $body
     *
     * @return \Khrizt\PushNotiphications\Model\Apns\Message
     */
    public function setBody($body)
    {
        if (empty($body)) {
            throw new \InvalidArgumentException('Message body can not be empty');
        }
        $this->body = $body;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return \Khrizt\PushNotiphications\Model\Apns\Message
     */
    public function setTitle($title)
    {
        if (empty($title)) {
            throw new \InvalidArgumentException('Title can not be empty');
        }
        $this->title = $title;

        return $this;
    }

    /**
     * Get action locale key.
     *
     * @return string
     */
    public function getActionLocaleKey()
    {
        return $this->actionLocaleKey;
    }

    /**
     * Set action locale key.
     *
     * @param string $key
     *
     * @return \Khrizt\PushNotiphications\Model\Apns\Message
     */
    public function setActionLocaleKey($key)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('Action locale key can not be empty');
        }
        $this->actionLocaleKey = $key;

        return $this;
    }

    /**
     * Get locale key.
     *
     * @return string
     */
    public function getLocaleKey()
    {
        return $this->localeKey;
    }

    /**
     * Set locale key.
     *
     * @param string $key
     *
     * @return \Khrizt\PushNotiphications\Model\Apns\Message
     */
    public function setLocaleKey($key)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('Locale key can not be empty');
        }
        $this->localeKey = $key;

        return $this;
    }

    /**
     * Get locale arguments.
     *
     * @return array|null
     */
    public function getLocaleArguments()
    {
        return $this->localeArguments;
    }

    /**
     * Set locale arguments.
     *
     * @param array $arguments
     *
     * @return \Khrizt\PushNotiphications\Model\Apns\Message
     */
    public function setLocaleArguments(array $arguments)
    {
        foreach ($arguments as $a) {
            if (!is_scalar($a)) {
                throw new \InvalidArgumentException('Locale arguments can only contain scalar values');
            }
        }
        $this->localeArguments = $arguments;

        return $this;
    }

    /**
     * Get launch image.
     *
     * @return string
     */
    public function getLaunchImage()
    {
        return $this->launchImage;
    }

    /**
     * Set launch image.
     *
     * @param string $image
     *
     * @return \Khrizt\PushNotiphications\Model\Apns\Message
     */
    public function setLaunchImage($image)
    {
        if (empty($image)) {
            throw new \InvalidArgumentException('Launch image can not be empty');
        }
        $this->launchImage = $image;

        return $this;
    }

    /**
     * Get title locale key.
     *
     * @return string
     */
    public function getTitleLocaleKey()
    {
        return $this->titleLocaleKey;
    }

    /**
     * Set title locale key.
     *
     * @param string $key
     *
     * @return \Khrizt\PushNotiphications\Model\Apns\Message
     */
    public function setTitleLocaleKey($key)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('Title locale key can not be empty');
        }
        $this->titleLocaleKey = $key;

        return $this;
    }

    /**
     * Get title locale arguments.
     *
     * @return array|null
     */
    public function getTitleLocaleArguments()
    {
        return $this->titleLocaleArguments;
    }

    /**
     * Set title locale arguments.
     *
     * @param array $argumentss
     *
     * @return \Khrizt\PushNotiphications\Model\Apns\Message
     */
    public function setTitleLocaleArguments(array $argumentss)
    {
        foreach ($argumentss as $a) {
            if (!is_scalar($a)) {
                throw new \InvalidArgumentException('Title arguments must only contain scalar values');
            }
        }
        $this->titleLocaleArguments = $argumentss;

        return $this;
    }
    /**
     * Maps fields to the correspondant APNs name.
     *
     * @param string $field Field name
     *
     * @return string
     */
    protected function payloadMapField($field)
    {
        if (!array_key_exists($field, $this->payloadFieldMapping)) {
            return;
        }

        return $this->payloadFieldMapping[$field];
    }

    /**
     * Maps headers fields to the correspondant APNs name.
     *
     * @param string $field Field name
     *
     * @return string
     */
    protected function headersMapField($field)
    {
        if (!array_key_exists($field, $this->headersFieldMapping)) {
            return;
        }

        return $this->headersFieldMapping[$field];
    }

    /**
     * Create payload for APNs Provider API. Will return JSON encoded data or the body message only.
     *
     * @return string
     */
    public function getPayload()
    {
        $params = get_object_vars($this);

        $payload = ['aps' => ['alert' => []]];
        foreach ($params as $key => $value) {
            if (!is_null($value) && !is_null($this->payloadMapField($key))) {
                $payload['aps']['alert'][$this->payloadMapField($key)] = $value;
            }
        }

        if (empty($payload)) {
            throw new EmptyMessageException();
        }

        return json_encode($payload, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Create headers for APNs Provider API. Will return array data.
     *
     * @return string
     */
    public function getHeaders()
    {
        $params = get_object_vars($this);

        $headers = array();
        foreach ($params as $key => $value) {
            if (!is_null($value) && !is_null($this->headersMapField($key))) {
                $headers[$this->headersMapField($key)] = $value;
            }
        }

        return $headers;
    }
}
