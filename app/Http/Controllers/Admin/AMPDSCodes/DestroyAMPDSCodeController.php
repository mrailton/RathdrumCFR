<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\AMPDSCodes;

use App\Http\Controllers\Controller;
use App\Models\AMPDSCode;
use Illuminate\Http\RedirectResponse;

final class DestroyAMPDSCodeController extends Controller
{
    public function __invoke(AMPDSCode $ampdsCode): RedirectResponse
    {
        $this->authorize('delete', $ampdsCode);
        $ampdsCode->delete();

        return redirect()
            ->route('admin.ampds-codes.index')
            ->with('success', 'AMPDS Code deleted successfully.');
    }
}
