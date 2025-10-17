<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rapport;
use App\Models\Eleve;

class RecalculerScores extends Command
{
    protected $signature = 'scores:recalculer';
    protected $description = 'Recalculer tous les points des rapports et scores des élèves';

    public function handle()
    {
        $this->info('Début du recalcul des points...');
        
        // Recalculer tous les points des rapports
        $rapportsModifies = Rapport::recalculerTousLesPoints();
        $this->info("{$rapportsModifies} rapports mis à jour");
        
        // Les scores des élèves sont calculés dynamiquement, pas besoin de les mettre à jour
        $this->info('Recalcul terminé avec succès!');
        
        return 0;
    }
}