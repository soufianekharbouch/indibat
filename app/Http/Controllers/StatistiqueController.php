<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use App\Models\Conseil;
use App\Models\User;
use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $totalRapports = Rapport::count();
        $totalConseils = Conseil::count();
        $totalProfs = User::where('role', 'prof')->count();
        $totalPointsRetires = Rapport::sum('points_retires');
        
        // Classification basée sur les points
        $rapportsLegers = Rapport::where('points_retires', '<=', 2)->count();
        $rapportsMoyens = Rapport::whereBetween('points_retires', [2.1, 5])->count();
        $rapportsGraves = Rapport::where('points_retires', '>', 5)->count();
        
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
            'rapportsLegers',
            'rapportsMoyens',
            'rapportsGraves',
            'conseilsOuverts',
            'conseilsClotures',
            'conseilsEnAttente',
            'topProfs'
        ));
    }
}