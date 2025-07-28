<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JustificationAbsence extends Model
{
    use HasFactory;

    protected $table = 'justifications_absences';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_presence',
        'date_justification',
        'raison_justification',
        'document_justificatif_url',
        'justifiee_par_id_coordinateur',
    ];

    protected $casts = [
        'date_justification' => 'datetime',
    ];

    public function presence()
    {
        return $this->belongsTo(Presence::class, 'id_presence');
    }

    public function justifieeParCoordinateur()
    {
        return $this->belongsTo(Coordinateur::class, 'justifiee_par_id_coordinateur');
    }
}
