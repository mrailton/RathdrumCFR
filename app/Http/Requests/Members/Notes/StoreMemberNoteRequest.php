<?php

declare(strict_types=1);

namespace App\Http\Requests\Members\Notes;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('member.note');
    }

    public function rules(): array
    {
        return [
            'note' => ['required', 'string'],
        ];
    }
}
