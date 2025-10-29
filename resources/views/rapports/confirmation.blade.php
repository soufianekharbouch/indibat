<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد الإرسال - إنضباط</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-green-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">تم إرسال التقرير بنجاح</h1>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 px-3 py-1 rounded">العودة للرئيسية</a>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <div class="bg-white p-6 rounded-lg shadow">
            <!-- Carte de confirmation -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-green-700 mb-2">تم إرسال التقرير بنجاح</h2>
                <p class="text-gray-600">تم تسجيل التقرير في النظام وسيتم معالجته</p>
            </div>

            <!-- Résumé du rapport -->
            <div class="border border-gray-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800">ملخص التقرير</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-medium text-gray-700">الطالب</h4>
                        <p class="text-gray-900">{{ $rapport->eleve->nom_ar }} {{ $rapport->eleve->prenom_ar }}</p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-700">المادة</h4>
                        <p class="text-gray-900">{{ $rapport->matiere }}</p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-700">تاريخ الحصة</h4>
                        <p class="text-gray-900">{{ $rapport->date_seance }}</p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-700">وقت الحصة</h4>
                        <p class="text-gray-900">{{ $rapport->heure_seance }}</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <h4 class="font-medium text-gray-700">السلوكيات المسجلة</h4>
                        <ul class="list-disc list-inside text-gray-900 mt-2">
                            @foreach($rapport->comportements as $comportement)
                                <li>{{ $comportement }}</li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="md:col-span-2">
                        <h4 class="font-medium text-gray-700">النقاط المخصومة</h4>
                        <p class="text-red-600 font-bold text-xl">{{ number_format($rapport->points_retires, 2) }}</p>
                    </div>
                    
                    @if($rapport->notes_additionnelles)
                    <div class="md:col-span-2">
                        <h4 class="font-medium text-gray-700">الملاحظات الإضافية</h4>
                        <p class="text-gray-900 bg-gray-50 p-3 rounded mt-2">{{ $rapport->notes_additionnelles }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="{{ route('dashboard') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-center transition duration-300">
                   العودة للرئيسية
                </a>
                <a href="{{ route('mes-rapports') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg text-center transition duration-300">
                   عرض جميع تقاريري
                </a>
            </div>

            <!-- Informations supplémentaires -->
            <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="font-medium text-blue-800 mb-2">معلومات مهمة</h4>
                <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                    <li>تم تسجيل التقرير بتاريخ: {{ $rapport->created_at->format('Y-m-d H:i') }}</li>
                    <li>رقم التقرير: #{{ $rapport->id }}</li>
                    <li>يمكنك مراجعة جميع تقاريرك من صفحة "تقاريري"</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>