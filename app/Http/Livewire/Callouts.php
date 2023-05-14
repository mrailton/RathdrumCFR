<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Callout;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Callouts extends Component
{
    public function render(): View
    {
        $callouts = Callout::orderBy('incident_date')->paginate(10);

        return view('livewire.callouts', ['callouts' => $callouts]);
    }
}
