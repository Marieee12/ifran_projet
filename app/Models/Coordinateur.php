<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coordinateur extends Model
{
    use HasFactory;

    protected $table = 'coordinateurs';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_utilisateur',
        'departement',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function seancesCours()
    {
        return $this->hasMany(SeanceCours::class, 'id_coordinateur');
    }

    public function justificationsAbsences()
    {
        return $this->hasMany(JustificationAbsence::class, 'justifiee_par_id_coordinateur');
    }
}
