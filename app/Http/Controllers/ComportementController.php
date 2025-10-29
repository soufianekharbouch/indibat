<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comportement;
use App\Models\Rapport;

class ComportementController extends Controller
{
    public function index()
    {
        $comportements = Comportement::orderBy('categorie')->orderBy('points_retires', 'desc')->get();
        return view('comportements.index', compact('comportements'));
    }

    public function create()
    {
        $categories = [
            'leger' => 'خفيف',
            'moyen' => 'متوسط', 
            'grave' => 'خطير'
        ];
        
        return view('comportements.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_fr' => 'required|string|max:191',
            'nom_ar' => 'required|string|max:191',
            'points_retires' => 'required|numeric|min:0.1|max:20',
            'categorie' => 'required|string|in:leger,moyen,grave'
        ]);

        Comportement::create([
            'nom_fr' => $request->nom_fr,
            'nom_ar' => $request->nom_ar,
            'points_retires' => $request->points_retires,
            'categorie' => $request->categorie
        ]);

        return redirect()->route('comportements.index')
            ->with('success', 'تمت إضافة السلوك بنجاح.');
    }

    public function edit(Comportement $comportement)
    {
        $categories = [
            'leger' => 'خفيف',
            'moyen' => 'متوسط',
            'grave' => 'خطير'
        ];
        
        return view('comportements.edit', compact('comportement', 'categories'));
    }

    public function update(Request $request, Comportement $comportement)
    {
        $request->validate([
            'nom_fr' => 'required|string|max:191',
            'nom_ar' => 'required|string|max:191',
            'points_retires' => 'required|numeric|min:0.1|max:20',
            'categorie' => 'required|string|in:leger,moyen,grave'
        ]);

        // Sauvegarder les anciens points pour le recalcul
        $anciensPoints = $comportement->points_retires;
        $ancienNomFr = $comportement->nom_fr;

        $comportement->update([
            'nom_fr' => $request->nom_fr,
            'nom_ar' => $request->nom_ar,
            'points_retires' => $request->points_retires,
            'categorie' => $request->categorie
        ]);

        // Recalculer les points des rapports si les points ou le nom ont changé
        if ($anciensPoints != $request->points_retires || $ancienNomFr != $request->nom_fr) {
            $rapportsModifies = Rapport::recalculerTousLesPoints();
            
            return redirect()->route('comportements.index')
                ->with('success', "تم تعديل السلوك بنجاح. تم تحديث {$rapportsModifiques} تقرير.");
        }

        return redirect()->route('comportements.index')
            ->with('success', 'تم تعديل السلوك بنجاح.');
    }

    public function destroy(Comportement $comportement)
    {
        // Vérifier si le comportement est utilisé dans des rapports
        $rapportsAvecCeComportement = Rapport::whereJsonContains('comportements', $comportement->nom_fr)->count();
        
        if ($rapportsAvecCeComportement > 0) {
            return redirect()->route('comportements.index')
                ->with('error', 'لا يمكن حذف هذا السلوك لأنه مستخدم في ' . $rapportsAvecCeComportement . ' تقرير.');
        }

        $comportement->delete();

        return redirect()->route('comportements.index')
            ->with('success', 'تم حذف السلوك بنجاح.');
    }

    public function recalculerPoints()
    {
        $rapportsModifies = Rapport::recalculerTousLesPoints();
        
        return redirect()->route('comportements.index')
            ->with('success', "تم إعادة حساب نقاط جميع التقارير. تم تحديث {$rapportsModifies} تقرير.");
    }
}