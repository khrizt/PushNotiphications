<?php

namespace Khrizt\PushNotiphications\Model\Fcm;

use Khrizt\PushNotiphications\Model\Message as MessageInterface;

/**
 * Message object.
 */
class Message implements MessageInterface
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

    /**
     * Field mapping for getting FCM field names.
     *
     * @var array
     */
    protected $fieldMapping = [
        'delayWhileIdle' => 'delay_while_idle',
        'collapseKey' => 'collapse_key',
        'timeToLive' => 'time_to_live',
        'data' => 'data',
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->notification = new Notification();
    }

    /**
     * Sets the title for the message.
     *
     * @param string $title Notification title
     *
     * @return self
     */
    public function setTitle(string $title): Message
    {
        $this->notification->setTitle($title);

        return $this;
    }

    /**
     * Gets message title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->notification->getTitle();
    }

    /**
     * Sets the body for the message.
     *
     * @param string $body Notification body
     *
     * @return self
     */
    public function setBody(string $body): Message
    {
        $this->notification->setBody($body);

        return $this;
    }

    /**
     * Gets message body.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->notification->getBody();
    }

    /**
     * Sets the icon for the notification.
     *
     * @param string $icon Notification icon
     *
     * @return self
     */
    public function setIcon(string $icon): Message
    {
        $this->notification->setIcon($icon);

        return $this;
    }

    /**
     * Gets icon value for notification.
     *
     * @return string
     */
    public function getIcon(): string
    {
        return $this->notification->getIcon();
    }

    /**
     * Sets the collapse key.
     *
     * @param string $collapseKey Collapse key
     *
     * @return self
     */
    public function setCollapseKey(string $collapseKey): Message
    {
        $this->collapseKey = $collapseKey;

        return $this;
    }

    /**
     * Get collapse key value.
     *
     * @return string
     */
    public function getCollapseKey()
    {
        return $this->collapseKey;
    }

    /**
     * Sets if message should not be sent until the device becomes active.
     *
     * @param bool $delayWhileIdle Delay while idle flag
     */
    public function setDelayWhileIdle(bool $delayWhileIdle): Message
    {
        $this->delayWhileIdle = $delayWhileIdle;

        return $this;
    }

    /**
     * Returns delay while idle value.
     *
     * @return bool
     */
    public function isDelayWhileIdle(): bool
    {
        return $this->delayWhileIdle;
    }

    /**
     * Sets notification time to live.
     *
     * @param int $timeToLive Time to live
     */
    public function setTimeToLive(int $timeToLive): Message
    {
        $this->timeToLive = $timeToLive;

        return $this;
    }

    /**
     * Gets time to live value.
     *
     * @return int
     */
    public function getTimeToLive(): int
    {
        return $this->timeToLive;
    }

    /**
     * Sets notification data.
     *
     * @param array $data Notification data
     */
    public function setData(array $data): Message
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Sets a single value in notification data.
     *
     * @param string $key   Data key
     * @param string $value Data value
     */
    public function setDataValue(string $key, string $value): Message
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Get notification data.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Maps fields to the correspondant APNs name.
     *
     * @param string $field Field name
     *
     * @return string
     */
    protected function mapField(string $field): string
    {
        if (!array_key_exists($field, $this->fieldMapping)) {
            return '';
        }

        return $this->fieldMapping[$field];
    }

    /**
     * Gets payload not encoded.
     *
     * @return array
     */
    public function getNoEncodedPayload(): array
    {
        $params = get_object_vars($this);

        $payload = array();
        foreach ($params as $key => $value) {
            if ($key === 'notification' && !is_null($value)) {
                $notificationPayload = $this->notification->getNoEncodedPayload();
                if (!empty($notificationPayload)) {
                    $payload[$key] = $this->notification->getNoEncodedPayload();
                }
            } elseif ($key === 'data' && count($value) === 0) {
                unset($payload['data']);
            } elseif (!empty($this->mapField($key)) && !is_null($value)) {
                $payload[$this->mapField($key)] = $value;
            }
        }

        if (empty($payload)) {
            throw new EmptyMessageException();
        }

        return $payload;
    }

    /**
     * Gets encoded payload.
     *
     * @return string
     */
    public function getPayload(): string
    {
        return json_encode($this->getNoEncodedPayload());
    }
}
