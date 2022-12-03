<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contact;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactUsPageController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('contact');
    }
}
