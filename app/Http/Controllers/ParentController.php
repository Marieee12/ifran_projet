<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentModel;

class ParentController extends Controller
{
    public function index()
    {
        return view('parents.dashboard');
    }

    public function enfants()
    {
        // Récupérer les enfants du parent connecté
        return view('parents.enfants');
    }

    public function absences()
    {
        // Récupérer les absences des enfants du parent connecté
        return view('parents.absences');
    }

    public function notes()
    {
        // Récupérer les notes des enfants du parent connecté
        return view('parents.notes');
    }

    public function emploiTemps()
    {
        // Récupérer les emplois du temps des enfants du parent connecté
        return view('parents.emploi_temps');
    }
}
