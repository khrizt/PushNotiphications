<?php

namespace Khrizt\PushNotiphications\Model\Fcm;

class Notification
{
    protected $title;

    protected $body;

    protected $icon;

    public function setTitle(string $title): Notification
    {
        if (strlen($title) === 0) {
            throw new \InvalidArgumentException('Title must be a non-empty string');
        }

        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setBody(string $body): Notification
    {
        if (strlen($body) === 0) {
            throw new \InvalidArgumentException('Body must be a non-empty string');
        }

        $this->body = $body;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setIcon(string $icon): Notification
    {
        if (strlen($icon) === 0) {
            throw new \InvalidArgumentException('Icon must be a non-empty string');
        }

        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getPayload(): string
    {
        return json_encode($this->getNoEncodedPayload());
    }

    public function getNoEncodedPayload(): array
    {
        $params = get_object_vars($this);
        foreach ($params as $key => $value) {
            if (is_null($value)) {
                unset($params[$key]);
            }
        }

        return $params;
    }
}
