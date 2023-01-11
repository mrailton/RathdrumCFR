<?php

declare(strict_types=1);

namespace App\Http\Controllers\Callouts;

use App\Http\Controllers\Controller;
use App\Models\Callout;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ListCalloutsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $callouts = Callout::orderBy('incident_date')->paginate(2);

        return view('callouts.list', ['callouts' => $callouts]);
    }
}
