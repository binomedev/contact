<?php

namespace Binomedev\Contact;

use Binomedev\Contact\Contracts\MailMessage;
use Binomedev\Contact\Events\MessageSent;
use Binomedev\Contact\Mail\ContactMessage;
use Binomedev\Contact\Models\Message;
use Binomedev\Contact\Models\Subscriber;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class Contact
{
    public function unsubscribeUrl(Subscriber $subscriber): string
    {
        return URL::signedRoute('contact.unsubscribe', $subscriber);
    }

    public function testMessage(): Contact
    {
        $fromUser = Subscriber::factory()->create();
        $message = __('contact::messages.test_message', ['url' => config('app.url')]);

        $this->send($message, $fromUser);

        return $this;
    }

    public function send($message, Subscriber $subscriber, $to = null): Contact
    {
        // Where this message will be sent
        $recipient = $to ?? $this->getDefaultRecipient();

        // Get the template to be used
        $mailable = new ContactMessage($subscriber, $message);

        // Do we want to record messages into the database?
        if ((bool)config('contact.save_messages')) {
            $this->saveMailable($mailable, $recipient);
        }

        // Send the actual email.
        Mail::to($recipient)->send($mailable);

        // Fire an event that a message was sent.
        event(new MessageSent($subscriber, $mailable));

        // Inform the user that the message was sent successfully.
        session()->flash('message', __('contact::messages.message_sent'));

        return $this;
    }

    /**
     * Find the users where to send the contact message.
     *
     * @return string
     */
    public function getDefaultRecipient(): string
    {
        return config('contact.default_email_receiver');
    }

    /**
     * Stores a mailable into the database
     *
     * @param MailMessage $mailable
     * @param string $recipient
     * @return mixed
     */
    public function saveMailable(MailMessage $mailable, string $recipient)
    {
        return Message::create([
            'subscriber_id' => $mailable->getSubscriber()->id,
            'from' => $mailable->getSubscriber()->email,
            'to' => $recipient,
            'content' => $mailable->getMessage(),
            'subject' => $mailable->getSubject(),
        ]);
    }

    /**
     * Add a new subscriber if there isn't one, otherwise just retrieve it.
     *
     * @param $email
     * @param null $name
     * @param null $phone
     * @param array $data
     * @return mixed
     */
    public function subscribe($email, $name = null, $phone = null, $data = []): Subscriber
    {
        $name = $name ?? explode('@', $email)[0];
        $ip = request()->getClientIp();
        $agent = request()->userAgent();

        $subscriber = Subscriber::firstOrCreate(
            compact('email'),
            compact('name', 'phone', 'data', 'ip', 'agent')
        );

        // Check if the subscriber is set to inactive, if so then set it active again.
        if (! $subscriber->active) {
            $subscriber->active = true;
            $subscriber->save();
        }

        return $subscriber;
    }

    /**
     * If the subscriber is active, set him as inactive.
     *
     * @param Subscriber $subscriber
     * @return Subscriber
     */
    public function unsubscribe(Subscriber $subscriber)
    {
        if ($subscriber->active) {
            $subscriber->active = false;
            $subscriber->save();
        }
    }
}
