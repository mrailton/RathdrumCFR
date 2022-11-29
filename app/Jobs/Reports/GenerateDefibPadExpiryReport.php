<?php

declare(strict_types=1);

namespace App\Jobs\Reports;

use App\Models\User;
use App\Models\Defib;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\Reports\DefibPadExpiryMail;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateDefibPadExpiryReport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        $defibs = Defib::query()
            ->where('pads_expire_at', '<', now()->addMonths(1))
            ->orWhereNull('pads_expire_at')
            ->get();

        $users = User::query()
            ->where('receive_reports', '=', true)
            ->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new DefibPadExpiryMail($defibs));
        }
    }
}
