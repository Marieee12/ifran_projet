<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presence extends Model
{
    use HasFactory;

    protected $table = 'presences';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_seance_cours',
        'id_etudiant',
        'statut_presence',
        'date_saisie',
        'saisi_par_user_id',
    ];

    protected $casts = [
        'date_saisie' => 'datetime',
    ];

    public function seanceCours()
    {
        return $this->belongsTo(SeanceCours::class, 'id_seance_cours');
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'id_etudiant');
    }

    public function saisieParUser()
    {
        return $this->belongsTo(User::class, 'saisi_par_user_id');
    }

    public function justificationAbsence()
    {
        return $this->hasOne(JustificationAbsence::class, 'id_presence');
    }
}
