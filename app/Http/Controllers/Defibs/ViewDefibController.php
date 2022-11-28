<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs;

use App\Models\Defib;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class ViewDefibController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $defib = Defib::find($id);

        if (!$defib) {
            abort(404);
        }

        return view('defibs.view', ['defib' => $defib]);
    }
}
