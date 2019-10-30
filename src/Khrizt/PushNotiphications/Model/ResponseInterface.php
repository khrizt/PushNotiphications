<?php

namespace Khrizt\PushNotiphications\Model;

interface ResponseInterface
{
    public function getNotificationId(): string;

    public function getToken(): string;

    public function isUnregisteredToken(): bool;

    public function isInvalidToken(): bool;

    public function isOk(): bool;

    public function getErrorCode(): ?string;

    public function getErrorMessage(): string;
}
