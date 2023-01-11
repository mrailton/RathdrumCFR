<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs;

use App\Http\Controllers\Controller;
use App\Models\Defib;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ListDefibsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $defibs = Defib::paginate(10);

        return view('defibs.list', ['defibs' => $defibs]);
    }
}
