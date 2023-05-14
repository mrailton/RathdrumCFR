<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Callout;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Callouts extends Component
{
    use WithPagination;

    public array $searchColumns = [
        'incident_number' => '',
        'ampds_code' => '',
        'incident_date' => '',
        'attended' => '',
    ];

    public function updatingSearchColumns(): void
    {
        $this->resetPage();
    }

    public function paginationView(): string
    {
        return 'livewire.pagination.tailwind';
    }

    public function render(): View
    {
        $callouts = Callout::query();

        foreach ($this->searchColumns as $column => $value) {
            if (!empty($value)) {
                $callouts
                    ->when($column == 'incident_number', fn($callouts) => $callouts->where('incident_number', 'LIKE', '%' . $value . '%'))
                    ->when($column == 'ampds_code', fn($callouts) => $callouts->where('ampds_code', 'LIKE', '%' . $value . '%'))
                    ->when($column == 'incident_date', fn($callouts) => $callouts->where('incident_date', 'LIKE', '%' . $value . '%'))
                    ->when($column == 'attended', fn($callouts) => $callouts->where('attended', $value));
            }
        }

        return view('livewire.callouts', ['callouts' => $callouts->paginate(10)]);
    }
}
