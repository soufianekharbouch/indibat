<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل المجلس - إنضباط</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">تفاصيل مجلس الانضباط</h1>
            <div class="space-x-2 space-x-reverse">
                <a href="{{ route('conseils.index') }}" class="bg-gray-500 hover:bg-gray-600 px-3 py-1 rounded">قائمة المجالس</a>
                <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded">لوحة التحكم</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <!-- Message de confirmation après soumission d'avis -->
        @if(session('avis_soumis'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow">
            <div class="flex items-center">
                <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="font-bold text-lg">شكراً لك على مشاركتك!</h3>
                    <p class="text-sm">تم استلام رأيك بنجاح وسيتم أخذه بعين الاعتبار من قبل الإدارة.</p>
                    <p class="text-xs mt-1">نقدر لك مساهمتك القيمة في تحسين الانضباط المدرسي.</p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- معلومات المجلس -->
            <div class="lg:col-span-2 space-y-6">
                <!-- معلومات أساسية -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-bold mb-4">معلومات المجلس</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-bold text-gray-700">الطالب:</label>
                            <p class="text-lg">{{ $conseil->eleve->nom_ar }} {{ $conseil->eleve->prenom_ar }}</p>
                            <p class="text-gray-600">{{ $conseil->eleve->classe }} - {{ $conseil->eleve->code_massar }}</p>
                        </div>
                        
                        <div>
                            <label class="font-bold text-gray-700">الحالة:</label>
                            <span class="px-3 py-1 rounded-full text-sm 
                                {{ $conseil->statut === 'ouvert' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $conseil->statut === 'ouvert' ? 'مفتوح' : 'مغلق' }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="font-bold text-gray-700">دواعي عقد المجلس:</label>
                            <p class="mt-1 p-2 bg-gray-50 rounded">{{ $conseil->raison_principale }}</p>
                        </div>
                        
                        <div>
                            <label class="font-bold text-gray-700">أنشئ بواسطة:</label>
                            <p>{{ $conseil->admin->prenom }} {{ $conseil->admin->nom }}</p>
                            <p class="text-sm text-gray-600">{{ $conseil->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        @if($conseil->date_fermeture)
                        <div>
                            <label class="font-bold text-gray-700">تاريخ الإغلاق المحدد:</label>
                            <p class="{{ $conseil->est_en_retard ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                {{ $conseil->date_fermeture->format('d/m/Y') }}
                                @if($conseil->est_en_retard)
                                <span class="text-red-500">(متأخر)</span>
                                @endif
                            </p>
                        </div>
                        @endif

                        <div>
                            <label class="font-bold text-gray-700">الردود المستلمة:</label>
                            <p class="text-lg font-bold {{ $conseil->nombre_reponses > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $conseil->nombre_reponses }}/{{ $conseil->total_profs }}
                            </p>
                        </div>
                    </div>

                    @if($conseil->description)
                    <div class="mt-4">
                        <label class="font-bold text-gray-700">الوصف التفصيلي:</label>
                        <p class="mt-1 p-3 bg-gray-50 rounded border border-gray-200">{{ $conseil->description }}</p>
                    </div>
                    @endif
                </div>

                <!-- آراء الأساتذة -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-bold mb-4">آراء الأساتذة 
                        <span class="text-sm font-normal text-gray-600">
                            ({{ $conseil->nombre_reponses }}/{{ $conseil->total_profs }})
                        </span>
                    </h2>
                    
                    @php
                        $user = auth()->user();
                        $userARepondu = $user->isProf() ? $conseil->profARepondu($user->id) : true;
                    @endphp

                    @if(auth()->user()->isAdmin() || auth()->user()->isRoot() || $userARepondu)
                        <!-- Afficher les avis pour les admins OU les profs qui ont déjà répondu -->
                        @if($conseil->reponses->where('a_repondu', true)->count() > 0)
                        <div class="space-y-4">
                            @foreach($conseil->reponses->where('a_repondu', true) as $reponse)
                            <div class="border-l-4 {{ $reponse->prof_id === auth()->id() ? 'border-purple-500 bg-purple-50' : 'border-blue-500 bg-blue-50' }} pl-4 p-4 rounded">
                                @if(auth()->user()->isAdmin() || auth()->user()->isRoot())
                                <!-- Pour les admins: afficher tous les détails -->
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-bold text-lg">
                                            {{ $reponse->prof->prenom }} {{ $reponse->prof->nom }}
                                            @if($reponse->prof_id === auth()->id())
                                            <span class="text-purple-600 text-sm">(أنت)</span>
                                            @endif
                                        </h4>
                                        <p class="text-sm text-gray-600">{{ $reponse->prof->matiere }}</p>
                                    </div>
                                    <span class="text-sm text-gray-500 bg-white px-2 py-1 rounded">
                                        {{ $reponse->repondu_le->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                @else
                                <!-- Pour les profs qui ont répondu -->
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        @if($reponse->prof_id === auth()->id())
                                        <h4 class="font-bold text-lg text-purple-700">رأيي</h4>
                                        @else
                                        <h4 class="font-bold text-lg text-gray-700"></h4>
                                        @endif
                                    </div>
                                    <span class="text-sm text-gray-500 bg-white px-2 py-1 rounded">
                                        {{ $reponse->repondu_le->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                @endif
                                <div class="mt-3">
                                    <p class="font-bold text-green-700 text-lg mb-2">الاقتراح:</p>
                                    <p class="p-2 bg-green-50 rounded border border-green-200 mb-3">{{ $reponse->avis }}</p>
                                    
                                    <p class="font-bold text-blue-700 mb-2">التبرير:</p>
                                    <p class="p-2 bg-white rounded border border-gray-200">{{ $reponse->justification }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-500 text-lg">لا توجد آراء حتى الآن</p>
                            <p class="text-gray-400 text-sm mt-2">لم يقم أي أستاذ بإبداء رأيه بعد</p>
                        </div>
                        @endif

                    @elseif(auth()->user()->isProf() && $conseil->profs->contains(auth()->id()) && !$userARepondu)
                        <!-- Pour les profs concernés qui n'ont pas encore répondu -->
                        <div class="text-center py-8 bg-yellow-50 rounded-lg border border-yellow-200">
                            <svg class="w-16 h-16 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <h3 class="text-xl font-bold text-yellow-700 mb-3">رأيك يهمنا!</h3>
                            <p class="text-yellow-600 mb-4">
                                تم تقديم <span class="font-bold">{{ $conseil->nombre_reponses }}</span> رأي حتى الآن من أصل <span class="font-bold">{{ $conseil->total_profs }}</span> أستاذ معني
                            </p>
                            <p class="text-gray-700 mb-6">
                                نرحب برأيك الشخصي والمستقل في هذه القضية. 
                                <br>يرجى إبداء رأيك أولاً لمشاهدة آراء الزملاء والحفاظ على استقلالية قرارك.
                            </p>
                            <div class="bg-white p-4 rounded-lg border border-yellow-300 mb-4">
                                <p class="text-sm text-gray-600">
                                    <strong>ملاحظة:</strong> سيتم إخفاء هوية جميع الآراء للحفاظ على السرية والموضوعية.
                                </p>
                            </div>
                            <a href="#donner-avis" 
                               class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 inline-block">
                               إبداء رأيي الآن
                            </a>
                        </div>
                    @endif
                </div>

                <!-- معلومات إضافية عن الطالب -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-bold mb-4">معلومات عن الطالب</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-bold text-gray-700">نتيجة الانضباط الحالية:</label>
                            @php
                                $pointsRetires = $conseil->eleve->rapports->sum('points_retires');
                                $score = max(0, 100 - $pointsRetires);
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm 
                                {{ $score >= 80 ? 'bg-green-100 text-green-800' : 
                                   ($score >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $score }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="font-bold text-gray-700">عدد التقارير:</label>
                            <p>{{ $conseil->eleve->rapports->count() }} تقرير</p>
                        </div>
                        
                        <div>
                            <label class="font-bold text-gray-700">آخر تقرير:</label>
                            @if($conseil->eleve->rapports->count() > 0)
                                @php
                                    $lastRapport = $conseil->eleve->rapports->sortByDesc('created_at')->first();
                                @endphp
                                <p class="text-sm">{{ $lastRapport->created_at->format('d/m/Y') }}</p>
                            @else
                                <p class="text-sm text-gray-500">لا توجد تقارير</p>
                            @endif
                        </div>
                        
                        <div>
                            <a href="{{ route('eleve.show', $conseil->eleve->id) }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-block">
                               عرض الملف الكامل للطالب
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الجانب الأيمن -->
            <div class="space-y-6">
                @if($conseil->statut === 'ouvert')
                    <!-- نموذج إبداء الرأي للأساتذة -->
                    @if(auth()->user()->isProf() && $conseil->profs->contains(auth()->id()) && !$conseil->profARepondu(auth()->id()))
                    <div id="donner-avis" class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-bold mb-4 text-green-600">إبداء رأيك</h3>
                        <form method="POST" action="{{ route('conseils.donner-avis', $conseil->id) }}" id="avisForm">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">الاقتراح المناسب</label>
                                <select name="avis" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">اختر الاقتراح المناسب</option>
                                    @foreach($avisPossibles as $avis => $niveau)
                                    <option value="{{ $avis }}">{{ $avis }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">الاقتراحات مرتبة من الأخف إلى الأقسى</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">التبرير والملاحظات</label>
                                <textarea name="justification" required rows="4" 
                                          class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                          placeholder="أضف تبريرك للاقتراح المختار وأي ملاحظات إضافية..."></textarea>
                            </div>
                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                </svg>
                                إرسال الرأي
                            </button>
                        </form>
                        
                        <!-- Message de confirmation caché -->
                        <div id="confirmationMessage" class="hidden mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-bold text-green-800">شكراً لك على مشاركتك!</h4>
                                    <p class="text-sm text-green-700">تم استلام رأيك بنجاح وسيتم أخذه بعين الاعتبار من قبل الإدارة.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- إغلاق المجلس للإدارة -->
                    @if((auth()->user()->isAdmin() || auth()->user()->isRoot()) && auth()->user()->id === $conseil->admin_id)
                    <div class="bg-white p-6 rounded-lg shadow border border-red-200">
                        <h3 class="text-lg font-bold mb-4 text-red-600">إغلاق المجلس</h3>
                        <form method="POST" action="{{ route('conseils.fermer', $conseil->id) }}">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">القرار النهائي</label>
                                <textarea name="decision_finale" required rows="3" 
                                          class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                          placeholder="أدخل القرار النهائي للمجلس بناءً على آراء الأساتذة..."></textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                إغلاق المجلس
                            </button>
                        </form>
                    </div>
                    @endif
                @else
                    <!-- معلومات الإغلاق -->
                    <div class="bg-white p-6 rounded-lg shadow border border-green-200">
                        <h3 class="text-lg font-bold mb-4 text-green-600">معلومات الإغلاق</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="font-bold text-gray-700">القرار النهائي:</label>
                                <p class="mt-1 p-3 bg-green-50 rounded border border-green-200">{{ $conseil->decision_finale }}</p>
                            </div>
                            <div class="pt-3 border-t border-gray-200">
                                <p class="text-sm text-gray-500">تم إغلاق المجلس في: {{ $conseil->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- الأساتذة المعنيون -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">
                        @if(auth()->user()->isAdmin() || auth()->user()->isRoot())
                        الأساتذة المعنيون
                        @else
                        الأساتذة المعنيون بالمجلس
                        @endif
                    </h3>
                    <div class="space-y-2 max-h-80 overflow-y-auto">
                        @foreach($conseil->profs as $prof)
                        <div class="flex justify-between items-center p-3 hover:bg-gray-50 rounded border border-gray-100">
                            <div>
                                <span class="font-medium">{{ $prof->prenom }} {{ $prof->nom }}</span>
                                <p class="text-xs text-gray-500">{{ $prof->matiere }}</p>
                            </div>
                            @if(auth()->user()->isAdmin() || auth()->user()->isRoot())
                            <span class="text-sm px-2 py-1 rounded-full {{ $conseil->profARepondu($prof->id) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $conseil->profARepondu($prof->id) ? 'أبدى رأيه' : 'لم يبد رأيه' }}
                            </span>
                            @else
                            <span class="text-sm px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                معني بالمجلس
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    
                    @if(auth()->user()->isAdmin() || auth()->user()->isRoot())
                    <!-- إحصائيات الردود pour les admins seulement -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium">معدل الاستجابة:</span>
                            <span class="text-sm font-bold {{ $conseil->nombre_reponses > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $conseil->total_profs > 0 ? round(($conseil->nombre_reponses / $conseil->total_profs) * 100, 1) : 0 }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-green-600 h-2 rounded-full" 
                                 style="width: {{ $conseil->total_profs > 0 ? ($conseil->nombre_reponses / $conseil->total_profs) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                    @else
                    <!-- Information générale pour les profs -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600 text-center">
                            تمت دعوة {{ $conseil->total_profs }} أستاذ للمشاركة في هذا المجلس
                        </p>
                        <p class="text-xs text-gray-500 text-center mt-1">
                            {{ $conseil->nombre_reponses }} رأي مقدم حتى الآن
                        </p>
                    </div>
                    @endif
                </div>

                <!-- إجراءات سريعة -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">إجراءات سريعة</h3>
                    <div class="space-y-2">
                        <a href="{{ route('eleve.show', $conseil->eleve->id) }}" 
                           class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-center block transition duration-300">
                           عرض ملف الطالب
                        </a>
                        <a href="{{ route('conseils.index') }}" 
                           class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-center block transition duration-300">
                           العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gestion de la soumission du formulaire d'avis
        document.addEventListener('DOMContentLoaded', function() {
            const avisForm = document.getElementById('avisForm');
            const confirmationMessage = document.getElementById('confirmationMessage');
            
            if (avisForm) {
                avisForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Afficher le message de confirmation
                    confirmationMessage.classList.remove('hidden');
                    
                    // Soumettre le formulaire après un délai
                    setTimeout(() => {
                        avisForm.submit();
                    }, 3000); // 3 secondes pour lire le message
                });
            }
        });
    </script>
</body>
</html>