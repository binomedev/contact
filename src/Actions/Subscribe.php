<?php


namespace Binomedev\Contact\Actions;


use Binomedev\Contact\Contact;
use Binomedev\Contact\Models\Subscriber;
use Illuminate\Support\Facades\Validator;

class Subscribe
{

    /**
     * @var Contact
     */
    protected $contactManager;

    /**
     * Subscribe constructor.
     * @param Contact $contactManager
     */
    public function __construct(Contact $contactManager)
    {
        $this->contactManager = $contactManager;
    }

    /**
     * @param array $input
     * @return Subscriber
     * @throws \Illuminate\Validation\ValidationException
     */
    public function run(array $input = []): Subscriber
    {
        $data = $this->validate($input);

       return $this->contactManager->subscribe(
            $data['email']
        );
    }

    /**
     * @param array $input
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validate(array $input): array
    {
        return Validator::make($input, [
            'email' => 'required|email',
        ])->validated();
    }
}
