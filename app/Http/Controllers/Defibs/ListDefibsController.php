<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs;

use App\Http\Controllers\Controller;
use App\Models\Defib;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListDefibsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $defibs = Defib::all();

        return view('defibs.list', ['defibs' => $defibs]);
    }
}
