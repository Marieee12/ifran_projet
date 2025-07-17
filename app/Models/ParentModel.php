<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parents'; // Nom de la table dans la base de données
    protected $primaryKey = 'id';
    public $timestamps = false; // Pas de timestamps pour cette table

    protected $fillable = [
        'user_id',
        'telephone',
        'lien_avec_etudiant',
    ];

    // Relation: Un parent est un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation N:M: Un parent peut avoir plusieurs étudiants (enfants)
    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'parents_etudiants', 'id_parent', 'id_etudiant');
    }
}
