<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Etudiant extends Model
{
    use HasFactory;

    protected $table = 'etudiants';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'classe_id',
        'niveau_etude_id',
        'date_naissance',
        'adresse',
        'telephone'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function parents()
    {
        return $this->belongsToMany(ParentModel::class, 'parents_etudiants', 'id_etudiant', 'id_parent');
    }

    protected $casts = [
        'date_naissance' => 'date',
    ];
}
