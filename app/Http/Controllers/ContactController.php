<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
            ],

            'subject' => [
                'required',
                'string',
                'max:200',
            ],

            'message' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        /*
         * Send email / store message here.
         *
         * Example:
         *
         * Mail::to('your-email@example.com')
         *     ->send(new ContactMail($validated));
         */

        return back()->with(
            'success',
            'Thank you for contacting us. We have received your message and will get back to you soon.'
        );
    }
}