<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visite extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifiant',
        'type',
        'ip_address',
        'user_agent',
        'appareil',
        'nombre_visites',
        'derniere_visite'
    ];

    protected $casts = [
        'derniere_visite' => 'datetime',
    ];

    // Scope pour les visiteurs non authentifiés
    public function scopeVisiteurs($query)
    {
        return $query->where('type', 'visiteur');
    }

    // Scope pour les utilisateurs authentifiés
    public function scopeUtilisateurs($query)
    {
        return $query->where('type', 'utilisateur');
    }

    // Scope pour les visites récentes
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('derniere_visite', '>=', now()->subDays($days));
    }
}