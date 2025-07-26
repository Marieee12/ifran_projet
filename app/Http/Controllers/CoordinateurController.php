<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoordinateurController extends Controller
{
    public function index()
    {
        return view('coordinateur.index');
    }

    public function creerCours()
    {
        return view('coordinateur.creer_cours');
    }

    public function justifications()
    {
        return view('coordinateur.justifications');
    }

    public function absences()
    {
        return view('coordinateur.absences');
    }

    public function classes()
    {
        return view('coordinateur.classes');
    }

    public function emploiTemps()
    {
        return view('coordinateur.emploi_temps');
    }
}
