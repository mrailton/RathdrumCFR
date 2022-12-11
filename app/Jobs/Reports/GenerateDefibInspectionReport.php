<?php

declare(strict_types=1);

namespace App\Jobs\Reports;

use App\Mail\Reports\DefibInspectionMail;
use App\Models\Defib;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class GenerateDefibInspectionReport implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;
    use InteractsWithQueue;

    public function __construct()
    {
    }

    public function handle(): void
    {
        $defibs = $this->getDefibs();
        $users = $this->getUsers();

        foreach ($users as $user) {
            Mail::to($user->email)->queue(new DefibInspectionMail($defibs));
        }
    }

    public function getDefibs(): Collection
    {
        return Defib::query()
            ->where('last_inspected_at', '<', now()->subMonths(1))
            ->orWhereNull('last_inspected_at')
            ->get();
    }

    public function getUsers(): Collection
    {
        return User::query()
            ->where('receive_reports', '=', true)
            ->get();
    }
}
