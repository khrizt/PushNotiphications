<?php

namespace Khrizt\PushNotiphications\Model;

class Device
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var int
     */
    private $badge;

    /**
     * @var string
     */
    private $sound;

    /**
     * Constructor.
     *
     * @param string $token Token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get token.
     *
     * @return string
     */
    public function getToken() : string
    {
        return $this->token;
    }

    /**
     * Set token.
     *
     * @param string $token Token
     *
     * @return \Khrizt\PushNotiphications\Model
     */
    public function setToken(string $token) : Device
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Gets the value of badge.
     *
     * @return int
     */
    public function getBadge() : int
    {
        return $this->badge;
    }

    /**
     * Sets the value of badge.
     *
     * @param int $badge the badge
     *
     * @return self
     */
    private function setBadge(int $badge) : Device
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Gets the value of sound.
     *
     * @return string
     */
    public function getSound() : string
    {
        return $this->sound;
    }

    /**
     * Sets the value of sound.
     *
     * @param string $sound the sound
     *
     * @return self
     */
    private function setSound(string $sound) : Device
    {
        $this->sound = $sound;

        return $this;
    }
}
