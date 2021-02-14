<?php

use Binomedev\Contact\Http\Controllers\ContactController;
use Binomedev\Contact\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {

    // Contact Form
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    if (app()->environment() === 'local') {
        Route::get('/mailable', [ContactController::class, 'show'])->middleware('auth');
    }

    // Newsletter
    Route::post('/newsletter/subscribe', [SubscriberController::class, 'store'])->name('contact.subscribe');
    Route::get('/newsletter/unsubscribe/{subscriber}', [SubscriberController::class, 'delete'])->name('contact.unsubscribe');

});
