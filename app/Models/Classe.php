<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classe extends Model
{
    use HasFactory;

    protected $table = 'classes';
    protected $primaryKey = 'id';
    public $timestamps = false; // Pas de timestamps pour cette table

    protected $fillable = [
        'id_annee_academique',
        'id_niveau_etude',
        'id_filiere',
        'nom_classe_complet',
    ];

    // Relation: Une classe appartient à une année académique
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'id_annee_academique');
    }

    // Relation: Une classe appartient à un niveau d'étude
    public function niveauEtude()
    {
        return $this->belongsTo(NiveauEtude::class, 'id_niveau_etude');
    }

    // Relation: Une classe appartient à une filière
    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'id_filiere');
    }

    // Relation: Une classe a plusieurs étudiants
    public function etudiants()
    {
        return $this->hasMany(Etudiant::class, 'id_classe');
    }

    // Relation: Une classe a plusieurs séances de cours
    public function seancesCours()
    {
        return $this->hasMany(SeanceCours::class, 'id_classe');
    }

    // Relation N:M: Une classe peut avoir plusieurs matières
    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'classes_matieres', 'id_classe', 'id_matiere');
    }
}
