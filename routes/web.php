<?php

use Binomedev\Contact\Http\Controllers\ContactController;
use Binomedev\Contact\Http\Controllers\GmailAuthController;
use Binomedev\Contact\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {

    // Contact Form
    if (config('contact.enable_legacy_support')) {
        Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
        Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

        if (app()->environment() === 'local') {
            Route::get('/contact/mailable', [ContactController::class, 'show'])->middleware('auth');
            Route::get('/contact/test', [ContactController::class, 'test']);
        }
    }

    // Newsletter
    Route::post('/newsletter/subscribe', [SubscriberController::class, 'store'])->name('contact.subscribe');
    Route::get('/newsletter/unsubscribe/{subscriber}', [SubscriberController::class, 'delete'])->name('contact.unsubscribe');

    if (config('contact.enable_gmail_api')) {
        Route::get('/oauth/gmail', [GmailAuthController::class, 'login'])->name('gmail.login');
        Route::get('/oauth/gmail/callback', [GmailAuthController::class, 'callback']);
        Route::get('/oauth/gmail/logout', [GmailAuthController::class, 'logout'])->name('gmail.logout');
    }

});
