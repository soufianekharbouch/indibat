<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comportement extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_fr',
        'nom_ar',
        'points_retires',
        'categorie'
    ];

    protected $casts = [
        'points_retires' => 'integer'
    ];

    // Événement pour recalculer tous les rapports quand les points changent
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($comportement) {
            if ($comportement->isDirty('points_retires')) {
                // Recalculer tous les rapports qui utilisent ce comportement
                Rapport::recalculerTousLesPoints();
            }
        });

        static::saved(function ($comportement) {
            if ($comportement->wasChanged('points_retires')) {
                // Optionnel: logger le changement
                \Log::info("Points du comportement {$comportement->nom_fr} modifiés, recalcul des rapports déclenché");
            }
        });
    }
}