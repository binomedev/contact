<?php

namespace Binomedev\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;
use Binomedev\Contact\Actions\SendMessage;
use Binomedev\Contact\Contact;
use Binomedev\Contact\Mail\ContactMessage;
use Binomedev\Contact\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class ContactController
 * @package Binomedev\Contact\Http\Controllers
 * @deprecated Use livewire components instead
 */
class ContactController extends Controller
{
    use SEOTools;

    /**
     *
     */
    public function index()
    {
        $this->seo()->setTitle(__('contact::messages.index_title'));

        return view('contact::index');
    }

    /**
     *
     * @param SendMessage $sendMessageAction
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SendMessage $sendMessageAction, Request $request)
    {
        $sendMessageAction->run($request->input());

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
