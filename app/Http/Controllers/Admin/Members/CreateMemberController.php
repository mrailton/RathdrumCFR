<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Contracts\View\View;

final class CreateMemberController extends Controller
{
    public function __invoke(): View
    {
        $this->authorize('create', Member::class);
        return view('admin.members.create');
    }
}
