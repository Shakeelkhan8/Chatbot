<?php

namespace App\Http\Controllers;

use App\Mail\NewsLetterMail;
use App\Models\ContactForm;
use Illuminate\Http\Request;
use Mail;

class MainController extends Controller
{
    public function send_Mail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        Mail::to($request->email)->send(new NewsLetterMail($request->email));

        return back()->with('success', 'Mail Has been sent successfully');
    }

    public function formSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);
        try {

            ContactForm::create($validated);

            return back()->with('success', 'Thank you for contacting us. We will be in touch soon!');
            //code...
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function forms()
    {
        $forms = ContactForm::all();

        return view('backend_app.community-form.index', compact('forms'));
    }
}
