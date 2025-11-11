<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_massar',
        'nom_ar',
        'prenom_ar',
        'classe'
    ];

    protected $appends = ['score_discipline_calcule'];

    public function rapports()
    {
        return $this->hasMany(Rapport::class);
    }
    public function decisions()
    {
        return $this->hasMany(\App\Models\Decision::class);
    }
    public function getNomCompletAttribute()
    {
        return $this->nom_ar . ' ' . $this->prenom_ar;
    }

    // Accessor pour calculer le score dynamiquement basé sur les rapports
    public function getScoreDisciplineCalculeAttribute()
    {
        $scoreInitial = 100.00;
        $pointsRetires = $this->rapports->sum('points_retires');
        return max(0.00, $scoreInitial - $pointsRetires);
    }

    // Méthode pour recalculer tous les scores (à utiliser quand les points des comportements changent)
    public static function recalculerTousLesScores()
    {
        $eleves = self::with('rapports')->get();
        
        foreach ($eleves as $eleve) {
            // Le score est calculé dynamiquement, donc pas besoin de mise à jour
            // Cette méthode est gardée pour la compatibilité
        }
        
        return $eleves->count();
    }
    public function conseils()
    {
        return $this->hasMany(Conseil::class);
    }

    public function getConseilOuvertAttribute()
    {
        return $this->conseils()->where('statut', 'ouvert')->first();
    }
}