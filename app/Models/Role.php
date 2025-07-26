<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nom_role',
    ];

    // SUPPRIMER OU COMMENTER LA LIGNE SUIVANTE :
    // public $timestamps = false; // <-- Cette ligne doit être supprimée ou commentée

    // Relation: Un rôle peut être attribué à plusieurs utilisateurs
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
