<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs;

use App\Http\Controllers\Controller;
use App\Models\Defib;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ViewDefibController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $defib = Defib::with(['notes.author', 'inspections.member'])->find($id);

        if (!$defib) {
            abort(404);
        }

        return view('defibs.view', ['defib' => $defib]);
    }
}
