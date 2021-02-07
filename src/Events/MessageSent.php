<?php

namespace Binomedev\Contact\Events;

use Binomedev\Contact\Contracts\MailMessage;
use Binomedev\Contact\Models\Subscriber;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageSent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var Subscriber
     */
    public $sender;
    /**
     * @var Mailable|MailMessage
     */
    public $mailable;

    /**
     * Create a new event instance.
     *
     * @param Subscriber $sender
     * @param Mailable|MailMessage $mailable
     */
    public function __construct($sender, $mailable)
    {
        $this->sender = $sender;
        $this->mailable = $mailable;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
