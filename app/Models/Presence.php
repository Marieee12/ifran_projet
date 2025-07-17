<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presence extends Model
{
    use HasFactory;

    protected $table = 'presences';
    protected $primaryKey = 'id';
    public $timestamps = false; // Pas de timestamps pour cette table

    protected $fillable = [
        'id_seance_cours',
        'id_etudiant',
        'statut_presence',
        'date_saisie',
        'saisi_par_user_id',
    ];

    protected $casts = [
        'date_saisie' => 'datetime',
    ];

    // Relation: Une présence appartient à une séance de cours
    public function seanceCours()
    {
        return $this->belongsTo(SeanceCours::class, 'id_seance_cours');
    }

    // Relation: Une présence est pour un étudiant
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'id_etudiant');
    }

    // Relation: Une présence a été saisie par un utilisateur (enseignant ou coordinateur)
    public function saisieParUser()
    {
        return $this->belongsTo(User::class, 'saisi_par_user_id');
    }

    // Relation 0:1: Une présence (si absente) peut avoir une justification
    public function justificationAbsence()
    {
        return $this->hasOne(JustificationAbsence::class, 'id_presence');
    }
}
