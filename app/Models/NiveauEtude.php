<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NiveauEtude extends Model
{
    use HasFactory;

    protected $table = 'niveaux_etude';
    protected $primaryKey = 'id';
    public $timestamps = false; // Pas de timestamps pour cette table

    protected $fillable = [
        'nom_niveau',
        'description',
    ];

    // Relation: Un niveau d'étude peut avoir plusieurs classes
    public function classes()
    {
        return $this->hasMany(Classe::class, 'id_niveau_etude');
    }
}
