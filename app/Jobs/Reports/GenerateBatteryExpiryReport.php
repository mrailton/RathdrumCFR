<?php

declare(strict_types=1);

namespace App\Jobs\Reports;

use App\Mail\Reports\BatteryExpiryMail;
use App\Models\Defib;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

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
        $defibs = $this->getDefibs();
        $users = $this->getUsers();

        foreach ($users as $user) {
            Mail::to($user->email)->queue(new BatteryExpiryMail($defibs));
        }
    }

    public function getDefibs(): Collection
    {
        return Defib::query()
            ->where('battery_expires_at', '<', now()->addMonths(1))
            ->orWhereNull('battery_expires_at')
            ->get();
    }

    public function getUsers(): Collection
    {
        return User::query()
            ->where('receive_reports', '=', true)
            ->get();
    }
}
