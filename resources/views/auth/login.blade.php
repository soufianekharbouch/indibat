<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - إنضباط</title>
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
        
        <!-- Logo en haut -->
        <div class="mb-8 text-center">
            <img src="{{ asset('assets/logo.png') }}" 
                 alt="شعار المؤسسة" 
                 class="bg-transparent mx-auto w-4/5 max-w-[220px] h-auto mb-4">
        </div>

        <div class="max-w-md w-full space-y-6">
            <!-- En-tête -->
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">تسجيل الدخول</h1>
                <p class="text-lg text-gray-600">نظام إدارة الانضباط المدرسي</p>
            </div>
            
            <!-- Formulaire -->
            <form class="bg-white p-8 rounded-lg shadow-lg space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <input type="text" name="username" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-700 focus:border-gray-700 transition duration-200"
                               placeholder="اسم المستخدم">
                    </div>
                    <div>
                        <input type="password" name="password" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-700 focus:border-gray-700 transition duration-200"
                               placeholder="كلمة المرور">
                    </div>
                </div>

                @if ($errors->any())
                    <div class="text-red-600 text-sm text-center bg-red-50 py-2 px-3 rounded-lg border border-red-200">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <button type="submit" 
                            class="w-full py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-medium text-white bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-900 hover:to-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700 transition duration-300">
                        تسجيل الدخول
                    </button>
                </div>
            </form>

            <!-- Lien de retour -->
            <div class="text-center">
                <a href="{{ url('/') }}" 
                   class="text-gray-600 hover:text-gray-800 text-sm transition duration-200">
                    ← العودة إلى الصفحة الرئيسية
                </a>
            </div>
        </div>
    </div>
</body>
</html>