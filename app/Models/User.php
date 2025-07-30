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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

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

    public function etudiant()
    {
        return $this->hasOne(Etudiant::class, 'user_id');
    }

    public function enseignant()
    {
        return $this->hasOne(Enseignant::class, 'id_utilisateur');
    }

    public function coordinateur()
    {
        return $this->hasOne(Coordinateur::class, 'id_utilisateur');
    }

    public function parent()
    {
        return $this->hasOne(ParentModel::class, 'id_utilisateur');
    }

    public function presencesSaisies()
    {
        return $this->hasMany(Presence::class, 'saisi_par_id_utilisateur');
    }

    public function presencesModifiees()
    {
        return $this->hasMany(Presence::class, 'modifie_par_id_utilisateur');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->nom_role === $roleName;
    }

    /**
     * Vérifier si l'utilisateur peut gérer les présences
     */
    public function canManagePresences(): bool
    {
        return $this->hasRole('Coordinateur Pédagogique') ||
               $this->hasRole('Enseignant') ||
               $this->hasRole('Administrateur');
    }
}
