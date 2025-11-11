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
        $comportements = Comportement::orderBy('points_retires', 'desc')->get(); // Trier par points
        
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
            'comportements.*' => 'exists:comportements,id',
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
        $pointsRetires = 0.00;
        $comportementsSelectionnes = Comportement::whereIn('id', $request->comportements)->get();
        
        foreach ($comportementsSelectionnes as $comportement) {
            $pointsRetires += floatval($comportement->points_retires);
        }

        // Récupérer les noms français des comportements
        $nomsComportements = $comportementsSelectionnes->pluck('nom_ar')->toArray();

        $rapport = Rapport::create([
            'eleve_id' => $request->eleve_id,
            'prof_id' => auth()->id(),
            'date_seance' => $request->date_seance,
            'heure_seance' => $request->heure_seance,
            'matiere' => $request->matiere,
            'comportements' => $nomsComportements,
            'notes_additionnelles' => $request->notes_additionnelles,
            'points_retires' => $pointsRetires
        ]);

        // Redirection vers la page de confirmation au lieu du dashboard
        return redirect()->route('rapport.confirmation', $rapport->id);
    }

    // Nouvelle méthode pour afficher la page de confirmation
    public function confirmation(Rapport $rapport)
    {

        return view('rapports.confirmation', compact('rapport'));
    }
    public function markSeen($id)
    {
        $rapport = Rapport::findOrFail($id);

        if (auth()->user()->isAdmin() || auth()->user()->isMotasarrif()) {
            $rapport->vu_par_admin = true;
            $rapport->save();
            return back()->with('success', 'تم تأكيد الاطلاع على التقرير من طرف الإدارة');
        }

        abort(403, 'غير مصرح');
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
        $user = auth()->user();
        
        if ($user->isAdmin() || $user->isRoot()) {
            // Pour les admins: afficher tous les rapports avec les relations
            $rapports = Rapport::with(['eleve', 'prof'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Pour les profs: afficher seulement leurs rapports
            $rapports = Rapport::with('eleve')
                ->where('prof_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('rapports.mes-rapports', compact('rapports'));
    }
}