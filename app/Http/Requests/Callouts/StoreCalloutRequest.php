<?php

declare(strict_types=1);

namespace App\Http\Requests\Callouts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCalloutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('callout.create');
    }

    public function rules(): array
    {
        $rules = [
            'incident_number' => ['required', 'string'],
            'incident_date' => ['required', 'date'],
            'ampds_code' => ['required', 'string'],
            'attended' => ['required', Rule::in(['Yes', 'No'])],
            'notes' => ['sometimes', 'string', 'nullable'],
            'gender' => ['required', 'string', Rule::in(['Male', 'Female', 'Unknown'])],
            'age' => ['sometimes', 'string', 'nullable'],
        ];

        if ($this->get('attended') === 'Yes') {
            $rules = array_merge($rules, [
                'ohca_at_scene' => ['required', Rule::in(['Yes', 'No', 'Unknown'])],
                'bystander_cpr' => ['required', Rule::in(['Yes', 'No', 'Unknown'])],
                'source_of_aed' => ['required', Rule::in(['PAD', 'CFR', 'NAS', 'Fire', 'Garda', 'Other'])],
                'number_of_shocks_given' => ['required', 'integer'],
                'rosc_achieved' => ['required', Rule::in(['Yes', 'No', 'Unknown'])],
                'patient_transported' => ['required', Rule::in(['Yes', 'No', 'Unknown'])],
                'responders_at_scene' => ['required', 'integer'],
                'ppe_kits_used' => ['required', 'integer'],
                'waste_disposal' => ['required', Rule::in(['NAS Crew', 'DFB Crew', 'NAS Station', 'Hospital', 'Responder', 'Other'])],
            ]);
        }

        return $rules;
    }
}
