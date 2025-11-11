@extends('layouts.app')

@section('title', 'لوحة التحكم - إنضباط')

@section('content')
    <!-- Barre de recherche -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <input type="text" id="searchInput" 
               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
               placeholder="ابحث باسم الطالب، الرمز الماسي، أو الصف..."
               data-search-url="{{ route('search.eleves') }}">
        <div id="searchResults" class="mt-2 hidden bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto"></div>
    </div>

    <!-- Liste des élèves -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b">
            <h2 class="text-lg font-bold">قائمة الطلاب</h2>
        </div>
        
        @if(auth()->user()->isProf())
        <!-- Vue simplifiée pour les professeurs -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">الرمز الماسي</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">الاسم الكامل</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">نتيجة الانضباط</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">عدد التقارير</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="elevesTableBody">
                    @foreach($eleves as $eleve)
                    <tr data-url="{{ route('eleve.show', $eleve->id) }}" class="hover:bg-gray-50 cursor-pointer transition duration-200 eleve-row" 
                        data-eleve-id="{{ $eleve->id }}">
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $eleve->code_massar }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm font-semibold text-gray-900">
                                {{ $eleve->nom_ar }} {{ $eleve->prenom_ar }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">{{ $eleve->classe }}</div>
                        </td>
                        <td class="px-4 py-4">
                            @php
                                $pointsRetires = $eleve->rapports->sum('points_retires');
                                $score = max(0, 100 - $pointsRetires);
                            @endphp
                            <div class="flex flex-col items-start">
                                <span class="text-2xl font-bold {{ $score >= 80 ? 'text-green-600' : ($score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $score }}
                                </span>
                                <span class="text-xs text-gray-500 mt-1">نقطة</span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            @php
                                $rapportsCount = $eleve->rapports->count();
                            @endphp
                            <div class="flex flex-col items-center">
                                <span class="text-lg font-semibold {{ $rapportsCount == 0 ? 'text-gray-600' : ($rapportsCount <= 3 ? 'text-blue-600' : ($rapportsCount <= 6 ? 'text-orange-600' : 'text-red-600')) }}">
                                    {{ $rapportsCount }}
                                </span>
                                <span class="text-xs text-gray-500">تقرير</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <!-- Vue simplifiée pour les admins et root -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">الاسم الكامل</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">الصف</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">النتيجة</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="elevesTableBody">
                    @foreach($eleves as $eleve)
                    <tr data-url="{{ route('eleve.show', $eleve->id) }}" class="hover:bg-gray-50 cursor-pointer transition duration-200 eleve-row" 
                        data-eleve-id="{{ $eleve->id }}">
                        <td class="px-4 py-4">
                            <div class="text-sm font-semibold text-gray-900">
                                {{ $eleve->nom_ar }} {{ $eleve->prenom_ar }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">{{ $eleve->code_massar }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm text-gray-900">{{ $eleve->classe }}</div>
                        </td>
                        <td class="px-4 py-4">
                            @php
                                $pointsRetires = $eleve->rapports->sum('points_retires');
                                $score = max(0, 100 - $pointsRetires);
                                $rapportsCount = $eleve->rapports->count();
                                $conseilsCount = $eleve->conseils->count();
                                $conseilsOuverts = $eleve->conseils->where('statut', 'ouvert')->count();
                            @endphp
                            <div class="flex flex-col items-start space-y-1">
                                <span class="text-xl font-bold {{ $score >= 80 ? 'text-green-600' : ($score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $score }}
                                </span>
                                <div class="flex gap-2 text-xs">
                                    <span class="bg-blue-100 text-blue-800 px-1 rounded">{{ $rapportsCount }} تقرير</span>
                                    @if($conseilsCount > 0)
                                    <span class="bg-purple-100 text-purple-800 px-1 rounded">{{ $conseilsCount }} مجلس</span>
                                    @endif
                                    @if($conseilsOuverts > 0)
                                    <span class="bg-red-100 text-red-800 px-1 rounded">{{ $conseilsOuverts }} مفتوح</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col gap-2">
                                @if(auth()->user()->isMotasarrif())
                                <a href="{{ route('decisions.create', $eleve->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg transition duration-200 flex items-center justify-center gap-1 whitespace-nowrap"
                                title="اتخاذ إجراء إداري">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    اتخاذ إجراء إداري
                                </a>
                                @else
                                <a href="{{ route('conseils.create', $eleve->id) }}" 
                                   class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded text-xs font-medium transition duration-200 flex items-center justify-center gap-1 whitespace-nowrap"
                                   title="عقد مجلس تأديبي">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    مجلس
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <script>
        // Gestion du clic sur les lignes des élèves
        document.addEventListener('DOMContentLoaded', function() {
            const eleveRows = document.querySelectorAll('.eleve-row');
            
            eleveRows.forEach(row => {
                row.addEventListener('click', function(e) {
                    // Empêcher la redirection si on clique sur un bouton d'action
                    if (e.target.closest('a') || e.target.closest('button')) {
                        return;
                    }
                    
                    const eleveUrl = this.getAttribute('data-url');
                    if (eleveUrl) {
                        window.location.href = eleveUrl;
                    }
                });
            });
        });

        // Script de recherche
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.trim();
            const resultsDiv = document.getElementById('searchResults');
            const searchUrl = this.getAttribute('data-search-url');
            
            if (searchTerm.length < 2) {
                resultsDiv.classList.add('hidden');
                return;
            }

            resultsDiv.innerHTML = '<div class="p-4 text-center text-gray-500">جاري البحث...</div>';
            resultsDiv.classList.remove('hidden');

            fetch(`${searchUrl}?search=${encodeURIComponent(searchTerm)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                resultsDiv.innerHTML = '';
                
                if (data.length === 0) {
                    resultsDiv.innerHTML = '<div class="p-4 text-center text-gray-500">لا توجد نتائج</div>';
                    return;
                }

                data.forEach(eleve => {
                    const div = document.createElement('div');
                    div.className = 'p-3 border-b hover:bg-blue-50 cursor-pointer transition duration-200';
                    
                    let scoreColor = 'text-gray-600';
                    let scoreBg = 'bg-gray-100';
                    if (eleve.score_calcule >= 80) {
                        scoreColor = 'text-green-800';
                        scoreBg = 'bg-green-100';
                    } else if (eleve.score_calcule >= 60) {
                        scoreColor = 'text-yellow-800';
                        scoreBg = 'bg-yellow-100';
                    } else {
                        scoreColor = 'text-red-800';
                        scoreBg = 'bg-red-100';
                    }

                    div.innerHTML = `
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="font-bold text-lg">${eleve.nom_ar} ${eleve.prenom_ar}</div>
                                <div class="text-sm text-gray-600">${eleve.code_massar} - ${eleve.classe}</div>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs ${scoreBg} ${scoreColor}">
                                ${eleve.score_calcule}
                            </span>
                        </div>
                    `;
                    div.addEventListener('click', function() {
                        window.location.href = `/eleve/${eleve.id}`;
                    });
                    resultsDiv.appendChild(div);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                resultsDiv.innerHTML = '<div class="p-4 text-center text-red-500">خطأ في البحث</div>';
            });
        });

        document.addEventListener('click', function(event) {
            const searchContainer = document.querySelector('.bg-white.p-4.rounded-lg.shadow.mb-6');
            const resultsDiv = document.getElementById('searchResults');
            
            if (!searchContainer.contains(event.target)) {
                resultsDiv.classList.add('hidden');
            }
        });
    </script>

    <style>
        /* Styles pour améliorer l'affichage mobile */
        @media (max-width: 768px) {
            .eleve-row td {
                padding: 12px 8px;
            }
            
            .eleve-row .text-2xl {
                font-size: 1.5rem;
            }
            
            .eleve-row .text-xl {
                font-size: 1.25rem;
            }
            
            .eleve-row .text-lg {
                font-size: 1.125rem;
            }
            
            /* Réduire la taille des boutons sur mobile */
            .eleve-row a {
                padding: 8px 12px;
                font-size: 0.7rem;
            }
            
            .eleve-row .flex.gap-2 {
                gap: 4px;
            }
        }
        
        @media (max-width: 480px) {
            .eleve-row td {
                padding: 8px 4px;
            }
            
            .eleve-row .text-xl {
                font-size: 1.125rem;
            }
            
            .eleve-row a {
                padding: 6px 8px;
                font-size: 0.65rem;
            }
            
            .eleve-row .flex.gap-2.text-xs {
                flex-direction: column;
                gap: 2px;
            }
        }
        
        /* Amélioration de l'expérience tactile sur mobile */
        .eleve-row {
            -webkit-tap-highlight-color: transparent;
        }
        
        .eleve-row:active {
            background-color: #f3f4f6;
        }
        
        /* Empêcher la sélection de texte sur les boutons */
        .eleve-row a {
            user-select: none;
            -webkit-user-select: none;
        }
    </style>
@endsection