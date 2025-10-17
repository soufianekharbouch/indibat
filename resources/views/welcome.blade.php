<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنضباط - نظام إدارة الانضباط</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Noto Naskh Arabic', serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="min-h-screen flex flex-col items-center justify-center py-8">
        
        <!-- Nom de l'école en bas -->
        <div class="mt-16 text-center mb-10 ">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-6 px-12 rounded-2xl shadow-lg">
                <h2 class="text-3xl font-bold mb-2">ثانوية الحسنى الإعدادية</h2>
                <p class="text-xl opacity-90">مراكش</p>
            </div>
        </div>

        <!-- Logo et Header -->
        <div class="text-center mb-8">
            <div class="bg-white rounded-full p-4 shadow-lg inline-block mb-4">
                <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    ⚖️
                </div>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">إنضباط</h1>
            <p class="text-lg text-gray-600">نظام إدارة الانضباط المدرسي</p>
        </div>
        
        <!-- Bouton de connexion -->
        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
            <a href="{{ route('login') }}" 
               class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-lg font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
               تسجيل الدخول
            </a>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12 w-full max-w-4xl">
            <div class="bg-white p-6 rounded-xl shadow text-center transform hover:scale-105 transition duration-300">
                <div class="text-3xl font-bold text-blue-600">1,247</div>
                <div class="text-gray-600 text-sm mt-2">تقرير مكتمل</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow text-center transform hover:scale-105 transition duration-300">
                <div class="text-3xl font-bold text-green-600">89%</div>
                <div class="text-gray-600 text-sm mt-2">تحسن في الانضباط</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow text-center transform hover:scale-105 transition duration-300">
                <div class="text-3xl font-bold text-yellow-600">56</div>
                <div class="text-gray-600 text-sm mt-2">قرارات متخذة</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow text-center transform hover:scale-105 transition duration-300">
                <div class="text-3xl font-bold text-red-600">12</div>
                <div class="text-gray-600 text-sm mt-2">حالات مستعصية</div>
            </div>
        </div>

    </div>
</body>
</html>