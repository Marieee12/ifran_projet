<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    protected $table = 'presences';

    protected $fillable = [
        'cours_id',
        'etudiant_id',
        'present',
        'justifie',
        'date_absence'
    ];

    protected $casts = [
        'present' => 'boolean',
        'justifie' => 'boolean',
        'date_absence' => 'date'
    ];

    public function cours()
    {
        return $this->belongsTo(Cours::class, 'cours_id');
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'etudiant_id');
    }

    public function justification()
    {
        return $this->hasOne(JustificationAbsence::class, 'absence_id');
    }
}
