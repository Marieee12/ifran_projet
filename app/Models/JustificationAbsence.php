<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JustificationAbsence extends Model
{
    use HasFactory;

    protected $table = 'justifications_absences';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_presence',
        'date_justification',
        'raison_justification',
        'document_justificatif_url',
        'justifiee_par_id_coordinateur',
        'statut',
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

    /**
     * Relation pour accéder à l'étudiant via la présence
     */
    public function etudiant()
    {
        return $this->hasOneThrough(
            Etudiant::class,
            Presence::class,
            'id', // Clé primaire de Presence
            'id', // Clé primaire d'Etudiant
            'id_presence', // Clé étrangère de JustificationAbsence
            'id_etudiant' // Clé étrangère de Presence
        );
    }
}
