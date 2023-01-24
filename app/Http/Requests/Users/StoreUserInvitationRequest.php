<?php

declare(strict_types=1);

namespace App\Http\Requests\Users;

use App\Rules\NoPendingInvites;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('user.invite');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users', new NoPendingInvites()],
        ];
    }
}
