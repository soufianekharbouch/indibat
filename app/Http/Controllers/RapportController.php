<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rapport;
use App\Models\Eleve;
use App\Models\Comportement;
use Carbon\Carbon;

class RapportController extends Controller
{
    public function create($eleveId)
    {
        $eleve = Eleve::findOrFail($eleveId);
        $comportements = Comportement::all();
        
        // Vérifier les contraintes
        $constraints = Rapport::checkConstraints($eleveId, auth()->id());
        
        if (!$constraints['can_create']) {
            $nextAllowedDate = Rapport::getNextAllowedDate($eleveId, auth()->id());
            $daysRemaining = Rapport::getDaysRemaining($eleveId, auth()->id());
            
            return view('rapports.blocked', compact('eleve', 'constraints', 'nextAllowedDate', 'daysRemaining'));
        }

        return view('rapports.create', compact('eleve', 'comportements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
            'date_seance' => 'required|date',
            'heure_seance' => 'required',
            'matiere' => 'required|string',
            'comportements' => 'required|array',
            'notes_additionnelles' => 'nullable|string'
        ]);

        // Vérifier à nouveau les contraintes avant de créer
        $constraints = Rapport::checkConstraints($request->eleve_id, auth()->id());
        
        if (!$constraints['can_create']) {
            return redirect()->back()
                ->with('error', 'لا يمكن إنشاء التقرير بسبب القيود المفروضة.')
                ->withInput();
        }

        // Calculer les points retirés basés sur les comportements sélectionnés
        $pointsRetires = 0;
        $comportementsSelectionnes = Comportement::whereIn('id', $request->comportements)->get();
        
        foreach ($comportementsSelectionnes as $comportement) {
            $pointsRetires += $comportement->points_retires;
        }

        // Récupérer les noms français des comportements
        $nomsComportements = $comportementsSelectionnes->pluck('nom_fr')->toArray();

        Rapport::create([
            'eleve_id' => $request->eleve_id,
            'prof_id' => auth()->id(),
            'date_seance' => $request->date_seance,
            'heure_seance' => $request->heure_seance,
            'matiere' => $request->matiere,
            'comportements' => $nomsComportements,
            'notes_additionnelles' => $request->notes_additionnelles,
            'points_retires' => $pointsRetires
        ]);

        return redirect()->route('dashboard')->with('success', 'تم إرسال التقرير بنجاح.');
    }

    // Nouvelle méthode pour vérifier les contraintes via AJAX
    public function checkConstraints(Request $request)
    {
        $eleveId = $request->get('eleve_id');
        $profId = auth()->id();

        $constraints = Rapport::checkConstraints($eleveId, $profId);
        $nextAllowedDate = Rapport::getNextAllowedDate($eleveId, $profId);
        $daysRemaining = Rapport::getDaysRemaining($eleveId, $profId);

        return response()->json([
            'can_create' => $constraints['can_create'],
            'constraints' => $constraints,
            'next_allowed_date' => $nextAllowedDate,
            'days_remaining' => $daysRemaining
        ]);
    }
    public function mesRapports()
        {
            $rapports = Rapport::with('eleve')
                ->where('prof_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();

            return view('rapports.mes-rapports', compact('rapports'));
        }
}