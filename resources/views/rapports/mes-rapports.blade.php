@extends('layouts.app')

@section('title', 'تقاريري')

@section('content')
<div class="container mx-auto p-4">
    <!-- En-tête -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            @if(auth()->user()->isAdmin() || auth()->user()->isRoot())
                جميع التقارير
            @else
                تقاريري
            @endif
        </h1>
        <p class="text-gray-600 text-sm">
            @if(auth()->user()->isAdmin() || auth()->user()->isRoot())
                إدارة وتتبع جميع تقارير الأساتذة
            @else
                إدارة وتتبع جميع التقارير التي قمت بإعدادها
            @endif
        </p>
    </div>

    <!-- Cartes de statistiques compactes -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs">إجمالي التقارير</p>
                    <p class="text-xl font-bold text-blue-600">{{ $rapports->count() }}</p>
                </div>
                <div class="bg-blue-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs">النقاط المخصومة</p>
                    <p class="text-xl font-bold text-red-600">{{ $rapports->sum('points_retires') }}</p>
                </div>
                <div class="bg-red-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs">الطلاب المعنيون</p>
                    <p class="text-xl font-bold text-green-600">{{ $rapports->groupBy('eleve_id')->count() }}</p>
                </div>
                <div class="bg-green-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs">متوسط النقاط</p>
                    <p class="text-xl font-bold text-purple-600">{{ $rapports->avg('points_retires') ? round($rapports->avg('points_retires'), 1) : 0 }}</p>
                </div>
                <div class="bg-purple-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des rapports simplifiée -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                @if(auth()->user()->isAdmin() || auth()->user()->isRoot())
                    قائمة جميع التقارير
                @else
                    قائمة تقاريري
                @endif
            </h2>
        </div>

        @if($rapports->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700">الطالب</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700">المادة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700">التاريخ</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700">النقاط</th>
                            @if(auth()->user()->isAdmin() || auth()->user()->isRoot())
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-700">الأستاذ</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($rapports as $rapport)
                        <tr data-rapport-id="{{ $rapport->id }}" 
                            class="hover:bg-gray-50 cursor-pointer transition duration-200 rapport-row">
                            <!-- Colonne Élève -->
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium text-xs">
                                            @if($rapport->eleve && $rapport->eleve->prenom_ar && $rapport->eleve->nom_ar)
                                                {{ substr($rapport->eleve->prenom_ar, 0, 1) }}{{ substr($rapport->eleve->nom_ar, 0, 1) }}
                                            @else
                                                ??
                                            @endif
                                        </span>
                                    </div>
                                    <div class="mr-3">
                                        @if($rapport->eleve)
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $rapport->eleve->prenom_ar }} {{ $rapport->eleve->nom_ar }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $rapport->eleve->classe ?? 'غير محدد' }}</div>
                                        @else
                                            <div class="text-sm font-medium text-gray-900">طالب محذوف</div>
                                            <div class="text-xs text-gray-500">-</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Colonne Matière -->
                            <td class="px-4 py-4 text-sm text-gray-900">
                                {{ $rapport->matiere }}
                            </td>

                            <!-- Colonne Date -->
                            <td class="px-4 py-4 text-sm text-gray-500">
                                <div>{{ \Carbon\Carbon::parse($rapport->date_seance)->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $rapport->heure_seance }}</div>
                            </td>

                            <!-- Colonne Points -->
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $rapport->points_retires > 5 ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                    -{{ $rapport->points_retires }}
                                </span>
                            </td>

                            <!-- Colonne Professeur (uniquement pour admin) -->
                            @if(auth()->user()->isAdmin() || auth()->user()->isRoot())
                                <td class="px-4 py-4 text-sm text-gray-500">
                                    @if($rapport->prof)
                                        {{ $rapport->prof->prenom }} {{ $rapport->prof->nom }}
                                    @else
                                        أستاذ محذوف
                                    @endif
                                </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">
                    لا توجد تقارير
                </h3>
                <p class="mt-1 text-xs text-gray-500">
                    @if(auth()->user()->isAdmin() || auth()->user()->isRoot())
                        لم يتم إنشاء أي تقارير حتى الآن.
                    @else
                        لم تقم بإنشاء أي تقارير حتى الآن.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Popup pour les détails du rapport -->
<div id="rapportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <!-- En-tête de la modal -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">تفاصيل التقرير</h3>
                <button onclick="closeRapportModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Contenu de la modal -->
            <div id="rapportDetails" class="space-y-4">
                <!-- Les détails seront chargés ici par JavaScript -->
            </div>
        </div>
    </div>
