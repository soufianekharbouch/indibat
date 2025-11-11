<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Decision;

class DecisionController extends Controller
{
    // Formulaire de création
    public function create($eleveId)
    {
        $user = auth()->user();
        if (!$user || !$user->isMotasarrif()) {
            abort(403);
        }

        $eleve = Eleve::findOrFail($eleveId);

        // Propositions par défaut (tu peux adapter)
        $choices = [
            'استدعاء التلميذ للإدارة',
            'التوجه للقاعة للوقوف على الواقعة',
            'استدعاء ولي الأمر',
            'مثول التلميذ أمام رئيس المؤسسة',
            'توجيه إنذار شفوي للتلميذ',
            'توقيع التزام كتابي من طرف التلميذ',
            'توقيع التزام كتابي من طرف ولي الأمر',
            'إجراء آخر',
        ];

        return view('decisions.create', compact('eleve', 'choices'));
    }

    // Enregistrement
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->isMotasarrif()) {
            abort(403);
        }

        $data = $request->validate([
            'eleve_id'       => 'required|exists:eleves,id',
            'decision_date'  => 'nullable|date',
            'decision_time'  => 'nullable',
            'choices'        => 'required|array|min:1',
            'choices.*'      => 'string|max:255',
            'details'        => 'nullable|string|max:5000',
        ]);

        $decision = Decision::create([
            'eleve_id'       => $data['eleve_id'],
            'motasarrif_id'  => $user->id,
            'decision_date'  => $data['decision_date'] ?? null,
            'decision_time'  => $data['decision_time'] ?? null,
            'choices'        => $data['choices'],
            'details'        => $data['details'] ?? null,
        ]);

        return redirect()->route('eleve.show', $decision->eleve_id)
            ->with('success', 'تم حفظ الإجراء الإداري بنجاح.');
    }
}
