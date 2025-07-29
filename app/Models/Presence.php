<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presence extends Model
{
    use HasFactory;

    protected $table = 'presences';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_seance_cours',
        'id_etudiant',
        'statut_presence',
        'date_saisie',
        'saisi_par_id_utilisateur',
        'saisie_dans_delai',
        'derniere_modification',
        'modifie_par_id_utilisateur',
    ];

    protected $casts = [
        'date_saisie' => 'datetime',
        'saisie_dans_delai' => 'boolean',
        'derniere_modification' => 'datetime',
    ];

    public function seanceCours()
    {
        return $this->belongsTo(SeanceCours::class, 'id_seance_cours');
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'id_etudiant');
    }

    public function saisieParUser()
    {
        return $this->belongsTo(User::class, 'saisi_par_id_utilisateur');
    }

    public function modifieParUser()
    {
        return $this->belongsTo(User::class, 'modifie_par_id_utilisateur');
    }

    public function justificationAbsence()
    {
        return $this->hasOne(JustificationAbsence::class)->where('id_etudiant', $this->id_etudiant)
                    ->where('id_seance_cours', $this->id_seance_cours);
    }
}
