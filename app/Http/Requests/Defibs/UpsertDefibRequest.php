<?php

declare(strict_types=1);

namespace App\Http\Requests\Defibs;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;

class UpsertDefibRequest extends FormRequest
{
    public function __construct(private readonly Authenticatable $user)
    {
        parent::__construct();
    }

    public function authorize(): bool
    {
        return match ($this->server('REQUEST_METHOD')) {
            'POST' => $this->user->can('defib.create'),
            'PUT' => $this->user->can('defib.update'),
            default => false,
        };
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'location' => ['required', 'string'],
            'coordinates' => ['nullable', 'string'],
            'display_on_map' => ['required', 'boolean'],
            'model' => ['required', 'string'],
            'serial' => ['required', 'string'],
            'owner' => ['required', 'string'],
            'last_inspected_by' => ['nullable', 'string'],
            'last_inspected_at' => ['nullable', 'date'],
            'last_serviced_at' => ['nullable', 'date'],
            'pads_expire_at' => ['nullable', 'date'],
            'battery_expires_at' => ['nullable', 'date'],
        ];
    }
}
