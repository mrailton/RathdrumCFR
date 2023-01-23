<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Invite;
use Closure;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class NoPendingInvites implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     *
     * @return void
     */
    public function __invoke($attribute, $value, $fail): void
    {
        $existing = Invite::query()->where('email', '=', $value)->where('expires_at', '>', now())->first();

        if ($existing) {
            $fail('This email address is used in a pending invite');
        }
    }
}
