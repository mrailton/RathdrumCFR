<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Defibs;

use App\Http\Controllers\Controller;
use App\Models\Defib;
use Illuminate\Contracts\View\View;

final class CreateDefibController extends Controller
{
    public function __invoke(): View
    {
        $this->authorize('create', Defib::class);
        return view('admin.defibs.create');
    }
}
