<?php


namespace Binomedev\Contact\Actions;


use Binomedev\Contact\Contact;
use Binomedev\Contact\Models\Subscriber;
use Illuminate\Support\Facades\Validator;

/**
 * Class SendMessage
 * @package Binomedev\Contact\Actions
 *
 * - Store the request data as contact details
 * - Sends an email
 */
class SendMessage
{
    /**
     * @var Contact
     */
    protected $contactManager;

    /**
     * SendMessage constructor.
     * @param $contactManager
     */
    public function __construct(Contact $contactManager)
    {
        $this->contactManager = $contactManager;
    }

    /**
     * Performs the action
     *
     * @param array $input
     * @throws \Illuminate\Validation\ValidationException
     */
    public function run(array $input = [])
    {
        // Validate data
        $data = $this->validate($input);

        // Store the details as a subscriber for further usage.
        $subscriber = $this->makeSubscriber($data);

        // Send the email
        $this->contactManager->send($data['message'], $subscriber);
    }

    /**
     * Validate the input data
     *
     * @param array $input
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validate(array $input): array
    {
        return Validator::make($input, [
            'name' => 'required|string|min:3',
            'email' => 'required|email',
            'phone' => 'present',
            'message' => 'required|string|min:10',
        ])->validated();
    }

    /**
     * Get a valid subscriber for further usage
     *
     * @param array $data
     * @return Subscriber|mixed
     */
    protected function makeSubscriber(array $data): Subscriber
    {
        return $this->contactManager->subscribe(
            $data['email'],
            $data['name'],
            $data['phone']
        );
    }
}
