<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملف الطالب - إنضباط</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">ملف الطالب</h1>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 px-3 py-1 rounded">رجوع</a>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">{{ $eleve->nom_ar }} {{ $eleve->prenom_ar }}</h2>
                    <p class="text-gray-600">الرمز الماسي: {{ $eleve->code_massar }} | الصف: {{ $eleve->classe }}</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold 
                        {{ $eleve->score_discipline_calcule >= 80 ? 'text-green-600' : 
                        ($eleve->score_discipline_calcule >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ $eleve->score_discipline_calcule }}
                    </div>
                    <div class="text-gray-600">نتيجة الانضباط</div>
                </div>
            </div>
        </div>

        <!-- Section unifiée des événements -->
        @php
            // Créer une collection mixte de tous les événements
            $evenements = collect();
            
            // Ajouter les rapports avec leur type et date de référence
            foreach ($eleve->rapports as $rapport) {
                $evenements->push([
                    'type' => 'rapport',
                    'data' => $rapport,
                    'date_reference' => $rapport->date_seance ?? $rapport->created_at,
                    'created_at' => $rapport->created_at
                ]);
            }
            
            // Ajouter les conseils avec leur type et date de référence
            foreach ($eleve->conseils as $conseil) {
                $evenements->push([
                    'type' => 'conseil',
                    'data' => $conseil,
                    'date_reference' => $conseil->created_at,
                    'created_at' => $conseil->created_at
                ]);
            }
            
            // Trier par date de référence (du plus ancien au plus récent)
            $evenementsTries = $evenements->sortBy('date_reference');
        @endphp
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">السجل الزمني للانضباط 
                <span class="text-sm font-normal text-gray-600">(من الأقدم إلى الأحدث)</span>
            </h3>
            
            @if($evenementsTries->count() > 0)
            <div class="space-y-6">
                @foreach($evenementsTries as $evenement)
                    @if($evenement['type'] === 'rapport')
                        <!-- Affichage d'un rapport -->
                        @php $rapport = $evenement['data']; @endphp
                        <div class="border-l-4 border-red-500 pl-4 bg-red-50 p-4 rounded">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-lg">تقرير انضباط - {{ $rapport->matiere }}</h4>
                                        <p class="text-sm text-gray-600">
                                            {{ $rapport->date_seance->format('d/m/Y') }} - {{ $rapport->heure_seance }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">
                                        -{{ $rapport->points_retires }} نقطة
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $rapport->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Cacher l'identité du professeur pour les autres profs -->
                            @if(auth()->user()->isRoot() || auth()->user()->isAdmin() || auth()->user()->isMotasarrif())
                            <p class="text-sm text-gray-500 mb-2">
                                الأستاذ: {{ $rapport->prof->prenom }} {{ $rapport->prof->nom }}
                                @if($rapport->prof_id === auth()->id())
                                <span class="text-blue-600">(أنت)</span>
                                @endif
                            </p>
                            @else
                            <p class="text-sm text-gray-500 mb-2">
                                تقرير مادة {{ $rapport->matiere }}
                                @if($rapport->prof_id === auth()->id())
                                <span class="text-green-600">• تقريرك</span>
                                @endif
                            </p>
                            @endif

                            <div class="mt-3">
                                <p class="text-sm font-medium text-gray-700 mb-2">السلوكيات المسجلة:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($rapport->comportements_ar as $comportementAr)
                                    <span class="bg-red-200 text-red-800 px-2 py-1 rounded text-xs">
                                        {{ $comportementAr }}
                                    </span>
                                    @endforeach
                                </div>
                                @if($rapport->notes_additionnelles)
                                <p class="text-sm mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded">
                                    <strong>ملاحظات إضافية:</strong> {{ $rapport->notes_additionnelles }}
                                </p>
                                @endif
                            </div>
                        </div>

                    @elseif($evenement['type'] === 'conseil')
                        <!-- Affichage d'un conseil -->
                        @php $conseil = $evenement['data']; @endphp
                        <div class="border-l-4 {{ $conseil->statut === 'ouvert' ? 'border-yellow-500 bg-yellow-50' : 'border-green-500 bg-green-50' }} pl-4 p-4 rounded">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 {{ $conseil->statut === 'ouvert' ? 'bg-yellow-500' : 'bg-green-500' }} rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-lg">
                                            مجلس انضباط 
                                            @if($conseil->statut === 'ouvert')
                                            <span class="text-yellow-600">(جاري)</span>
                                            @else
                                            <span class="text-green-600">(مغلق)</span>
                                            @endif
                                        </h4>
                                        <p class="text-sm text-gray-600">
                                            أنشئ في: {{ $conseil->created_at->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        {{ $conseil->statut === 'ouvert' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $conseil->statut === 'ouvert' ? 'مفتوح' : 'مغلق' }}
                                    </span>
                                    <a href="{{ route('conseils.show', $conseil->id) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm text-center">
                                       عرض التفاصيل
                                    </a>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-600 mb-2">
                                <div>
                                    <strong>السبب:</strong> {{ $conseil->raison_principale }}
                                </div>
                                <div>
                                    <strong>أنشئ بواسطة:</strong> {{ $conseil->admin->prenom }} {{ $conseil->admin->nom }}
                                </div>
                                @if($conseil->description)
                                <div class="md:col-span-2">
                                    <strong>الوصف:</strong> {{ $conseil->description }}
                                </div>
                                @endif
                            </div>

                            @if($conseil->statut === 'ferme' && $conseil->decision_finale)
                            <div class="mt-3 p-3 bg-white rounded border {{ $conseil->reinitialiser_score ? 'border-green-200 bg-green-25' : 'border-gray-200' }}">
                                <p class="font-bold text-gray-700 mb-1">القرار النهائي:</p>
                                <p class="text-gray-800">{{ $conseil->decision_finale }}</p>
                                @if($conseil->reinitialiser_score)
                                <p class="text-sm text-green-600 mt-1">
                                    ✓ تم إعادة تعيين نقاط الانضباط
                                </p>
                                @endif
                                <p class="text-xs text-gray-500 mt-2">
                                    تم الإغلاق في: {{ $conseil->updated_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            @endif

                            <!-- Bouton d'action pour les profs concernés -->
                            @if(auth()->user()->isProf() && $conseil->statut === 'ouvert' && $conseil->profs->contains(auth()->id()) && !$conseil->profARepondu(auth()->id()))
                            <div class="mt-3 text-center">
                                <a href="{{ route('conseils.show', $conseil->id) }}" 
                                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm inline-block">
                                   إبداء الرأي
                                </a>
                            </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-600 text-lg">لا توجد أحداث انضباط</p>
                <p class="text-gray-500 text-sm mt-2">لم يتم تسجيل أي تقارير أو مجالس انضباط حتى الآن</p>
            </div>
            @endif
        </div>
    </div>
</body>
</html>