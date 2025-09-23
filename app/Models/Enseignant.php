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
        'user_id',
        'specialite',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seancesCours()
    {
        return $this->hasMany(SeanceCours::class, 'id_enseignant');
    }
}
