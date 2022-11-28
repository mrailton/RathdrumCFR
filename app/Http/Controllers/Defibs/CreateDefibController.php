<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class CreateDefibController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('defibs.create');
    }
}
