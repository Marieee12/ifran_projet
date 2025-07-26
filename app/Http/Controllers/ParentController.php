<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentModel;

class ParentController extends Controller
{
    public function index()
    {
        $parents = ParentModel::all();
        return view('dashboard.parents.index', compact('parents'));
    }

    // Méthodes CRUD vides à compléter
    public function create() {}
    public function store(Request $request) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
