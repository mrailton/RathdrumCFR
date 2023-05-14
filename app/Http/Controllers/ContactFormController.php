<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Contact\ProcessContactUsRequest;
use App\Mail\ContactFormMail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function process(ProcessContactUsRequest $request): RedirectResponse
    {
        Mail::to(config('app.admin_email'))->queue(new ContactFormMail(
            name: $request->validated('name'),
            email: $request->validated('email'),
            phone: $request->validated('phone'),
            message: $request->validated('message')
        ));

        toast()->success('Contact form successfully submitted')->pushOnNextPage();

        return redirect()->route('index');
    }
}
