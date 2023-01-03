<?php

declare(strict_types=1);

namespace App\Http\Controllers\Callouts;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CreateCalloutController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('callouts.create');
    }
}
