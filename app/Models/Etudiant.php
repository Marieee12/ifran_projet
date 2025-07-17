<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Etudiant extends Model
{
    use HasFactory;

    protected $table = 'etudiants';
    protected $primaryKey = 'id';
    public $timestamps = false; // Pas de timestamps pour cette table

    protected $fillable = [
        'user_id',
        'numero_etudiant',
        'date_naissance',
        'adresse',
        'photo_profil_url',
        'id_classe',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    // Relation: Un étudiant est un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation: Un étudiant appartient à une classe
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'id_classe');
    }

    // Relation: Un étudiant a plusieurs présences
    public function presences()
    {
        return $this->hasMany(Presence::class, 'id_etudiant');
    }

    // Relation N:M: Un étudiant peut avoir plusieurs parents
    public function parents()
    {
        return $this->belongsToMany(ParentModel::class, 'parents_etudiants', 'id_etudiant', 'id_parent');
    }
}
