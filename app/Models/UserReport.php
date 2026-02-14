<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @use HasFactory<\Database\Factories\UserReportFactory>
 */
class UserReport extends Model
{
    /** @use HasFactory<\Database\Factories\UserReportFactory> */
    use HasFactory;
    use HasUser;

    protected $fillable = ['cfr_cert_expiry', 'defib_battery_expiry', 'defib_inspection', 'defib_pad_expiry', 'garda_vetting_expiry', 'defib_inspected'];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
