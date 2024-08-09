<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Defib extends Model
{
    use HasFactory;
    use HasUser;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'coordinates',
        'display_on_map',
        'model',
        'serial',
        'owner',
        'last_inspected_at',
        'last_inspected_by',
        'last_serviced_at',
        'pads_expire_at',
        'battery_expires_at',
        'defib_lot_number',
        'defib_manufacture_date',
        'battery_lot_number',
        'battery_manufacture_date',
        'pads_lot_number',
        'pads_manufacture_date',
    ];

    protected function casts(): array
    {
        return [
            'last_inspected_at' => 'datetime:Y-m-d',
            'pads_expire_at' => 'datetime:Y-m-d',
            'last_serviced_at' => 'datetime:Y-m-d',
            'display_on_map' => 'boolean',
            'serial' => 'string',
            'battery_expires_at' => 'datetime:Y-m-d',
            'defib_manufacture_date' => 'datetime:Y-m-d',
            'battery_manufacture_date' => 'datetime:Y-m-d',
            'pads_manufacture_date' => 'datetime:Y-m-d',
        ];
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(DefibInspection::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(DefibNote::class);
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('display_on_map', true);
    }
}
