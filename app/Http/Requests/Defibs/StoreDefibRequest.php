<?php

declare(strict_types=1);

namespace App\Http\Requests\Defibs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Auth\Authenticatable;

class StoreDefibRequest extends FormRequest
{
    public function __construct(private readonly Authenticatable $user)
    {
        parent::__construct();
    }

    public function authorize(): bool
    {
        return $this->user->can('defib.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'location' => ['string', 'required'],
            'coordinates' => ['string', 'nullable'],
            'display_on_map' => ['sometimes', 'boolean'],
            'model' => ['string', 'required'],
            'serial' => ['string', 'nullable'],
            'owner' => ['string', 'required'],
            'last_inspected_by' => ['string', 'nullable'],
            'last_inspected_at' => ['date', 'nullable'],
            'last_serviced_at' => ['date', 'nullable'],
            'pads_expire_at' => ['date', 'nullable'],
            'battery_expires_at' => ['date', 'nullable'],
        ];
    }
}
