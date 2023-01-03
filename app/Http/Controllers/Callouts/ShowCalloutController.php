<?php

declare(strict_types=1);

namespace App\Http\Controllers\Callouts;

use App\Http\Controllers\Controller;
use App\Models\Callout;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ShowCalloutController extends Controller
{
    public function __invoke(Request $request, Callout $callout): View
    {
        return view('callouts.show', ['callout' => $callout]);
    }
}
