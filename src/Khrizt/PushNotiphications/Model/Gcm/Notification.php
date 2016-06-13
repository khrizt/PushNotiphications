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
    }

    public function getIcon($icon)
    {
        return $this->icon;
    }
}
