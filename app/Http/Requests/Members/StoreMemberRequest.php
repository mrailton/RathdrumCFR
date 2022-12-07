<?php

declare(strict_types=1);

namespace App\Http\Requests\Members;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('member.create');
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
            'status' => ['nullable', 'string', Rule::in(['inactive', 'active'])],
        ];
    }
}
