<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Filiere extends Model
{
    use HasFactory;

    protected $table = 'filieres';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nom_filiere',
        'description',
    ];

    // Une filière peut avoir plusieurs classes
    public function classes()
    {
        return $this->hasMany(Classe::class, 'id_filiere');
    }
}
