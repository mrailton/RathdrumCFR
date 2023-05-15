<?php

declare(strict_types=1);

namespace App\Http\Livewire\Callouts;

use App\Enums\AedSource;
use App\Enums\WasteDisposalMethods;
use App\Models\Callout;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ListCallouts extends Component
{
    use WithPagination;
    use WireToast;

    public Callout $callout;
    public bool $showCreateModal = false;
    public bool $showAdditionalFormFields = false;

    public array $aedSources = [];
    public array $wasteDisposalMethods = [];
    public array $searchColumns = [
        'incident_number' => '',
        'ampds_code' => '',
        'incident_date' => '',
        'attended' => '',
    ];

    public function mount(): void
    {
        $this->aedSources = AedSource::toArray();
        $this->wasteDisposalMethods = WasteDisposalMethods::toArray();
    }

    public function openCreateModal(): void
    {
        $defaults = [
            'attended' => '',
            'gender' => '',
        ];

        $this->callout = new Callout($defaults);
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function saveCallout(): void
    {
        $this->validate();

        $this->callout->user_id = auth()->user()->id;
        $this->callout->save();

        $this->reset('showCreateModal');

        toast()->success('Callout Logged')->push();
    }

    public function updatedCalloutAttended(): void
    {
        $this->showAdditionalFormFields = !$this->showAdditionalFormFields;

        if ($this->showAdditionalFormFields) {
            $this->callout->ohca_at_scene = '';
            $this->callout->bystander_cpr = '';
            $this->callout->source_of_aed = '';
            $this->callout->rosc_achieved = '';
            $this->callout->patient_transported = '';
            $this->callout->waste_disposal = '';
        }
    }

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
                    ->when($column === 'incident_number', fn ($callouts) => $callouts->where('incident_number', 'LIKE', '%' . $value . '%'))
                    ->when($column === 'ampds_code', fn ($callouts) => $callouts->where('ampds_code', 'LIKE', '%' . $value . '%'))
                    ->when($column === 'incident_date', fn ($callouts) => $callouts->where('incident_date', 'LIKE', '%' . $value . '%'))
                    ->when($column === 'attended', fn ($callouts) => $callouts->where('attended', $value));
            }
        }

        $callouts->orderByDesc('incident_date');

        return view('livewire.callouts.list-callouts', ['callouts' => $callouts->paginate(10)]);
    }

    protected function rules(): array
    {
        $rules = [
            'callout.incident_number' => ['required', 'string'],
            'callout.incident_date' => ['required', 'date'],
            'callout.ampds_code' => ['required', 'string'],
            'callout.age' => ['sometimes', 'string', 'nullable'],
            'callout.gender' => ['required', 'string', Rule::in(['Male', 'Female', 'Unknown'])],
            'callout.attended' => ['required', Rule::in(['Yes', 'No'])],
            'callout.notes' => ['sometimes', 'string', 'nullable'],
        ];

        if ($this->callout->attended === 'Yes') {
            $rules = array_merge($rules, [
                'callout.ohca_at_scene' => ['required', Rule::in(['Yes', 'No', 'Unknown'])],
                'callout.bystander_cpr' => ['required', Rule::in(['Yes', 'No', 'Unknown'])],
                'callout.source_of_aed' => ['required', Rule::in($this->aedSources)],
                'callout.number_of_shocks_given' => ['required', 'integer'],
                'callout.rosc_achieved' => ['required', Rule::in(['Yes', 'No', 'N/A', 'Unknown'])],
                'callout.patient_transported' => ['required', Rule::in(['Yes', 'No', 'Unknown'])],
                'callout.responders_at_scene' => ['required', 'integer'],
                'callout.ppe_kits_used' => ['required', 'integer'],
                'callout.waste_disposal' => ['required', Rule::in($this->wasteDisposalMethods)],
            ]);
        }

        return $rules;
    }
}
