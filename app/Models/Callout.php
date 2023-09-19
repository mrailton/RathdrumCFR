<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Callout extends Model
{
    use HasFactory;
    use HasUser;

    protected $fillable = [
        'incident_number', 'incident_date', 'ampds_code', 'ohca_at_scene', 'bystander_cpr', 'source_of_aed', 'number_of_shocks_given', 'rosc_achieved', 'patient_transported', 'responders_at_scene', 'ppe_kits_used', 'waste_disposal', 'notes', 'attended', 'age', 'gender', 'mobilised', 'medical_facility',
    ];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class);
    }
}
