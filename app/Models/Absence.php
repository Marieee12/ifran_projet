<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    protected $table = 'presences';

    protected $fillable = [
        'id_seance_cours',
        'id_etudiant',
        'statut_presence',
        'date_saisie',
        'saisi_par_id_utilisateur'
    ];

    protected $casts = [
        'date_saisie' => 'datetime'
    ];

    // Scope pour récupérer seulement les absences (statut_presence = 'Absent')
    public function scopeAbsent($query)
    {
        return $query->where('statut_presence', 'Absent');
    }

    public function seanceCours()
    {
        return $this->belongsTo(SeanceCours::class, 'id_seance_cours');
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'id_etudiant');
    }

    public function justification()
    {
        return $this->hasOne(JustificationAbsence::class, 'id_absence');
    }

    public function saisieParUtilisateur()
    {
        return $this->belongsTo(User::class, 'saisi_par_id_utilisateur');
    }
}
