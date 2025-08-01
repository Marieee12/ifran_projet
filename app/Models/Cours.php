<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;

    protected $table = 'seances_cours';
    public $timestamps = false;

    protected $fillable = [
        'id_classe',
        'id_matiere',
        'id_enseignant',
        'id_coordinateur',
        'date_seance',
        'heure_debut',
        'heure_fin',
        'type_cours',
        'salle',
        'description'
    ];

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'id_classe');
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'id_matiere');
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class, 'id_seance_cours');
    }

    public function absences()
    {
        return $this->hasMany(Absence::class, 'cours_id');
    }
}
