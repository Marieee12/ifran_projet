<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeanceCours extends Model
{
    use HasFactory;

    protected $table = 'seances_cours';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_matiere',
        'id_classe',
        'id_enseignant',
        'id_coordinateur',
        'date_seance',
        'heure_debut',
        'heure_fin',
        'type_cours',
        'salle',
        'est_annulee',
        'raison_annulation',
        'id_seance_precedente',
    ];

    protected $casts = [
        'date_seance' => 'date',
        'est_annulee' => 'boolean',
    ];

    public function getHeureDebutAttribute($value)
    {
        if (!$value) return null;
        $time = explode(' ', $value);
        return end($time);
    }

    public function getHeureFinAttribute($value)
    {
        if (!$value) return null;
        $time = explode(' ', $value);
        return end($time);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }


    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'id_matiere');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'id_classe');
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant');
    }

    public function coordinateur()
    {
        return $this->belongsTo(Coordinateur::class, 'id_coordinateur');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class, 'id_seance_cours');
    }

    public function seancePrecedente()
    {
        return $this->belongsTo(SeanceCours::class, 'id_seance_precedente');
    }

    public function seancesSuivantes()
    {
        return $this->hasMany(SeanceCours::class, 'id_seance_precedente');
    }
}
