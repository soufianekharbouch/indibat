<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visite;
use Jenssegers\Agent\Agent;

class TrackVisite
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Ne pas tracker les requêtes AJAX et certaines routes
        if ($request->ajax() || $request->is('api/*')) {
            return $response;
        }

        $this->enregistrerVisite($request);

        return $response;
    }

    private function enregistrerVisite(Request $request)
    {
        try {
            $agent = new Agent();
            
            if (auth()->check()) {
                // Utilisateur authentifié
                $identifiant = auth()->user()->username;
                $type = 'utilisateur';
                $appareil = $this->getAppareilName($agent);
            } else {
                // Visiteur non authentifié
                $identifiant = $request->ip();
                $type = 'visiteur';
                $appareil = $this->getAppareilName($agent);
            }

            // Rechercher une visite existante
            $visite = Visite::where('identifiant', $identifiant)
                           ->where('type', $type)
                           ->first();

            if ($visite) {
                // Mettre à jour la visite existante
                $visite->update([
                    'nombre_visites' => $visite->nombre_visites + 1,
                    'derniere_visite' => now(),
                    'user_agent' => $request->userAgent(),
                    'appareil' => $appareil
                ]);
            } else {
                // Créer une nouvelle visite
                Visite::create([
                    'identifiant' => $identifiant,
                    'type' => $type,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'appareil' => $appareil,
                    'nombre_visites' => 1,
                    'derniere_visite' => now()
                ]);
            }
        } catch (\Exception $e) {
            // Logger l'erreur mais ne pas interrompre l'application
            \Log::error('Erreur tracking visite: ' . $e->getMessage());
        }
    }

    private function getAppareilName(Agent $agent)
    {
        if ($agent->isDesktop()) {
            return 'Desktop';
        } elseif ($agent->isTablet()) {
            return 'Tablet - ' . $agent->device();
        } elseif ($agent->isMobile()) {
            return 'Mobile - ' . $agent->device();
        } else {
            return 'Autre';
        }
    }
}