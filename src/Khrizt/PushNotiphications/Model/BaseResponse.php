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
                if ($key === 0) {
                    // get status from headers
                    $headers['httpCode'] = str_replace('HTTP/2 ', '', $header);
                } elseif (!empty(trim($header))) {
                    list($key, $value) = explode(': ', $header);
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
