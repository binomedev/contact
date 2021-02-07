<?php

namespace Binomedev\Contact\Database\Factories;

use Binomedev\Contact\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;


class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'to' => '',
            'from' => '',
            'subject' => '',
            'content' => '',
            'mailable' => '',
            'meta' => '',
        ];
    }
}

