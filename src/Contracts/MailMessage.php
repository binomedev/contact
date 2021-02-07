<?php


namespace Binomedev\Contact\Contracts;


use Binomedev\Contact\Models\Subscriber;

interface MailMessage
{

    public function getSubject() : string;
    public function getSubscriber() : Subscriber;
    public function getMeta() : array;
    public function getMessage() : string;
}
