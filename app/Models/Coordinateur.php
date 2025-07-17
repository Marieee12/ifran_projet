<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coordinateur extends Model
{
    use HasFactory;

    protected $table = 'coordinateurs';
    protected $primaryKey = 'id';
    public $timestamps = false; // Pas de timestamps pour cette table

    protected $fillable = [
        'user_id',
        'departement',
    ];

    // Relation: Un coordinateur est un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation: Un coordinateur gère plusieurs séances de cours (e-learning/workshop)
    public function seancesCours()
    {
        return $this->hasMany(SeanceCours::class, 'id_coordinateur');
    }

    // Relation: Un coordinateur justifie plusieurs absences
    public function justificationsAbsences()
    {
        return $this->hasMany(JustificationAbsence::class, 'justifiee_par_id_coordinateur');
    }
}
