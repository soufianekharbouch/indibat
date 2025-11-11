<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنضباط - نظام إدارة الانضباط</title>
    <script src="https://cdn.tailwindcss.com"></script>
      <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Tajawal', serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="min-h-screen flex flex-col items-center justify-center py-8 px-4">
        
        <!-- Logo en bas centré -->
        <div class="mb-10 text-center">
            <img src="{{ asset('assets/logo.png') }}" 
                 alt="شعار المؤسسة" 
                 class="bg-transparent mx-auto w-4/5 max-w-[220px] h-auto">
        </div>

        <!-- Logo et Header -->
        <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">نظام إدارة الانضباط المدرسي</h3>
        </div>
        
        <!-- Bouton de connexion -->
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md mb-12">
            <a href="{{ route('login') }}" 
               class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-medium text-white bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-900 hover:to-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700 transition duration-300">
               تسجيل الدخول
            </a>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 w-full max-w-4xl">
            <div class="bg-white p-6 rounded-lg shadow text-center transform hover:scale-105 transition duration-300">
                <div class="text-3xl font-bold text-blue-600">--</div>
                <div class="text-gray-600 text-sm mt-2">تقرير مكتمل</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow text-center transform hover:scale-105 transition duration-300">
                <div class="text-3xl font-bold text-green-600">--</div>
                <div class="text-gray-600 text-sm mt-2">تحسن في الانضباط</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow text-center transform hover:scale-105 transition duration-300">
                <div class="text-3xl font-bold text-yellow-600">--</div>
                <div class="text-gray-600 text-sm mt-2">قرارات متخذة</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow text-center transform hover:scale-105 transition duration-300">
                <div class="text-3xl font-bold text-red-600">--</div>
                <div class="text-gray-600 text-sm mt-2">حالات مستعصية</div>
            </div>
        </div>
    </div>
</body>
</html>