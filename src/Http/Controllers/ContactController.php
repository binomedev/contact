<?php

namespace Binomedev\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;
use Binomedev\Contact\Contact;
use Binomedev\Contact\Mail\ContactMessage;
use Binomedev\Contact\Models\Subscriber;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    use SEOTools;

    /**
     *
     */
    public function index()
    {
        $this->seo()->setTitle(__('contact.index_title'));

        return view('contact');
    }

    /**
     * Store the contact details
     * Send an email
     * @param Contact $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Contact $contact)
    {
        // Validate data
        $data = request()->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email',
            'phone' => 'present',
            'message' => 'required|string|min:10'
        ]);

        // Store the details as a subscriber for further usage.
        $subscriber = $contact->subscribe(
            $data['email'],
            $data['name'],
            $data['phone']
        );

        $contact->send($data['message'], $subscriber);

        return back();
    }

    public function test(Contact $contact)
    {
        $contact->testMessage();

        return back();
    }

    public function show()
    {
        $subscriber = Subscriber::factory()->make();
        $message = Str::random(20);

        return new ContactMessage($subscriber, $message);
    }
}
