<?php

declare(strict_types=1);

namespace App\Http\Requests\Callouts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCalloutRequest extends FormRequest
{
    public function __construct(private readonly Authenticatable $user)
    {
        parent::__construct();
    }
    public function authorize(): bool
    {
        return $this->user->can('callout.create');
    }

    public function rules(): array
    {
        return [
            'incident_number' => ['required', 'string'],
            'incident_date' => ['required', 'date'],
            'ampds_code' => ['required', 'string'],
            'ohca_at_scene' => ['required', Rule::in(['Yes', 'No', 'Unknown'])],
            'bystander_cpr' => ['required', Rule::in(['Yes', 'No', 'Unknown'])],
            'source_of_aed' => ['required', Rule::in(['PAD', 'CFR', 'NAS', 'Fire', 'Garda', 'Other'])],
            'number_of_shocks_given' => ['required', 'integer'],
            'rosc_achieved' => ['required', Rule::in(['Yes', 'No', 'Unknown'])],
            'patient_transported' => ['required', Rule::in(['Yes', 'No', 'Unknown'])],
            'responders_at_scene' => ['required', 'integer'],
            'ppe_kits_used' => ['required', 'integer'],
            'waste_disposal' => ['required', Rule::in(['NAS Crew', 'DFB Crew', 'NAS Station', 'Hospital', 'Responder', 'Other'])],
            'notes' => ['sometimes', 'string', 'nullable'],
        ];
    }
}
