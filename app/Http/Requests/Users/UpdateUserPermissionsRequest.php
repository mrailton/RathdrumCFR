<?php

declare(strict_types=1);

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('user.permissions');
    }

    public function rules(): array
    {
        return [
            'permissions' => ['required', 'array'],
        ];
    }
}
