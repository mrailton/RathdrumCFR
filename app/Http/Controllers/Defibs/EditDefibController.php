<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs;

use App\Http\Controllers\Controller;
use App\Models\Defib;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class EditDefibController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $defib = Defib::find($id);

        return view('defibs.update', ['defib' => $defib]);
    }
}
