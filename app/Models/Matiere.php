<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matiere extends Model
{
    use HasFactory;

    protected $table = 'matieres';
    protected $primaryKey = 'id';
    public $timestamps = false; // Pas de timestamps pour cette table

    protected $fillable = [
        'nom_matiere',
        'code_matiere',
        'description',
    ];

    // Relation: Une matière a plusieurs séances de cours
    public function seancesCours()
    {
        return $this->hasMany(SeanceCours::class, 'id_matiere');
    }

    // Relation N:M: Une matière peut être suivie par plusieurs classes
    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'classes_matieres', 'id_matiere', 'id_classe');
    }
}
