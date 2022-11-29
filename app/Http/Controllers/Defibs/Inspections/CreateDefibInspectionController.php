<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs\Inspections;

use App\Models\Defib;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class CreateDefibInspectionController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $defib = Defib::find($id);
        $members = Member::all();

        return view('defibs.inspections.create', ['defib' => $defib, 'members' => $members]);
    }
}
