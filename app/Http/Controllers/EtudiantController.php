<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etudiant;

class EtudiantController extends Controller
{
    public function index()
    {
        $etudiants = Etudiant::with(['user', 'classe'])->get();
        return view('dashboard.etudiants.index', compact('etudiants'));
    }


}
