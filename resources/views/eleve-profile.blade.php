<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملف الطالب - إنضباط</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-3">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold">ملف الطالب</h1>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 px-3 py-1 rounded text-sm">رجوع</a>
        </div>
    </nav>

    <div class="container mx-auto p-3">
        <div class="bg-white p-4 rounded-lg shadow mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold">{{ $eleve->nom_ar }} {{ $eleve->prenom_ar }}</h2>
                    <p class="text-gray-600 text-sm">رمز مسار: {{ $eleve->code_massar }} | الصف: {{ $eleve->classe }}</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold 
                        {{ $eleve->score_discipline_calcule >= 80 ? 'text-green-600' : 
                        ($eleve->score_discipline_calcule >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ number_format($eleve->score_discipline_calcule, 2) }}
                    </div>
                    <div class="text-gray-600 text-sm">نتيجة الانضباط</div>
                </div>
            </div>
        </div>

        <!-- Bouton pour créer un rapport (pour les profs seulement) -->
        @if(auth()->user()->isProf())
            @php
                $constraints = \App\Models\Rapport::checkConstraints($eleve->id, auth()->id());
                $canCreateRapport = $constraints['can_create'];
                $nextAllowedDate = \App\Models\Rapport::getNextAllowedDate($eleve->id, auth()->id());
            @endphp

            @if($canCreateRapport)
            <div class="mb-4 text-center">
                <a href="{{ route('rapport.create', $eleve->id) }}" 
                   class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 inline-flex items-center text-sm">
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    إنشاء تقرير جديد
                </a>
                <p class="text-xs text-gray-600 mt-1">يمكنك إنشاء تقرير انضباط جديد لهذا الطالب</p>
            </div>
            @else
            <div class="mb-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-yellow-800 font-medium">
                            انتظر حتى {{ $nextAllowedDate }} لإنشاء تقرير جديد لهذا الطالب
                        </p>
                    </div>
                </div>
            </div>
            @endif
        @endif

        <!-- Section unifiée des événements -->
        @php
            $evenements = collect();
            
            foreach ($eleve->rapports as $rapport) {
                $evenements->push([
                    'type' => 'rapport',
                    'data' => $rapport,
                    'date_reference' => $rapport->date_seance ?? $rapport->created_at,
                    'created_at' => $rapport->created_at
                ]);
            }
            
            foreach ($eleve->conseils as $conseil) {
                $evenements->push([
                    'type' => 'conseil',
                    'data' => $conseil,
                    'date_reference' => $conseil->created_at,
                    'created_at' => $conseil->created_at
                ]);
            }
            
            $evenementsTries = $evenements->sortBy('date_reference');
        @endphp
        
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-3">السجل الزمني للانضباط 
                <span class="text-xs font-normal text-gray-600">(من الأقدم إلى الأحدث)</span>
            </h3>
            
            @if($evenementsTries->count() > 0)
            <div class="space-y-4">
                @foreach($evenementsTries as $evenement)
                    @if($evenement['type'] === 'rapport')
                        <!-- Affichage d'un rapport -->
                        @php $rapport = $evenement['data']; @endphp
                        <div class="border-l-3 border-red-500 pr-3 bg-red-50 p-3 rounded">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-base">تقرير - {{ $rapport->matiere }}</h4>
                                        <p class="text-xs text-gray-600">
                                            {{ $rapport->date_seance->format('d/m/Y') }} - {{ $rapport->heure_seance }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-bold">
                                        -{{ number_format($rapport->points_retires, 2) }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $rapport->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            
                            @if(auth()->user()->isRoot() || auth()->user()->isAdmin() || auth()->user()->isMotasarrif())
                            <p class="text-xs text-gray-500 mb-2">
                                الأستاذ: {{ $rapport->prof->prenom }} {{ $rapport->prof->nom }}
                                @if($rapport->prof_id === auth()->id())
                                <span class="text-blue-600">(أنت)</span>
                                @endif
                            </p>
                            @else
                            <p class="text-xs text-gray-500 mb-2">
                                {{ $rapport->matiere }}
                                @if($rapport->prof_id === auth()->id())
                                <span class="text-green-600">• تقريرك</span>
                                @endif
                            </p>
                            @endif

                            <div class="mt-2">
                                <p class="text-xs font-medium text-gray-700 mb-1">السلوكيات:</p>
                                <div class="flex flex-wrap gap-1">
                                    @if(isset($rapport->comportements_ar) && is_array($rapport->comportements_ar))
                                        @foreach($rapport->comportements_ar as $comportementAr)
                                        <span class="bg-red-200 text-red-800 px-1 py-0.5 rounded text-xs">
                                            {{ $comportementAr }}
                                        </span>
                                        @endforeach
                                    @elseif(is_array($rapport->comportements))
                                        @foreach($rapport->comportements as $comportement)
                                        <span class="bg-red-200 text-red-800 px-1 py-0.5 rounded text-xs">
                                            {{ $comportement }}
                                        </span>
                                        @endforeach
                                    @endif
                                </div>
                                @if($rapport->notes_additionnelles)
                                <p class="text-xs mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded">
                                    <strong>ملاحظات:</strong> {{ $rapport->notes_additionnelles }}
                                </p>
                                @endif
                            </div>
                        </div>

                    @elseif($evenement['type'] === 'conseil')
                        <!-- Affichage d'un conseil -->
                        @php $conseil = $evenement['data']; @endphp
                        <div class="border-l-3 {{ $conseil->statut === 'ouvert' ? 'border-yellow-500 bg-yellow-50' : 'border-green-500 bg-green-50' }} pr-3 p-3 rounded">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 {{ $conseil->statut === 'ouvert' ? 'bg-yellow-500' : 'bg-green-500' }} rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-base">
                                            مجلس انضباط 
                                            @if($conseil->statut === 'ouvert')
                                            <span class="text-yellow-600">(جاري)</span>
                                            @else
                                            <span class="text-green-600">(مغلق)</span>
                                            @endif
                                        </h4>
                                        <p class="text-xs text-gray-600">
                                            {{ $conseil->created_at->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <span class="px-1 py-0.5 rounded-full text-xs 
                                        {{ $conseil->statut === 'ouvert' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $conseil->statut === 'ouvert' ? 'مفتوح' : 'مغلق' }}
                                    </span>
                                    <a href="{{ route('conseils.show', $conseil->id) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs text-center">
                                       التفاصيل
                                    </a>
                                </div>
                            </div>
                            
                            <div class="text-xs text-gray-600 mb-2">
                                <div class="mb-1">
                                    <strong>السبب:</strong> {{ $conseil->raison_principale }}
                                </div>
                                @if($conseil->description)
                                <div>
                                    <strong>الوصف:</strong> {{ \Illuminate\Support\Str::limit($conseil->description, 100) }}
                                </div>
                                @endif
                            </div>

                            @if($conseil->statut === 'ferme' && $conseil->decision_finale)
                            <div class="mt-2 p-2 bg-white rounded border {{ $conseil->reinitialiser_score ? 'border-green-200 bg-green-25' : 'border-gray-200' }}">
                                <p class="font-bold text-gray-700 text-xs mb-1">القرار النهائي:</p>
                                <p class="text-gray-800 text-xs">{{ \Illuminate\Support\Str::limit($conseil->decision_finale, 120) }}</p>
                            </div>
                            @endif

                            @if(auth()->user()->isProf() && $conseil->statut === 'ouvert' && $conseil->profs->contains(auth()->id()) && !$conseil->profARepondu(auth()->id()))
                            <div class="mt-2 text-center">
                                <a href="{{ route('conseils.show', $conseil->id) }}" 
                                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs inline-block">
                                   إبداء الرأي
                                </a>
                            </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-600 text-base">لا توجد أحداث انضباط</p>
                <p class="text-gray-500 text-xs mt-1">لم يتم تسجيل أي تقارير أو مجالس انضباط حتى الآن</p>
            </div>
            @endif
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            .p-3 {
                padding: 0.75rem;
            }
            
            .p-4 {
                padding: 1rem;
            }
            
            .space-y-4 > * + * {
                margin-top: 1rem;
            }
            
            .text-xl {
                font-size: 1.25rem;
            }
            
            .text-lg {
                font-size: 1.125rem;
            }
            
            .text-base {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            .p-3 {
                padding: 0.5rem;
            }
            
            .p-4 {
                padding: 0.75rem;
            }
            
            .text-xl {
                font-size: 1.125rem;
            }
            
            .text-lg {
                font-size: 1rem;
            }
            
            .text-base {
                font-size: 0.875rem;
            }
        }
    </style>
</body>
</html>