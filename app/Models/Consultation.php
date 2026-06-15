<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{
    protected $fillable = [
        'appointment_id',
        'diagnosis',
        'treatment',
        'notes',
        'medicines',
    ];

    protected $casts = [
        'medicines' => 'array',
    ];

    /**
     * Get the appointment associated with this consultation.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
}
