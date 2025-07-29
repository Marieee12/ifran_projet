<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enseignant extends Model
{
    use HasFactory;

    protected $table = 'enseignants';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_utilisateur',
        'specialite',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function seancesCours()
    {
        return $this->hasMany(SeanceCours::class, 'id_enseignant');
    }
}
