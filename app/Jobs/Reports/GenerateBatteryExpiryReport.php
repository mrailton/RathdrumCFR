<?php

declare(strict_types=1);

namespace App\Jobs\Reports;

use App\Models\User;
use App\Models\Defib;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Mail\Reports\BatteryExpiryMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateBatteryExpiryReport implements ShouldQueue
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
            ->where('battery_expires_at', '<', now()->addMonths(1))
            ->orWhereNull('battery_expires_at')
            ->get();

        $users = User::query()
            ->where('receive_reports', '=', true)
            ->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new BatteryExpiryMail($defibs));
        }
    }
}
