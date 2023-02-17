<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ShowRequestedReportsController extends Controller
{
    public function __invoke(Request $request, User $user): View
    {
        $user->with('reports');

        if (! $user->reports) {
            $user->reports()->create();
            $user->refresh();
        }

        return view('users.reports', ['user' => $user]);
    }
}
