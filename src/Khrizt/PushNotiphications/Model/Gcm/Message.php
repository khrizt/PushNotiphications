<?php

namespace Khrizt\PushNotiphications\Model\Gcm;

// https://developers.google.com/cloud-messaging/concept-options
// //https://developers.google.com/cloud-messaging/http-server-ref
// 
class Message
{
    /**
     * Collapse key.
     *
     * @var string
     */
    protected $collapseKey;

    /**
     * Delay notification while idle.
     *
     * @var bool
     */
    protected $delayWhileIdle;

    /**
     * Notification time to live.
     *
     * @var int
     */
    protected $timeToLive;

    /**
     * Custom data for notification.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Notification message.
     *
     * @var Notification
     */
    protected $notification;

    /**
     * Notification priority.
     *
     * @var string
     */
    protected $priority;

    protected $fieldMapping = [
        'delayWhileIdle' => 'delay_while_idle',
        'collapseKey' => 'collapse_key',
        'timeToLive' => 'time_to_live',
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->notification = new Notification();
    }

    public function setCollapseKey($collapseKey)
    {
        if (!is_string($collapseKey)) {
            throw new \InvalidArgumentException('Collapse key must be a string value');
        }

        $this->collapseKey = $collapseKey;
    }

    public function getCollapseKey()
    {
        return $this->collapseKey;
    }

    public function setDelayWhileIdle($delayWhileIdle)
    {
        if (!is_bool($delayWhileIdle)) {
            throw new \InvalidArgumentException('Delay while idle parameter must be a boolean value');
        }

        $this->delayWhileIdle = $delayWhileIdle;
    }

    public function getDelayWhileIdle()
    {
        return $this->delayWhileIdle;
    }

    public function setTimeToLive($timeToLive)
    {
        if (!is_int($timeToLive)) {
            throw new \InvalidArgumentException('Time to live parameter must be an integer value');
        }

        $this->timeToLive = $timeToLive;
    }

    public function getTimeToLive()
    {
        return $this->timeToLive;
    }

    public function setData(array $data)
    {
        foreach ($data as $key => $value) {
            if (!is_scalar($key) || !is_scalar($value)) {
                throw new \InvalidArgumentException('Key / value pair '.$key.' / '.$value.' contains non-scalar values');
            }
        }
        $this->data = $data;
    }

    public function setDataValue($key, $value)
    {
        if (!is_scalar($key) || !is_scalar($value)) {
            throw new \InvalidArgumentException('Key and value for notification custom data must be scalar values');
        }

        $this->data[$key] = $value;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setNotification(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Maps fields to the correspondant APNs name.
     *
     * @param string $field Field name
     *
     * @return string
     */
    protected function mapField($field)
    {
        if (!array_key_exists($field, $this->fieldMapping)) {
            return $field;
        }

        return $this->fieldMapping[$key];
    }

    public function getPayload()
    {
        $params = get_object_vars($this);

        $payload = array();
        foreach ($params as $key => $value) {
            if ($key === 'notification' && !is_null($value)) {
                $payload[$this->mapField($key)] = $this->notification->getPayload();
            } elseif (!is_null($value)) {
                $payload[$this->mapField($key)] = $value;
            }
        }

        if (empty($payload)) {
            throw new EmptyMessageException();
        }

        return json_encode($payload);
    }
}
