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
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h2 class="text-lg font-bold">قائمة الطلاب</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right">الرمز الماسي</th>
                        <th class="px-6 py-3 text-right">الاسم</th>
                        <th class="px-6 py-3 text-right">الصف</th>
                        <th class="px-6 py-3 text-right">نتيجة الانضباط</th>
                        <th class="px-6 py-3 text-right">التقارير</th>
                        <th class="px-6 py-3 text-right">المجالس</th>
                        <th class="px-6 py-3 text-right">الحالة</th>
                        <th class="px-6 py-3 text-right">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="elevesTableBody">
                    @foreach($eleves as $eleve)
                    <tr>
                        <td class="px-6 py-4">{{ $eleve->code_massar }}</td>
                        <td class="px-6 py-4">{{ $eleve->nom_ar }} {{ $eleve->prenom_ar }}</td>
                        <td class="px-6 py-4">{{ $eleve->classe }}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col items-start space-y-1">
                                @php
                                    $pointsRetires = $eleve->rapports->sum('points_retires');
                                    $score = max(0, 100 - $pointsRetires);
                                    $rapportsCount = $eleve->rapports->count();
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs 
                                    {{ $score >= 80 ? 'bg-green-100 text-green-800' : 
                                    ($score >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $score }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $rapportsCount }} تقرير
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                    {{ $rapportsCount == 0 ? 'bg-gray-100 text-gray-600' : 
                                       ($rapportsCount <= 3 ? 'bg-blue-100 text-blue-600' : 
                                       ($rapportsCount <= 6 ? 'bg-orange-100 text-orange-600' : 'bg-red-100 text-red-600')) }}">
                                    {{ $rapportsCount }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">تقرير</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $conseilsCount = $eleve->conseils->count();
                                $conseilsOuverts = $eleve->conseils->where('statut', 'ouvert')->count();
                            @endphp
                            <div class="text-center">
                                @if($conseilsCount > 0)
                                    <div class="flex flex-col items-center space-y-1">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                            {{ $conseilsOuverts > 0 ? 'bg-purple-100 text-purple-600' : 'bg-green-100 text-green-600' }}">
                                            {{ $conseilsCount }}
                                        </span>
                                        @if($conseilsOuverts > 0)
                                        <span class="text-xs text-purple-600 font-bold">
                                            {{ $conseilsOuverts }} مفتوح
                                        </span>
                                        @else
                                        <span class="text-xs text-green-600">
                                            جميعها مغلق
                                        </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-400">
                                        0
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">لا يوجد</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($eleve->conseil_ouvert && (auth()->user()->isAdmin() || auth()->user()->isRoot()))
                            <div class="flex flex-col items-center space-y-1">
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">
                                    🔔 تحت المجلس
                                </span>
                                <span class="text-xs text-purple-600">
                                    {{ $eleve->conseil_ouvert->nombre_reponses }}/{{ $eleve->conseil_ouvert->total_profs }}
                                </span>
                            </div>
                            @elseif($conseilsOuverts > 0 && (auth()->user()->isAdmin() || auth()->user()->isRoot()))
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                مجلس مفتوح
                            </span>
                            @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">
                                عادي
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if(auth()->user()->isProf())
                                @php
                                    $constraints = \App\Models\Rapport::checkConstraints($eleve->id, auth()->id());
                                @endphp
                                
                                @if($constraints['can_create'])
                                    <a href="{{ route('rapport.create', $eleve->id) }}" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mb-1 block text-center">
                                    إبلاغ
                                    </a>
                                @else
                                    <span class="bg-red-500 text-white px-3 py-1 rounded text-sm cursor-not-allowed block text-center mb-1" 
                                        title="غير مسموح - {{ $constraints['recent_rapport_exists'] ? 'لم تمر 7 أيام' : 'وصل الحد الأقصى' }}">
                                    غير مسموح
                                    </span>
                                @endif
                            @endif
                            
                            @if(auth()->user()->isAdmin() || auth()->user()->isRoot())
                                @if($eleve->conseil_ouvert)
                                <a href="{{ route('conseils.show', $eleve->conseil_ouvert->id) }}" 
                                class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-sm mb-1 block text-center">
                                عرض المجلس
                                </a>
                                @else
                                <a href="{{ route('conseils.create', $eleve->id) }}" 
                                class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-sm mb-1 block text-center">
                                مجلس انضباط
                                </a>
                                @endif
                            @endif
                            
                            <a href="{{ route('eleve.show', $eleve->id) }}" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm block text-center">
                            الملف
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Script de recherche existant...
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
@endsection