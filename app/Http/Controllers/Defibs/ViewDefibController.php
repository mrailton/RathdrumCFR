<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs;

use App\Http\Controllers\Controller;
use App\Models\Defib;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ViewDefibController extends Controller
{
    public function __invoke(Request $request, Defib $defib): View
    {
        $defib->load(['notes.author', 'inspections.member']);

        return view('defibs.view', ['defib' => $defib]);
    }
}
