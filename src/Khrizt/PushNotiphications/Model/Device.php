<?php

namespace Khrizt\PushNotiphications\Model;

class Device
{
    /**
     * @var string
     */
    private $token;

    /**
     * Constructor.
     *
     * @param string $token Token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get token.
     *
     * @return string
     */
    public function getToken()
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
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
}
