<?php

declare(strict_types=1);

namespace App\Http\Requests\Members\Notes;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;

class StoreMemberNoteRequest extends FormRequest
{
    public function __construct(private readonly Authenticatable $user)
    {
        parent::__construct();
    }

    public function authorize(): bool
    {
        return $this->user->can('member.note');
    }

    public function rules(): array
    {
        return [
            'note' => ['string', 'required'],
        ];
    }
}
