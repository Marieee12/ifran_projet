<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeanceCours extends Model
{
    use HasFactory;

    protected $table = 'seances_cours';
    protected $primaryKey = 'id';
    public $timestamps = false; // Si vous n'avez pas ajouté les timestamps dans la migration

    protected $fillable = [
        'id_matiere',
        'id_classe',
        'id_enseignant',
        'id_coordinateur',
        'date_seance',
        'heure_debut',
        'heure_fin',
        'type_cours',
        'salle',
        'est_annulee',
        'raison_annulation', // Ajouté
        'id_seance_precedente', // Ajouté
    ];

    protected $casts = [
        'date_seance' => 'date',
        'est_annulee' => 'boolean',
    ];

    // Relations existantes...
    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'id_matiere');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'id_classe');
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant');
    }

    public function coordinateur()
    {
        return $this->belongsTo(Coordinateur::class, 'id_coordinateur');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class, 'id_seance_cours');
    }

    // Nouvelle relation auto-référencée : La séance qu'elle remplace
    public function seancePrecedente()
    {
        return $this->belongsTo(SeanceCours::class, 'id_seance_precedente');
    }

    // Optionnel : La ou les séances qui ont remplacé celle-ci (si elle est l'originale annulée)
    public function seancesSuivantes()
    {
        return $this->hasMany(SeanceCours::class, 'id_seance_precedente');
    }
}
