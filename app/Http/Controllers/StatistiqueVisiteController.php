<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visite;
use Carbon\Carbon;

class StatistiqueVisiteController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isRoot()) {
            abort(403, 'Unauthorized action.');
        }

        // Statistiques générales
        $totalVisites = Visite::count();
        $totalVisiteurs = Visite::visiteurs()->count();
        $totalUtilisateurs = Visite::utilisateurs()->count();
        
        // Visites aujourd'hui
        $visitesAujourdhui = Visite::whereDate('created_at', today())->count();
        
        // Top des visiteurs
        $topVisiteurs = Visite::orderBy('nombre_visites', 'desc')
                            ->take(10)
                            ->get();

        // Distribution par type d'appareil
        $appareils = Visite::select('appareil', \DB::raw('COUNT(*) as count'))
                          ->groupBy('appareil')
                          ->get();

        // Visites des 30 derniers jours
        $visites30Jours = Visite::where('created_at', '>=', now()->subDays(30))
                               ->get()
                               ->groupBy(function($date) {
                                   return Carbon::parse($date->created_at)->format('Y-m-d');
                               });

        return view('statistiques-visites.index', compact(
            'totalVisites',
            'totalVisiteurs',
            'totalUtilisateurs',
            'visitesAujourdhui',
            'topVisiteurs',
            'appareils',
            'visites30Jours'
        ));
    }

    public function details()
    {
        if (!auth()->user()->isRoot()) {
            abort(403, 'Unauthorized action.');
        }

        $visites = Visite::orderBy('derniere_visite', 'desc')
                        ->paginate(20);

        return view('statistiques-visites.details', compact('visites'));
    }
}