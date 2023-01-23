<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReport extends Model
{
    use HasFactory;

    protected $fillable = ['cfr_cert_expiry', 'defib_battery_expiry', 'defib_inspection', 'defib_pad_expiry', 'garda_vetting_expiry', 'defib_inspected'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
