<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\AMPDSCodes;

use App\Http\Controllers\Controller;
use App\Models\AMPDSCode;
use Illuminate\Contracts\View\View;

final class CreateAMPDSCodeController extends Controller
{
    public function __invoke(): View
    {
        $this->authorize('create', AMPDSCode::class);
        return view('admin.ampds-codes.create');
    }
}
