<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\AMPDSCodes;

use App\Http\Controllers\Controller;
use App\Models\AMPDSCode;
use Illuminate\Contracts\View\View;

final class ShowAMPDSCodeController extends Controller
{
    public function __invoke(AMPDSCode $ampdsCode): View
    {
        $this->authorize('view', $ampdsCode);
        return view('admin.ampds-codes.show', compact('ampdsCode'));
    }
}
