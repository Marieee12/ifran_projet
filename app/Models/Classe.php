<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classe extends Model
{
    use HasFactory;

    protected $table = 'classes';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_annee_academique',
        'id_niveau_etude',
        'id_filiere',
        'nom_classe_complet',
    ];
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'id_annee_academique');
    }
    public function niveauEtude()
    {
        return $this->belongsTo(NiveauEtude::class, 'id_niveau_etude');
    }
    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'id_filiere');
    }

    public function etudiants()
    {
        return $this->hasMany(Etudiant::class, 'classe_id');
    }

    public function seancesCours()
    {
        return $this->hasMany(SeanceCours::class, 'id_classe');
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'classes_matieres', 'id_classe', 'id_matiere');
    }
}
