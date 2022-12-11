<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ProcessContactUsRequest;
use App\Mail\ContactFormMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ProcessContactUsController extends Controller
{
    public function __invoke(ProcessContactUsRequest $request): RedirectResponse
    {
        Mail::to(config('app.admin_email'))->queue(new ContactFormMail($request->get('name'), $request->get('email'), $request->get('phone'), $request->get('message')));

        return redirect()->route('index')->with('success', 'Contact form successfully submitted');
    }
}
