<?php

declare(strict_types=1);

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequestedReportsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('user.update');
    }

    public function rules(): array
    {
        return [
            'cfr_cert_expiry' => ['required' => Rule::in('Yes', 'No')],
            'defib_battery_expiry' => ['required' => Rule::in('Yes', 'No')],
            'defib_inspection' => ['required' => Rule::in('Yes', 'No')],
            'defib_pad_expiry' => ['required' => Rule::in('Yes', 'No')],
            'garda_vetting_expiry' => ['required' => Rule::in('Yes', 'No')],
            'defib_inspected' => ['required' => Rule::in('Yes', 'No')],
        ];
    }
}
