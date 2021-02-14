<?php

namespace Binomedev\Contact;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Binomedev\Contact\Contact
 */
class ContactFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Contact::class;
    }
}
