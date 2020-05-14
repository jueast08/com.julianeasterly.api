<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\Captcha;
use Mail;

class EmailController extends Controller
{
    
    public function send(Request $request) {
        $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required | email',
                'message' => 'required ',
                'recaptcha' => new Captcha()
            ]
        );

        $data = ['name' => $request->get('name'), 'email' => $request->get('email'), 'content' => $request->get('message')];

        Mail::send('emails.contact', $data, function ($message) use ($data)
        {
            $message->from('contact@julianeasterly.com', 'JulianEasterly.com');
            $message->to($data['email'], $data['name'])
                    ->bcc('julianeasterly@gmail.com', 'Admin')
                    ->subject('Your Message to Julian Easterly');
        });

        Mail::send('emails.contact', $data, function ($message) use ($data)
        {
            $message->from($data['email'], $data['name']);
            $message->to('julianeasterly@gmail.com', 'Admin')
                    ->subject($data['name'].' sent you an email from JulianEasterly.com');
        });

        return response()->json(['message' => 'Request completed']);
    }
}
