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

   
    public $timestamps = false; // La table 'roles' n'a pas de timestamps dans notre migration

    // Relation: Un rôle peut être attribué à plusieurs utilisateurs
    public function users()
    {
        return $this->hasMany(User::class, 'role_id'); // 'role_id' est la clé étrangère dans la table 'users'
    }
}
