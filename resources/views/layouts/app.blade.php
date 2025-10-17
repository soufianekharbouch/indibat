<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'إنضباط')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4 space-x-reverse">
                <!-- Bouton du menu drawer -->
                <button id="menuButton" class="p-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <div class="flex items-center space-x-4 space-x-reverse">
                <!-- Notification des conseils -->
                @if(auth()->user()->isProf())
                    @php
                        $conseilsEnAttente = \App\Models\Conseil::whereHas('profs', function($query) {
                            $query->where('prof_id', auth()->id())->where('a_repondu', false);
                        })->where('statut', 'ouvert')->count();
                    @endphp
                    @if($conseilsEnAttente > 0)
                    <a href="{{ route('conseils.index') }}" class="bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded relative">
                        مجالس الانضباط
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">
                            {{ $conseilsEnAttente }}
                        </span>
                    </a>
                    @else
                    <a href="{{ route('conseils.index') }}" class="bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded">
                        مجالس الانضباط
                    </a>
                    @endif
                @endif
                
                <span>مرحبا, {{ auth()->user()->prenom }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded">تسجيل الخروج</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Drawer Menu -->
    <div id="drawerMenu" class="fixed inset-y-0 right-0 w-80 bg-white shadow-xl transform translate-x-full transition-transform duration-300 z-50">
        <div class="p-6">
            <!-- En-tête du menu -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-bold text-gray-800">القائمة</h2>
                <button id="closeMenu" class="p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Contenu du menu -->
            <div class="space-y-4">
                @if(auth()->user()->isProf())
                    <!-- Menu pour les professeurs -->
                    @php
                        $mesRapportsCount = \App\Models\Rapport::where('prof_id', auth()->id())->count();
                        $mesConseilsCount = \App\Models\Conseil::whereHas('profs', function($query) {
                            $query->where('prof_id', auth()->id());
                        })->count();
                    @endphp
                    
                    <a href="{{ route('mes-rapports') }}" class="flex items-center justify-between p-4 rounded-lg bg-blue-50 hover:bg-blue-100 transition duration-200">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-gray-800">تقاريري</span>
                        </div>
                        <span class="bg-blue-600 text-white px-2 py-1 rounded-full text-sm">{{ $mesRapportsCount }}</span>
                    </a>

                    <a href="{{ route('mes-conseils') }}" class="flex items-center justify-between p-4 rounded-lg bg-purple-50 hover:bg-purple-100 transition duration-200">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-gray-800">مجالسي</span>
                        </div>
                        <span class="bg-purple-600 text-white px-2 py-1 rounded-full text-sm">{{ $mesConseilsCount }}</span>
                    </a>

                    <a href="{{ route('statistiques') }}" class="flex items-center justify-between p-4 rounded-lg bg-green-50 hover:bg-green-100 transition duration-200">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium text-gray-800">الإحصائيات</span>
                        </div>
                    </a>

                @elseif(auth()->user()->isAdmin() || auth()->user()->isRoot())
                    <!-- Menu pour les administrateurs -->
                    @php
                        $mesRapportsCount = \App\Models\Rapport::where('prof_id', auth()->id())->count();
                        $mesConseilsCount = \App\Models\Conseil::where('admin_id', auth()->id())->count();
                        $totalProfs = \App\Models\User::where('role', 'prof')->count();
                    @endphp

                    <a href="{{ route('mes-rapports') }}" class="flex items-center justify-between p-4 rounded-lg bg-blue-50 hover:bg-blue-100 transition duration-200">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-gray-800">تقاريري</span>
                        </div>
                        <span class="bg-blue-600 text-white px-2 py-1 rounded-full text-sm">{{ $mesRapportsCount }}</span>
                    </a>

                    <a href="{{ route('mes-conseils') }}" class="flex items-center justify-between p-4 rounded-lg bg-purple-50 hover:bg-purple-100 transition duration-200">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-gray-800">مجالسي</span>
                        </div>
                        <span class="bg-purple-600 text-white px-2 py-1 rounded-full text-sm">{{ $mesConseilsCount }}</span>
                    </a>

                    <a href="{{ route('liste-profs') }}" class="flex items-center justify-between p-4 rounded-lg bg-orange-50 hover:bg-orange-100 transition duration-200">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span class="font-medium text-gray-800">قائمة الأساتذة</span>
                        </div>
                        <span class="bg-orange-600 text-white px-2 py-1 rounded-full text-sm">{{ $totalProfs }}</span>
                    </a>

                    <a href="{{ route('statistiques') }}" class="flex items-center justify-between p-4 rounded-lg bg-green-50 hover:bg-green-100 transition duration-200">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium text-gray-800">الإحصائيات</span>
                        </div>
                    </a>
                @endif

                <!-- Liens communs -->
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 space-x-reverse p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition duration-200">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium text-gray-800">لوحة التحكم</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

    <!-- Contenu principal -->
    <main class="container mx-auto p-4">
        @yield('content')
    </main>

    <!-- Script pour le menu drawer -->
    <script>
        const menuButton = document.getElementById('menuButton');
        const closeMenu = document.getElementById('closeMenu');
        const drawerMenu = document.getElementById('drawerMenu');
        const overlay = document.getElementById('overlay');

        function openMenu() {
            drawerMenu.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeMenuFunc() {
            drawerMenu.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        }

        menuButton.addEventListener('click', openMenu);
        closeMenu.addEventListener('click', closeMenuFunc);
        overlay.addEventListener('click', closeMenuFunc);

        // Fermer le menu avec la touche Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeMenuFunc();
            }
        });
    </script>
</body>
</html>