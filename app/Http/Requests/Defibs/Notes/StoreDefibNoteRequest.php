<?php

declare(strict_types=1);

namespace App\Http\Requests\Defibs\Notes;

use Illuminate\Foundation\Http\FormRequest;

class StoreDefibNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('defib.note');
    }

    public function rules(): array
    {
        return [
            'note' => ['string', 'required'],
        ];
    }
}
