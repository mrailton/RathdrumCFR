<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Contact\ProcessContactUsRequest;
use App\Mail\ContactFormMail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
{
    public function show(Request $request): View
    {
        return view('contact');
    }

    public function process(ProcessContactUsRequest $request): RedirectResponse
    {
        Mail::to(config('app.admin_email'))->queue(new ContactFormMail($request->get('name'), $request->get('email'), $request->get('phone'), $request->get('message')));

        return redirect()->route('index')->with('success', 'Contact form successfully submitted');
    }
}