</div>
@php
$rapportsJs = [];
foreach($rapports as $r){
    $rapportsJs[$r->id] = [
        'eleve' => $r->eleve ? [
            'id'           => $r->eleve->id,
            'nom_complet'  => ($r->eleve->prenom_ar ?? '').' '.($r->eleve->nom_ar ?? ''),
            'classe'       => $r->eleve->classe,
            'code_massar'  => $r->eleve->code_massar,
        ] : null,
        'prof' => $r->prof ? [
            'nom_complet'  => ($r->prof->prenom ?? '').' '.($r->prof->nom ?? ''),
            'email'        => $r->prof->email,
        ] : null,
        'matiere'             => $r->matiere,
        'date_seance'         => $r->date_seance ? \Carbon\Carbon::parse($r->date_seance)->format('d/m/Y') : null,
        'heure_seance'        => $r->heure_seance,
        'points_retires'      => (float) $r->points_retires,
        'comportements'       => is_array($r->comportements) ? $r->comportements : [],
        'notes_additionnelles'=> $r->notes_additionnelles,
        'created_at'          => $r->created_at?->format('d/m/Y H:i'),
    ];
}
@endphp
<script>
const rapportsData = @json($rapportsJs);
</script>
<script>

    // Gestion du clic sur les lignes
    document.addEventListener('DOMContentLoaded', function() {
        const rapportRows = document.querySelectorAll('.rapport-row');
        
        rapportRows.forEach(row => {
            row.addEventListener('click', function() {
                const rapportId = this.getAttribute('data-rapport-id');
                showRapportDetails(rapportId);
            });
        });
    });

    function showRapportDetails(rapportId) {
        const rapport = rapportsData[rapportId];
        if (!rapport) return;

        const detailsContainer = document.getElementById('rapportDetails');
        
        detailsContainer.innerHTML = `
            <!-- Informations de base -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-bold text-gray-700 mb-2">معلومات الطالب</h4>
                    <p class="text-sm"><strong>الاسم:</strong> ${rapport.eleve ? rapport.eleve.nom_complet : 'طالب محذوف'}</p>
                    <p class="text-sm"><strong>الصف:</strong> ${rapport.eleve ? rapport.eleve.classe : '-'}</p>
                    <p class="text-sm"><strong>الرمز الماسي:</strong> ${rapport.eleve ? rapport.eleve.code_massar : '-'}</p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-bold text-gray-700 mb-2">معلومات الحصة</h4>
                    <p class="text-sm"><strong>المادة:</strong> ${rapport.matiere}</p>
                    <p class="text-sm"><strong>التاريخ:</strong> ${rapport.date_seance}</p>
                    <p class="text-sm"><strong>الوقت:</strong> ${rapport.heure_seance}</p>
                </div>
            </div>

            <!-- Informations الأستاذ pour les admins -->
            ${rapport.prof ? `
            <div class="bg-blue-50 p-4 rounded-lg">
                <h4 class="font-bold text-blue-700 mb-2">معلومات الأستاذ</h4>
                <p class="text-sm"><strong>الاسم:</strong> ${rapport.prof.nom_complet}</p>
                <p class="text-sm"><strong>البريد الإلكتروني:</strong> ${rapport.prof.email}</p>
            </div>
            ` : ''}

            <!-- السلوكيات -->
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h4 class="font-bold text-yellow-700 mb-2">السلوكيات المسجلة</h4>
                <div class="flex flex-wrap gap-2">
                    ${rapport.comportements && rapport.comportements.length > 0 
                        ? rapport.comportements.map(comportement => 
                            `<span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">${comportement}</span>`
                        ).join('')
                        : '<p class="text-gray-500 text-sm">لا توجد سلوكيات مسجلة</p>'
                    }
                </div>
            </div>

            <!-- النقاط -->
            <div class="bg-red-50 p-4 rounded-lg">
                <h4 class="font-bold text-red-700 mb-2">النقاط المخصومة</h4>
                <p class="text-2xl font-bold text-red-600">-${rapport.points_retires} نقطة</p>
            </div>

            <!-- الملاحظات الإضافية -->
            ${rapport.notes_additionnelles ? `
            <div class="bg-green-50 p-4 rounded-lg">
                <h4 class="font-bold text-green-700 mb-2">ملاحظات إضافية</h4>
                <p class="text-sm text-gray-700">${rapport.notes_additionnelles}</p>
            </div>
            ` : ''}

            <!-- معلومات التقرير -->
            <div class="bg-gray-100 p-4 rounded-lg">
                <h4 class="font-bold text-gray-700 mb-2">معلومات التقرير</h4>
                <p class="text-sm"><strong>تاريخ الإرسال:</strong> ${rapport.created_at}</p>
            </div>

            <!-- 🔹 Nouveau bouton vers le profil de l'élève -->
            ${rapport.eleve ? `
            <div class="mt-6 flex justify-center">
                <a href="/eleve/${rapport.eleve.id}" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition duration-300">
                    عرض ملف التلميذ
                </a>
            </div>
            ` : ''}
        `;

        document.getElementById('rapportModal').classList.remove('hidden');
    }

    function closeRapportModal() {
        document.getElementById('rapportModal').classList.add('hidden');
    }

    // Fermer la modal en cliquant à l'extérieur
    document.getElementById('rapportModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeRapportModal();
        }
    });

    // Fermer la modal avec la touche Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeRapportModal();
        }
    });
</script>

<style>
    .rapport-row {
        -webkit-tap-highlight-color: transparent;
    }
    
    .rapport-row:active {
        background-color: #f3f4f6;
    }
    
    @media (max-width: 768px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
    }
</style>
@endsection