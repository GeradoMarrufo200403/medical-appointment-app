<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    protected $table = 'specialities';

    protected $fillable = [
        'name',
    ];

    /**
     * Get the doctors associated with this speciality.
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
