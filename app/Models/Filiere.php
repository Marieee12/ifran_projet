<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Filiere extends Model
{
    use HasFactory;

    protected $table = 'filieres';
    protected $primaryKey = 'id';
    public $timestamps = false; // Pas de timestamps pour cette table

    protected $fillable = [
        'nom_filiere',
        'description',
    ];

    // Relation: Une filière peut avoir plusieurs classes
    public function classes()
    {
        return $this->hasMany(Classe::class, 'id_filiere');
    }
}
