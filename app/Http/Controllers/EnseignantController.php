<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enseignant;

class EnseignantController extends Controller
{
    public function index()
    {
        $enseignants = \App\Models\Enseignant::all();
        return view('dashboard.enseignants.index', compact('enseignants'));
    }

    // Méthodes CRUD vides à compléter
    public function create() {}
    public function store(Request $request) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
