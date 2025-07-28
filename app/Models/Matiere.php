<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matiere extends Model
{
    use HasFactory;

    protected $table = 'matieres';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nom_matiere',
        'code_matiere',
        'description',
    ];

    public function seancesCours()
    {
        return $this->hasMany(SeanceCours::class, 'id_matiere');
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'classes_matieres', 'id_matiere', 'id_classe');
    }
}
