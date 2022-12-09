<?php

declare(strict_types=1);

namespace App\Http\Requests\Members;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMemberRequest extends FormRequest
{
    public function __construct(private readonly Authenticatable $user)
    {
        parent::__construct();
    }

    public function authorize(): bool
    {
        return $this->user->can('member.create');
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
            'status' => ['required', 'string', Rule::in(['inactive', 'active'])],
        ];
    }
}
