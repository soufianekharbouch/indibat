<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Rapport;
use App\Models\User;
use App\Models\Conseil;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isRoot() || $user->isAdmin() || $user->isMotasarrif()) {
            // Pour les administrateurs, trier par score (calculé dynamiquement)
            $eleves = Eleve::with(['rapports', 'conseils'])->get()->map(function($eleve) {
                $pointsRetires = $eleve->rapports->sum('points_retires');
                $eleve->score_calcule = max(0, 100 - $pointsRetires);
                $eleve->rapports_count = $eleve->rapports->count();
                $eleve->conseils_count = $eleve->conseils->count();
                $eleve->conseils_ouverts_count = $eleve->conseils->where('statut', 'ouvert')->count();
                $eleve->conseil_ouvert = $eleve->conseils->where('statut', 'ouvert')->first();
                return $eleve;
            })->sortBy('score_calcule');
        } else {
            $eleves = Eleve::with(['rapports', 'conseils'])->get()->map(function($eleve) {
                $pointsRetires = $eleve->rapports->sum('points_retires');
                $eleve->score_calcule = max(0, 100 - $pointsRetires);
                $eleve->rapports_count = $eleve->rapports->count();
                $eleve->conseils_count = $eleve->conseils->count();
                $eleve->conseils_ouverts_count = $eleve->conseils->where('statut', 'ouvert')->count();
                return $eleve;
            });
        }

        // Compter les conseils en attente pour les profs
        $conseilsEnAttente = 0;
        if ($user->isProf()) {
            $conseilsEnAttente = Conseil::whereHas('profs', function($query) use ($user) {
                $query->where('prof_id', $user->id)->where('a_repondu', false);
            })->where('statut', 'ouvert')->count();
        }

        return view('dashboard', compact('eleves', 'conseilsEnAttente'));
    }

    public function searchEleves(Request $request)
    {
        $search = $request->get('search');
        
        $eleves = Eleve::with(['rapports', 'conseils'])
            ->where('nom_ar', 'LIKE', "%{$search}%")
            ->orWhere('prenom_ar', 'LIKE', "%{$search}%")
            ->orWhere('code_massar', 'LIKE', "%{$search}%")
            ->orWhere('classe', 'LIKE', "%{$search}%")
            ->get()
            ->map(function($eleve) {
                $pointsRetires = $eleve->rapports->sum('points_retires');
                $eleve->score_calcule = max(0, 100 - $pointsRetires);
                $eleve->rapports_count = $eleve->rapports->count();
                $eleve->conseils_count = $eleve->conseils->count();
                $eleve->conseils_ouverts_count = $eleve->conseils->where('statut', 'ouvert')->count();
                return $eleve;
            });

        return response()->json($eleves);
    }

    public function showEleve($id)
    {
        $eleve = Eleve::with('rapports.prof')->findOrFail($id);
        return view('eleve-profile', compact('eleve'));
    }
    
    public function statistiques()
    {
        // Statistiques générales
        $totalRapports = Rapport::count();
        $totalConseils = Conseil::count();
        $totalProfs = User::where('role', 'prof')->count();
        $totalPointsRetires = Rapport::sum('points_retires');
        
        // Conseils
        $conseilsOuverts = Conseil::where('statut', 'ouvert')->count();
        $conseilsClotures = Conseil::where('statut', 'cloture')->count();
        
        // Alertes
        $conseilsEnAttente = 0;
        if (auth()->user()->isProf()) {
            $conseilsEnAttente = Conseil::whereHas('profs', function($query) {
                $query->where('prof_id', auth()->id())->where('a_repondu', false);
            })->where('statut', 'ouvert')->count();
        }
        
        // Top profs
        $topProfs = User::where('role', 'prof')
            ->withCount('rapports')
            ->orderBy('rapports_count', 'desc')
            ->limit(5)
            ->get();

        return view('statistiques', compact(
            'totalRapports',
            'totalConseils',
            'totalProfs',
            'totalPointsRetires',
            'conseilsOuverts',
            'conseilsClotures',
            'conseilsEnAttente',
            'topProfs'
        ));
    }
}