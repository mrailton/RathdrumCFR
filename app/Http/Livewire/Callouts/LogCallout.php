<?php

declare(strict_types=1);

namespace App\Http\Livewire\Callouts;

use App\Enums\AedSource;
use App\Enums\WasteDisposalMethods;
use App\Models\Callout;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class LogCallout extends Component
{
    use WireToast;

    public Callout $callout;
    public bool $showModal = false;
    public bool $showAdditionalFormFields = false;
    public array $aedSources = [];
    public array $wasteDisposalMethods = [];

    public function mount(): void
    {
        $this->aedSources = AedSource::toArray();
        $this->wasteDisposalMethods = WasteDisposalMethods::toArray();
    }

    public function openModal(): void
    {
        $defaults = [
            'attended' => '',
            'gender' => '',
        ];

        $this->callout = new Callout($defaults);
        $this->showAdditionalFormFields = false;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showAdditionalFormFields = false;
        $this->showModal = false;
    }

    public function save(): void
    {
        $this->validate();

        $this->callout->user_id = auth()->user()->id;
        $this->callout->save();

        $this->reset('showModal');

        toast()->success('Callout Logged')->push();
    }

    public function updatedCalloutAttended(): void
    {
        $this->showAdditionalFormFields = $this->callout->attended === 'Yes';

        if ($this->showAdditionalFormFields) {
            $this->callout->ohca_at_scene = '';
            $this->callout->bystander_cpr = '';
            $this->callout->source_of_aed = '';
            $this->callout->rosc_achieved = '';
            $this->callout->patient_transported = '';
            $this->callout->waste_disposal = '';
        }
    }

    public function render(): View
    {
        return view('livewire.callouts.log-callout');
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
