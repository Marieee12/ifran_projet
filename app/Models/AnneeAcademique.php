<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnneeAcademique extends Model
{
    use HasFactory;

    protected $table = 'annees_academiques';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nom_annee',
        'date_debut',
        'date_fin',
        'est_actuelle',
    ];

    public function classes()
    {
        return $this->hasMany(Classe::class, 'id_annee_academique');
    }
}
