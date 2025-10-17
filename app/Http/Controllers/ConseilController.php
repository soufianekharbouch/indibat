<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conseil;
use App\Models\Eleve;
use App\Models\User;
use Carbon\Carbon;

class ConseilController extends Controller
{
  public function create($eleveId)
    {
        $eleve = Eleve::findOrFail($eleveId);
        $profs = User::where('role', 'prof')->get();
        
        $raisons = [
            'كثرة التقارير' => 'كثرة التقارير',
            'إصرار أحد الأساتذة' => 'إصرار أحد الأساتذة',
            'سلوك عدواني' => 'سلوك عدواني',
            'عنف تجاه الزملاء' => 'عنف تجاه الزملاء',
            'عدم احترام الطاقم التعليمي' => 'عدم احترام الطاقم التعليمي',
            'تزوير أو غش' => 'تزوير أو غش',
            'سلوك خطير يهدد السلامة' => 'سلوك خطير يهدد السلامة',
            'تكرار المخالفات' => 'تكرار المخالفات',
            'تراكم النقاط السلبية' => 'تراكم النقاط السلبية',
            'سلوك غير أخلاقي' => 'سلوك غير أخلاقي',
            'تأثير سلبي على الزملاء' => 'تأثير سلبي على الزملاء',
            'عدم الالتزام بالزي المدرسي' => 'عدم الالتزام بالزي المدرسي',
            'التأخر المتكرر' => 'التأخر المتكرر',
            'الغياب غير المبرر' => 'الغياب غير المبرر',
            'أخرى' => 'أخرى'
        ];

        return view('conseils.create', compact('eleve', 'profs', 'raisons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
            'profs' => 'required|array',
            'profs.*' => 'exists:users,id',
            'raisons' => 'required|array',
            'raisons.*' => 'string',
            'description' => 'nullable|string',
            'date_fermeture' => 'nullable|date|after:today',
        ]);

        // Combiner les raisons sélectionnées en une seule chaîne
        $raisonsPrincipales = implode('، ', $request->raisons);

        $conseil = Conseil::create([
            'eleve_id' => $request->eleve_id,
            'admin_id' => auth()->id(),
            'raison_principale' => $raisonsPrincipales,
            'description' => $request->description,
            'date_fermeture' => $request->date_fermeture,
            'statut' => 'ouvert',
        ]);

        // Attacher les profs concernés
        $conseil->profs()->attach($request->profs);

        return redirect()->route('dashboard')->with('success', 'تم إنشاء مجلس الانضباط بنجاح');
    }

    public function index()
    {
        $user = auth()->user();
        
        if ($user->isProf()) {
            $conseils = Conseil::with(['eleve', 'admin'])
                ->whereHas('profs', function($query) use ($user) {
                    $query->where('prof_id', $user->id);
                })
                ->get()
                ->map(function($conseil) use ($user) {
                    // Ajouter l'information si le prof a répondu
                    $conseil->prof_a_repondu = $conseil->profARepondu($user->id);
                    return $conseil;
                });
        } else {
            $conseils = Conseil::with(['eleve', 'admin'])->get();
        }

        return view('conseils.index', compact('conseils'));
    }

    public function show($id)
    {
        $conseil = Conseil::with(['eleve', 'admin', 'profs', 'reponses.prof'])->findOrFail($id);
        $avisPossibles = Conseil::getAvisPossibles();

        return view('conseils.show', compact('conseil', 'avisPossibles'));
    }

    public function donnerAvis(Request $request, $id)
    {
        $request->validate([
            'avis' => 'required|string',
            'justification' => 'required|string'
        ]);

        $conseil = Conseil::findOrFail($id);
        $profId = auth()->id();

        // Vérifier que le prof fait partie du conseil
        if (!$conseil->profs->contains($profId)) {
            return redirect()->back()->with('error', 'غير مصرح لك بالمشاركة في هذا المجلس');
        }

        // Mettre à jour ou créer la réponse
        $conseil->profs()->updateExistingPivot($profId, [
            'a_repondu' => true,
            'avis' => $request->avis,
            'justification' => $request->justification,
            'repondu_le' => Carbon::now()
        ]);

        return redirect()->route('conseils.index')->with('success', 'تم إرسال رأيك بنجاح');
    }

    public function fermer(Request $request, $id)
    {
        $request->validate([
            'decision_finale' => 'required|string',
            'reinitialiser_score' => 'boolean'
        ]);

        $conseil = Conseil::findOrFail($id);

        // Vérifier que l'utilisateur est admin/root
        if (!auth()->user()->isAdmin() && !auth()->user()->isRoot()) {
            return redirect()->back()->with('error', 'غير مصرح لك بإغلاق المجلس');
        }

        // Permettre la fermeture même si la date n'est pas arrivée
        $conseil->fermer(
            $request->decision_finale,
            $request->has('reinitialiser_score')
        );

        return redirect()->route('conseils.show', $conseil->id)->with('success', 'تم إغلاق المجلس بنجاح');
    }
    public function mesConseils()
        {
            $user = auth()->user();
            
            if ($user->isProf()) {
                $conseils = Conseil::with(['eleve', 'admin'])
                    ->whereHas('profs', function($query) use ($user) {
                        $query->where('prof_id', $user->id);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $conseils = Conseil::with(['eleve'])
                    ->where('admin_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            return view('conseils.mes-conseils', compact('conseils'));
        }
}