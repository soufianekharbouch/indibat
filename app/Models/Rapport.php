<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rapport extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'prof_id',
        'date_seance',
        'heure_seance',
        'matiere',
        'comportements',
        'notes_additionnelles',
        'points_retires'
    ];

    protected $casts = [
        'comportements' => 'array',
        'date_seance' => 'date',
    ];

    // Événement pour recalculer les points si les comportements changent
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($rapport) {
            // Si les comportements changent, recalculer les points
            if ($rapport->isDirty('comportements')) {
                $rapport->points_retires = $rapport->calculerPointsRetires();
            }
        });
    }

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function prof()
    {
        return $this->belongsTo(User::class, 'prof_id');
    }

    // Nouvelle méthode pour obtenir les noms arabes des comportements
    public function getComportementsArAttribute()
    {
        $comportementsAr = [];
        $comportementsModel = Comportement::all()->keyBy('nom_fr');
        
        foreach ($this->comportements as $comportement) {
            if (isset($comportementsModel[$comportement])) {
                $comportementsAr[] = $comportementsModel[$comportement]->nom_ar;
            } else {
                $comportementsAr[] = $comportement;
            }
        }
        
        return $comportementsAr;
    }

    // Calculer les points retirés basés sur les comportements actuels
    public function calculerPointsRetires()
    {
        $points = 0;
        $comportementsModel = Comportement::all()->keyBy('nom_fr');
        
        foreach ($this->comportements as $comportement) {
            if (isset($comportementsModel[$comportement])) {
                $points += $comportementsModel[$comportement]->points_retires;
            }
        }
        
        return $points;
    }

    // Méthode pour recalculer tous les points des rapports (à appeler quand les points des comportements changent)
    public static function recalculerTousLesPoints()
    {
        $rapports = self::all();
        $comportementsModel = Comportement::all()->keyBy('nom_fr');
        $updatedCount = 0;

        foreach ($rapports as $rapport) {
            $nouveauxPoints = 0;
            
            foreach ($rapport->comportements as $comportement) {
                if (isset($comportementsModel[$comportement])) {
                    $nouveauxPoints += $comportementsModel[$comportement]->points_retires;
                }
            }

            // Mettre à jour seulement si les points ont changé
            if ($rapport->points_retires != $nouveauxPoints) {
                $rapport->points_retires = $nouveauxPoints;
                $rapport->save();
                $updatedCount++;
            }
        }

        return $updatedCount;
    }

    // ... garder les méthodes existantes pour les contraintes ...
    public static function canCreateRapport($eleve_id, $prof_id)
    {
        $constraints = self::checkConstraints($eleve_id, $prof_id);
        return $constraints['can_create'];
    }

    public static function checkConstraints($eleve_id, $prof_id)
    {
        $now = Carbon::now();

        // Contrainte 1: Pas de rapport dans les 7 derniers jours (basé sur created_at)
        $lastRapport = self::where('eleve_id', $eleve_id)
            ->where('prof_id', $prof_id)
            ->orderBy('created_at', 'desc')
            ->first();

        $recentRapportExists = false;
        $daysSinceLastRapport = null;

        if ($lastRapport) {
            $daysSinceLastRapport = $now->diffInDays($lastRapport->created_at);
            $recentRapportExists = $daysSinceLastRapport < 7;
        }

        // Contrainte 2: Maximum 10 rapports au total pour le même élève
        $totalRapports = self::where('eleve_id', $eleve_id)
            ->where('prof_id', $prof_id)
            ->count();

        $can_create = !$recentRapportExists && $totalRapports < 10;

        return [
            'can_create' => $can_create,
            'recent_rapport_exists' => $recentRapportExists,
            'total_rapports' => $totalRapports,
            'max_rapports_reached' => $totalRapports >= 10,
            'days_since_last_rapport' => $daysSinceLastRapport,
            'last_rapport_date' => $lastRapport ? $lastRapport->created_at : null
        ];
    }

    public static function getNextAllowedDate($eleve_id, $prof_id)
    {
        $lastRapport = self::where('eleve_id', $eleve_id)
            ->where('prof_id', $prof_id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastRapport) {
            return $lastRapport->created_at->addDays(7)->format('d/m/Y');
        }

        return null;
    }

    public static function getDaysRemaining($eleve_id, $prof_id)
    {
        $lastRapport = self::where('eleve_id', $eleve_id)
            ->where('prof_id', $prof_id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastRapport) {
            $nextAllowedDate = $lastRapport->created_at->addDays(7);
            $now = Carbon::now();
            
            if ($now->lt($nextAllowedDate)) {
                return $now->diffInDays($nextAllowedDate);
            }
        }

        return 0;
    }
}