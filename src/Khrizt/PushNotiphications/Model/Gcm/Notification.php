<?php

namespace Khrizt\PushNotiphications\Model\Gcm;

class Notification
{
    protected $title;

    protected $body;

    protected $icon;

    public function setTitle($title)
    {
        if (!is_string($title) || strlen($title) === 0) {
            throw new \InvalidArgumentException('Title must be a non-empty string');
        }

        $this->title = $title;

        return $this;
    }

    public function getTitle($title)
    {
        return $this->title;
    }

    public function setBody($body)
    {
        if (!is_string($body) || strlen($body) === 0) {
            throw new \InvalidArgumentException('Body must be a non-empty string');
        }

        $this->body = $body;

        return $this;
    }

    public function getBody($body)
    {
        return $this->body;
    }

    public function setIcon($icon)
    {
        if (!is_string($icon) || strlen($icon) === 0) {
            throw new \InvalidArgumentException('Icon must be a non-empty string');
        }

        $this->icon = $icon;

        return $this;
    }

    public function getIcon($icon)
    {
        return $this->icon;
    }

    public function getPayload()
    {
        return json_encode($this->getNoEncodedPayload());
    }

    public function getNoEncodedPayload()
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
