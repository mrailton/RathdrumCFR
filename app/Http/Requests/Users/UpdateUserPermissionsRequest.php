<?php

declare(strict_types=1);

namespace App\Http\Requests\Users;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPermissionsRequest extends FormRequest
{
    public function __construct(private readonly Authenticatable $user)
    {
        parent::__construct();
    }

    public function authorize(): bool
    {
        return $this->user->can('user.permissions');
    }

    public function rules(): array
    {
        return [
            'permissions' => ['required', 'array'],
        ];
    }
}
