<?php

namespace Binomedev\Contact\Mail;

use Binomedev\Contact\Contracts\MailMessage;
use Binomedev\Contact\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable implements MailMessage
{
    use Queueable;
    use SerializesModels;

    /**
     * @var string
     */
    public $message;

    /**
     * @var Subscriber
     */
    public $subscriber;

    /**
     * @var string
     */
    public $subject;

    /**
     * Create a new message instance.
     *
     * @param Subscriber $subscriber
     * @param string $message
     * @param null $subject
     */
    public function __construct(Subscriber $subscriber, string $message, $subject = null)
    {
        $this->subscriber = $subscriber;
        $this->message = $message;
        $this->subject = $subject ?? __('contact.mail_subject', ['app' => config('app.name')]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->getSubject())
            ->from($this->getSubscriber()->email)
            ->replyTo($this->getSubscriber()->email, $this->getSubscriber()->name)
            ->markdown('contact::emails.message');
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getSubscriber(): Subscriber
    {
        return $this->subscriber;
    }

    public function getMeta(): array
    {
        return $this->subscriber->only(['phone', 'ip', 'agent', 'data']);
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
