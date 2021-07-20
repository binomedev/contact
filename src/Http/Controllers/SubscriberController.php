<?php

namespace Binomedev\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Binomedev\Contact\Actions\Subscribe;
use Binomedev\Contact\Contact;
use Binomedev\Contact\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * Class SubscriberController
 * @package Binomedev\Contact\Http\Controllers
 * TODO: Refactor the unsubscribe method into a dedicated controller
 * TODO: Deprecate SubscriberController
 */
class SubscriberController extends Controller
{
    /**
     * @param Request $request
     * @param Contact $contact
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Subscribe $subscribeAction)
    {

        $subscribeAction->run($request->input());

        $message = __('contact::messages.subscribed');

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

        session()->flash('message', __('contact::messages.unsubscribed'));

        return redirect()->route('contact.index');
    }
}
