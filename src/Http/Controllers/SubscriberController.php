<?php

namespace Binomedev\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Binomedev\Contact\Contact;
use Binomedev\Contact\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubscriberController extends Controller
{
    /**
     * @param Request $request
     * @param Contact $contact
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);

        $email = $data['email'];


        $contact->subscribe($email);

        $message = __('contact.subscribed');

        if ($request->wantsJson()) {
            return response()->json(compact('message'));
        }

        session()->flash('message', $message);

        return back();
    }

    /**
     * This endpoint should be reached whenever a subscriber wants to unsubscribe.
     * The subscriber will not be deleted, however it will be set as unsubscribed and no emails will be sent.
     * @param Subscriber $subscriber
     * @param Contact $contact
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function delete(Subscriber $subscriber, Contact $contact, Request $request)
    {
        // Check if signature is correct, otherwise abort the request.
        if (! $request->hasValidSignature()) {
            return abort(Response::HTTP_UNAUTHORIZED);
        }

        $contact->unsubscribe($subscriber);

        session()->flash('message', __('contact.unsubscribed'));

        return redirect()->route('contact.index');
    }
}
