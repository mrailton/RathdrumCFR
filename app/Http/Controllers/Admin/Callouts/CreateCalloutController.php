<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Callouts;

use App\Http\Controllers\Controller;
use App\Models\AMPDSCode;
use App\Models\Callout;
use App\Models\Member;
use Illuminate\Contracts\View\View;

final class CreateCalloutController extends Controller
{
    public function __invoke(): View
    {
        $this->authorize('create', Callout::class);
        $ampdsCodes = AMPDSCode::all()->mapWithKeys(fn ($code) => [$code->code => "{$code->code} - {$code->description}"]);

        $members = Member::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('admin.callouts.create', compact('ampdsCodes', 'members'));
    }
}
