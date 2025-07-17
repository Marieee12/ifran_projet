<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeanceCours extends Model
{
    use HasFactory;

    protected $table = 'seances_cours';
    protected $primaryKey = 'id';
    public $timestamps = false; // Pas de timestamps pour cette table

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
        'date_report',
        'heure_report_debut',
        'heure_report_fin',
        'raison_annulation_report',
    ];

    protected $casts = [
        'date_seance' => 'date',
        'date_report' => 'date',
        'est_annulee' => 'boolean',
    ];

    // Relation: Une séance de cours appartient à une matière
    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'id_matiere');
    }

    // Relation: Une séance de cours appartient à une classe
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'id_classe');
    }

    // Relation: Une séance de cours est donnée par un enseignant (peut être null)
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant');
    }

    // Relation: Une séance de cours est gérée par un coordinateur (peut être null)
    public function coordinateur()
    {
        return $this->belongsTo(Coordinateur::class, 'id_coordinateur');
    }

    // Relation: Une séance de cours a plusieurs présences
    public function presences()
    {
        return $this->hasMany(Presence::class, 'id_seance_cours');
    }
}
