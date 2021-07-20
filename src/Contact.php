<?php

namespace Binomedev\Contact;

use Binomedev\Contact\Contracts\MailMessage;
use Binomedev\Contact\Events\MessageSent;
use Binomedev\Contact\Mail\ContactMessage;
use Binomedev\Contact\Models\Message;
use Binomedev\Contact\Models\Subscriber;
use Binomedev\Settings\MailSettings;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class Contact
{
    /**
     * @var ContactSettings
     */
    private $settings;

    private ?string $errormessage = null;

    public function unsubscribeUrl(Subscriber $subscriber): string
    {
        return URL::signedRoute('contact.unsubscribe', ['subscriber' => $subscriber]);
    }

    public function settings(): ContactSettings
    {
        if (!$this->settings) {
            $this->settings = app(ContactSettings::class);
        }

        return $this->settings;
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
        // TODO: Add support for multiple recipients
        $recipient = $to ?? $this->getDefaultRecipient();

        $subject = $this->getSubject($subscriber);

        // Get the template to be used
        $mailable = $this->makeMailable($subscriber, $message, $subject);

        try {
            // Send the email
            if (LaravelGmail::check()) {
                $this->sendWithGmailApi($recipient, $mailable);
            } else {
                $this->sendWithDefault($recipient, $mailable);
            }

            // Do we want to record messages into the database?
            if ($this->isSavingMessages()) {
                $this->saveMailable($mailable, $recipient);
            }

            // Fire an event that a message was sent.
            event(new MessageSent($subscriber, $mailable));

            // Inform the user that the message was sent successfully.

        } catch (\Exception $exception) {
            // Inform the user that the message was not sent.
            $this->errormessage = $exception->getMessage();
        }

        return $this;
    }

    /**
     * Find the users where to send the contact message.
     *
     * @return string
     */
    public function getDefaultRecipient(): string
    {
        return nova_get_setting(MailSettings::DEFAULT_TO, config('contact.default_to'));
    }

    private function getSubject(Subscriber $subscriber)
    {
        $vars = [
            '{APP_NAME}' => config('app.name'),
            '{SENDER_EMAIL}' => $subscriber->email,
            '{SENDER_NAME}' => $subscriber->name,
            '{SENDER_PHONE}' => $subscriber->phone,
        ];

        $subject = nova_get_setting(MailSettings::DEFAULT_SUBJECT);

        foreach ($vars as $search => $replace) {
            $subject = str_replace($search, $replace, $subject);
        }

        return $subject;
    }

    private function makeMailable(Subscriber $subscriber, string $message, string $subject): Mailable|MailMessage
    {
        $mailable = new ContactMessage($subscriber, $message, $subject);

        $mailable->from($this->getFromAddress());
        $mailable->priority($this->getPriority());

        return $mailable;
    }

    private function getFromAddress(): string
    {
        return nova_get_setting(MailSettings::DEFAULT_FROM, config('mail.from.address'));
    }

    private function getPriority(): int
    {
        return (int)nova_get_setting(MailSettings::PRIORITY, config('contact.priority', 3));
    }

    private function sendWithGmailApi($recipient, Mailable|MailMessage $mailable)
    {
        $mail = new \Dacastro4\LaravelGmail\Services\Message\Mail();
        $mail->to($recipient);
        $mail->from($mailable->getSubscriber()->email, $mailable->getSubscriber()->name);
        $mail->subject($mailable->getSubject());
        $mail->priority($this->getPriority());
        $mail->message($mailable->render());
        $mail->send();
    }

    private function sendWithDefault($recipient, Mailable $mailable)
    {
        Mail::to($recipient)->send($mailable);
    }

    private function isSavingMessages(): bool
    {
        return (bool)nova_get_setting(MailSettings::SAVE_MESSAGES, config('contact.save_messages'));
    }

    /**
     * Stores a mailable into the database
     *
     * @param MailMessage $mailable
     * @param string $recipient
     * @return mixed
     */
    public function saveMailable(MailMessage $mailable, string $recipient): mixed
    {
        return Message::create([
            'subscriber_id' => $mailable->getSubscriber()->id,
            'from' => $this->getFromAddress(),
            'to' => $recipient,
            'content' => $mailable->getMessage(),
            'subject' => $mailable->getSubject(),
        ]);
    }

    public function hasSucceeded(): bool
    {
        return empty($this->errormessage);
    }

    public function hasFailed(): bool
    {
        return !empty($this->errormessage);
    }

    public function errorMessage(): ?string
    {
        return $this->errormessage;
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

        $subscriber = Subscriber::firstOrNew(
            compact('email'),
            compact('name', 'phone', 'data', 'ip', 'agent')
        );

        // Check if the subscriber is set to inactive, if so then set it active again.
        if (!$subscriber->active) {
            $subscriber->active = true;
        }

        if ($this->isSavingSubscribers()) {
            $subscriber->save();
        }

        return $subscriber;
    }

    private function isSavingSubscribers(): bool
    {
        return (bool)nova_get_setting(MailSettings::SAVE_SUBSCRIBERS, config('contact.save_subscribers'));
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
