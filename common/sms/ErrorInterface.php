<?php
namespace common\sms;

interface  ErrorInterface
{
    public function setError(string $message);

    public function getError();
}
