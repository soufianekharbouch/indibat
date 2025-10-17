<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>غير مسموح - إنضباط</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">إنشاء تقرير</h1>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 px-3 py-1 rounded">رجوع</a>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                
                <h2 class="text-2xl font-bold text-red-600 mb-4">غير مسموح بإنشاء تقرير</h2>
                <p class="text-lg text-gray-700 mb-2">لا يمكنك إنشاء تقرير للطالب:</p>
                <p class="text-xl font-bold text-gray-900 mb-6">{{ $eleve->nom_ar }} {{ $eleve->prenom_ar }}</p>

                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    @if($constraints['recent_rapport_exists'])
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-red-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-700">
                            <strong>قيود الوقت:</strong> لقد قمت بإنشاء تقرير لهذا الطالب منذ 
                            {{ $constraints['days_since_last_rapport'] }} يوم
                        </p>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-blue-700">
                            <strong>الموعد المقبل:</strong> يمكنك إنشاء التقرير التالي في 
                            <strong>{{ $nextAllowedDate }}</strong>
                            (بعد {{ $daysRemaining }} يوم)
                        </p>
                    </div>
                    @endif

                    @if($constraints['max_rapports_reached'])
                    <div class="flex items-center mt-3">
                        <svg class="w-5 h-5 text-red-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p class="text-red-700">
                            <strong>قيود العدد:</strong> لقد وصلت إلى الحد الأقصى للمسموح به من التقارير لهذا الطالب
                        </p>
                    </div>
                    <div class="mt-2">
                        <p class="text-gray-700">
                            عدد التقارير الحالية: <strong>{{ $constraints['total_rapports'] }}/10</strong>
                        </p>
                    </div>
                    @endif
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h3 class="font-bold text-yellow-800 mb-2">قواعد إنشاء التقارير:</h3>
                    <ul class="text-right text-yellow-700 list-disc pr-4 space-y-1">
                        <li>يجب مرور 7 أيام على الأقل بين تقريرين لنفس الطالب</li>
                        <li>الحد الأقصى للمسموح به هو 10 تقارير لكل طالب</li>
                        <li>يتم احتساب المدة من تاريخ إنشاء التقرير السابق</li>
                    </ul>
                </div>

                <div class="flex justify-center space-x-4 space-x-reverse">
                    <a href="{{ route('dashboard') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-300">
                       العودة للقائمة
                    </a>
                    <a href="{{ route('eleve.show', $eleve->id) }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-300">
                       عرض ملف الطالب
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>