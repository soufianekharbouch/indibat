<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comportement;

class ComportementController extends Controller
{
    public function index()
    {
        $comportements = Comportement::all();
        return view('comportements.index', compact('comportements'));
    }

    public function create()
    {
        return view('comportements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_fr' => 'required|string|max:191',
            'nom_ar' => 'required|string|max:191',
            'points_retires' => 'required|integer|min:1',
            'categorie' => 'required|string'
        ]);

        Comportement::create($request->all());

        return redirect()->route('comportements.index')->with('success', 'Comportement ajouté avec succès.');
    }

    public function edit(Comportement $comportement)
    {
        return view('comportements.edit', compact('comportement'));
    }

    public function update(Request $request, Comportement $comportement)
    {
        $request->validate([
            'nom_fr' => 'required|string|max:191',
            'nom_ar' => 'required|string|max:191',
            'points_retires' => 'required|integer|min:1',
            'categorie' => 'required|string'
        ]);

        $comportement->update($request->all());

        return redirect()->route('comportements.index')->with('success', 'Comportement modifié avec succès.');
    }

    public function destroy(Comportement $comportement)
    {
        $comportement->delete();
        return redirect()->route('comportements.index')->with('success', 'Comportement supprimé avec succès.');
    }
}