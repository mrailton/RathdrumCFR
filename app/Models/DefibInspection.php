<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BatteryCondition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefibInspection extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['member_id', 'inspected_at', 'pads_expire_at', 'battery_expires_at', 'battery_condition', 'notes'];

    protected $casts = [
        'inspected_at' => 'date:Y-m-d',
        'pads_expire_at' => 'date:Y-m-d',
        'battery_expires_at' => 'date:Y-m-d',
        'battery_condition' => BatteryCondition::class,
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
