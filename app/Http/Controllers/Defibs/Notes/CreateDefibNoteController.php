<?php

declare(strict_types=1);

namespace App\Http\Controllers\Defibs\Notes;

use App\Models\Defib;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class CreateDefibNoteController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $defib = Defib::find($id);

        return view('defibs.notes.create', ['defib' => $defib]);
    }
}
