<?php

namespace Khrizt\PushNotiphications\Model;

interface ResponseInterface
{
    public function getNotificationId() : string;

    public function getToken() : string;

    public function getIsOk() : bool;

    public function getErrorCode() : string;

    public function getErrorMessage() : string;
}
