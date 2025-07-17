<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'nom_utilisateur',
        'prenom',
        'nom',
        'email',
        'password',
        'telephone',
        'date_creation',
        'derniere_connexion',
        'est_actif',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_creation' => 'datetime',
            'derniere_connexion' => 'datetime',
            'est_actif' => 'boolean',
            ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Relations 1:1 polymorphiques (un utilisateur est un étudiant OU un enseignant OU un coordinateur OU un parent)
    // On utilise hasOne ici car c'est une relation 1:1 où la clé étrangère est dans la table enfant (etudiant, enseignant, etc.)
    public function etudiant()
    {
        return $this->hasOne(Etudiant::class, 'user_id');
    }

    public function enseignant()
    {
        return $this->hasOne(Enseignant::class, 'user_id');
    }

    public function coordinateur()
    {
        return $this->hasOne(Coordinateur::class, 'user_id');
    }

    public function parentModel() // Renommé pour éviter le conflit avec le mot-clé PHP 'parent'
    {
        return $this->hasOne(ParentModel::class, 'user_id');
    }

    // Relation: Un utilisateur peut avoir saisi plusieurs présences
    public function presencesSaisies()
    {
        return $this->hasMany(Presence::class, 'saisi_par_user_id');
    }
}
