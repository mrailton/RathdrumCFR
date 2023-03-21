<?php

declare(strict_types=1);

namespace App\Http\Requests\Defibs\Inspections;

use App\Enums\BatteryCondition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreDefibInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('defib.inspect');
    }

    public function rules(): array
    {
        return [
            'member_id' => ['required', 'integer', 'exists:members,id'],
            'inspected_at' => ['required', 'date'],
            'pads_expire_at' => ['required', 'date'],
            'battery_expires_at' => ['required', 'date'],
            'battery_condition' => ['required', new Enum(BatteryCondition::class)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
