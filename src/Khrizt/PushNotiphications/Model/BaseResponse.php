<?php

namespace Khrizt\PushNotiphications\Model;

abstract class BaseResponse
{
    protected $headers = [];

    protected static function parseHeaders($rawHeaders)
    {
        $headers = [];

        if (!empty($rawHeaders)) {
            foreach (explode("\r\n", $rawHeaders) as $key => $header) {
                if ($i === 0) {
                    $headers['httpCode'] = $header;
                } else {
                    list($key, $value) = explode(': ', $line);
                    $headers[$key] = $value;
                }
            }
        }

        return $headers;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
