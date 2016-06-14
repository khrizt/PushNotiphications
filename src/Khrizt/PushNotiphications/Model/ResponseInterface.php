<?php

namespace Khrizt\PushNotiphications\Model;

interface ResponseInterface
{
    public function getNotificationId();

    public function getToken();

    public function getIsOk();

    public function getErrorCode();

    public function getErrorMessage();
}
