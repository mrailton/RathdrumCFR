<?php

declare(strict_types=1);

namespace App\Http\Requests\Defibs\Inspections;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;

class StoreDefibInspectionRequest extends FormRequest
{
    public function __construct(private readonly Authenticatable $user)
    {
        parent::__construct();
    }

    public function authorize(): bool
    {
        return $this->user->can('defib.inspect');
    }

    public function rules(): array
    {
        return [
            'member_id' => ['required', 'integer', 'exists:members,id'],
            'inspected_at' => ['required', 'date'],
            'pads_expire_at' => ['required', 'date'],
            'battery_expires_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
