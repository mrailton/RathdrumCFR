<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs;

use App\Models\Defib;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListDefibsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $defibs = Defib::all();

        return view('defibs.list', ['defibs' => $defibs]);
    }
}
