<?php

declare(strict_types=1);

namespace App\Http\Requests\Members;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('member.update');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'address_1' => ['required', 'string'],
            'address_2' => ['required', 'string'],
            'eircode' => ['nullable', 'string'],
            'title' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            'cfr_certificate_number' => ['nullable', 'string'],
            'cfr_certificate_expiry' => ['nullable', 'date'],
            'volunteer_declaration' => ['nullable', 'date'],
            'garda_vetting_id' => ['nullable', 'string'],
            'garda_vetting_date' => ['nullable', 'date'],
            'cism_completed' => ['nullable', 'date'],
            'child_first_completed' => ['nullable', 'date'],
            'ppe_community_completed' => ['nullable', 'date'],
            'ppe_acute_completed' => ['nullable', 'date'],
            'hand_hygiene_completed' => ['nullable', 'date'],
            'hiqa_completed' => ['nullable', 'date'],
            'covid_return_completed' => ['nullable', 'date'],
            'ppe_assessment_completed' => ['nullable', 'date'],
        ];
    }
}
