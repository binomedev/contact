<?php


namespace Binomedev\Contact\Http\Controllers;

use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Http\Request;

use Illuminate\Routing\Controller;

class GmailAuthController extends Controller
{
    public function login()
    {
        return LaravelGmail::redirect();
    }

    public function callback()
    {
        $token = LaravelGmail::makeToken();

        return redirect()->to('/');
    }

    public function logout()
    {
        LaravelGmail::logout(); //It returns exception if fails
        return redirect()->to('/');
    }
}
