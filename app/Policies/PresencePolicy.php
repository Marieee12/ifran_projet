<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Presence;
use Illuminate\Auth\Access\HandlesAuthorization;

class PresencePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Presence $presence): bool
    {
        $roleName = $user->role->nom_role;

        switch ($roleName) {
            case 'Administrateur':
                return true;

            case 'Coordinateur Pédagogique':
            case 'Enseignant':
                return true;

            case 'Étudiant':
                // L'étudiant peut voir ses propres présences
                return $presence->id_etudiant === $user->etudiant?->id;

            case 'Parent':
                // Le parent peut voir les présences de ses enfants
                $parent = $user->parent;
                if (!$parent) return false;

                return $parent->etudiants()->where('id', $presence->id_etudiant)->exists();

            default:
                return false;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $roleName = $user->role->nom_role;
        return in_array($roleName, ['Administrateur', 'Coordinateur Pédagogique', 'Enseignant']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Presence $presence): bool
    {
        $roleName = $user->role->nom_role;

        if ($roleName === 'Administrateur') {
            return true;
        }

        $seanceCours = $presence->seanceCours;

        if ($roleName === 'Coordinateur Pédagogique') {
            return in_array($seanceCours->type_cours, ['E-learning', 'Workshop']);
        }

        if ($roleName === 'Enseignant') {
            $enseignant = $user->enseignant;
            return $seanceCours->type_cours === 'Presentiel' &&
                   $seanceCours->id_enseignant === $enseignant?->id &&
                   $this->isWithinTimeLimit($seanceCours);
        }

        return false;
    }

    /**
     * Determine whether the user can justify an absence.
     */
    public function justify(User $user, Presence $presence): bool
    {
        $roleName = $user->role->nom_role;
        return in_array($roleName, ['Administrateur', 'Coordinateur Pédagogique']);
    }

    /**
     * Check if the presence is within the 2-week time limit for teachers.
     */
    private function isWithinTimeLimit($seanceCours): bool
    {
        $deadline = \Carbon\Carbon::parse($seanceCours->date_seance . ' ' . $seanceCours->heure_debut)
            ->addWeeks(2);

        return now()->lte($deadline);
    }
}
