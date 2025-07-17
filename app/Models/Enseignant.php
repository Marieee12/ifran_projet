<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enseignant extends Model
{
    use HasFactory;

    protected $table = 'enseignants';
    protected $primaryKey = 'id';
    public $timestamps = false; // Pas de timestamps pour cette table

    protected $fillable = [
        'user_id',
        'specialite',
    ];

    // Relation: Un enseignant est un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation: Un enseignant donne plusieurs séances de cours
    public function seancesCours()
    {
        return $this->hasMany(SeanceCours::class, 'id_enseignant');
    }
}
