<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contact;

use App\Mail\ContactFormMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Contact\ProcessContactUsRequest;

class ProcessContactUsController extends Controller
{
    public function __invoke(ProcessContactUsRequest $request): RedirectResponse
    {
        Mail::to(config('app.admin_email'))->send(new ContactFormMail($request->get('name'), $request->get('email'), $request->get('phone'), $request->get('message')));

        return redirect()->route('index');
    }
}
