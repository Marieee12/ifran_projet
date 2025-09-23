<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parents';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'telephone',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'parents_etudiants', 'id_parent', 'id_etudiant');
    }
}
