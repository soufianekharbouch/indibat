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
    <nav class="bg-blue-600 text-white p-3">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3 space-x-reverse">
                <!-- Bouton du menu drawer -->
                <button id="menuButton" class="p-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Icône home page vers dashboard -->
                <a href="{{ route('dashboard') }}" class="p-2 rounded-lg hover:bg-blue-700 transition duration-200" title="الصفحة الرئيسية">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </a>
            </div>
            
            <div class="flex items-center space-x-3 space-x-reverse">
                <!-- Notification des conseils -->
                @if(auth()->user()->isProf())
                    @php
                        $conseilsEnAttente = \App\Models\Conseil::whereHas('profs', function($query) {
                            $query->where('prof_id', auth()->id())->where('a_repondu', false);
                        })->where('statut', 'ouvert')->count();
                    @endphp
                    @if($conseilsEnAttente > 0)
                    <a href="{{ route('conseils.index') }}" class="bg-yellow-500 hover:bg-yellow-600 px-2 py-1 rounded relative text-sm">
                        مجالس الانضباط
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-4 h-4 text-xs flex items-center justify-center">
                            {{ $conseilsEnAttente }}
                        </span>
                    </a>
                    @endif
                @endif
                
                <span class="text-sm">مرحبا, {{ auth()->user()->prenom }}</span>
            </div>
        </div>
    </nav>

    <!-- Drawer Menu -->
    <div id="drawerMenu" class="fixed inset-y-0 right-0 w-72 bg-white shadow-xl transform translate-x-full transition-transform duration-300 z-50 flex flex-col">
        <!-- En-tête fixe -->
        <div class="p-4 border-b border-gray-200 flex-shrink-0">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800">القائمة</h2>
                <button id="closeMenu" class="p-1 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Contenu du menu avec défilement -->
        <div class="flex-1 overflow-y-auto">
            <div class="p-3 space-y-2">
                @if(auth()->user()->isProf())
                    <!-- Menu pour les professeurs -->
                    @php
                        $mesRapportsCount = \App\Models\Rapport::where('prof_id', auth()->id())->count();
                        $mesConseilsCount = \App\Models\Conseil::whereHas('profs', function($query) {
                            $query->where('prof_id', auth()->id());
                        })->count();
                    @endphp
                    
                    <a href="{{ route('mes-rapports') }}" class="flex items-center justify-between p-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition duration-200">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-gray-800 text-sm">تقاريري</span>
                        </div>
                        <span class="bg-blue-600 text-white px-1.5 py-0.5 rounded-full text-xs">{{ $mesRapportsCount }}</span>
                    </a>

                    <a href="{{ route('mes-conseils') }}" class="flex items-center justify-between p-3 rounded-lg bg-purple-50 hover:bg-purple-100 transition duration-200">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-gray-800 text-sm">مجالسي</span>
                        </div>
                        <span class="bg-purple-600 text-white px-1.5 py-0.5 rounded-full text-xs">{{ $mesConseilsCount }}</span>
                    </a>

                    <a href="{{ route('statistiques') }}" class="flex items-center p-3 rounded-lg bg-green-50 hover:bg-green-100 transition duration-200">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium text-gray-800 text-sm">الإحصائيات</span>
                        </div>
                    </a>

                @elseif(auth()->user()->isAdmin() || auth()->user()->isRoot())
                    <!-- Menu pour les administrateurs -->
                    @php
                        $totalRapports = \App\Models\Rapport::count();
                        $mesRapportsCount = \App\Models\Rapport::where('prof_id', auth()->id())->count();
                        $mesConseilsCount = \App\Models\Conseil::where('admin_id', auth()->id())->count();
                        $totalProfs = \App\Models\User::where('role', 'prof')->count();
                        $totalConseils = \App\Models\Conseil::count();
                    @endphp

                    <a href="{{ route('mes-rapports') }}" class="flex items-center justify-between p-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition duration-200">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-gray-800 text-sm">جميع التقارير</span>
                        </div>
                        <span class="bg-blue-600 text-white px-1.5 py-0.5 rounded-full text-xs">{{ $totalRapports }}</span>
                    </a>

                    <a href="{{ route('mes-conseils') }}" class="flex items-center justify-between p-3 rounded-lg bg-purple-50 hover:bg-purple-100 transition duration-200">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-gray-800 text-sm">جميع المجالس</span>
                        </div>
                        <span class="bg-purple-600 text-white px-1.5 py-0.5 rounded-full text-xs">{{ $totalConseils }}</span>
                    </a>

                    <a href="{{ route('liste-profs') }}" class="flex items-center justify-between p-3 rounded-lg bg-orange-50 hover:bg-orange-100 transition duration-200">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span class="font-medium text-gray-800 text-sm">قائمة الأساتذة</span>
                        </div>
                        <span class="bg-orange-600 text-white px-1.5 py-0.5 rounded-full text-xs">{{ $totalProfs }}</span>
                    </a>

                    <a href="{{ route('statistiques') }}" class="flex items-center p-3 rounded-lg bg-green-50 hover:bg-green-100 transition duration-200">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium text-gray-800 text-sm">الإحصائيات</span>
                        </div>
                    </a>

                    <!-- Lien pour les rapports personnels de l'admin -->
                    @if($mesRapportsCount > 0)
                    <a href="{{ route('mes-rapports') }}?filter=my" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition duration-200">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium text-gray-800 text-sm">تقاريري الشخصية</span>
                        </div>
                        <span class="bg-gray-600 text-white px-1.5 py-0.5 rounded-full text-xs">{{ $mesRapportsCount }}</span>
                    </a>
                    @endif

                    <!-- Liens pour root seulement -->
                    @if(auth()->user()->isRoot())
                    <a href="{{ route('eleves.upload.form') }}" class="flex items-center p-3 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition duration-200">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span class="font-medium text-gray-800 text-sm">رفع قائمة التلاميذ</span>
                        </div>
                    </a>

                    <a href="{{ route('users.upload.form') }}" class="flex items-center p-3 rounded-lg bg-pink-50 hover:bg-pink-100 transition duration-200">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span class="font-medium text-gray-800 text-sm">رفع قائمة المستخدمين</span>
                        </div>
                    </a>

                    <a href="{{ route('statistiques-visites.index') }}" class="flex items-center p-3 rounded-lg bg-teal-50 hover:bg-teal-100 transition duration-200">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium text-gray-800 text-sm">إحصائيات الزيارات</span>
                        </div>
                    </a>
                    @endif
                @endif

                <!-- Liens communs à tous les utilisateurs -->
                <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition duration-200">
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="font-medium text-gray-800 text-sm">لوحة التحكم</span>
                    </div>
                </a>

                <a href="{{ route('profil.change-password') }}" class="flex items-center p-3 rounded-lg bg-orange-50 hover:bg-orange-100 transition duration-200">
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        <span class="font-medium text-gray-800 text-sm">تغيير كلمة المرور</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Section logout fixe en bas -->
        <div class="p-3 border-t border-gray-200 flex-shrink-0">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="flex items-center justify-center space-x-2 space-x-reverse w-full p-3 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="font-medium text-sm">تسجيل الخروج</span>
                </button>
            </form>
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
            document.body.style.overflow = 'hidden'; // Empêcher le défilement du body
        }

        function closeMenuFunc() {
            drawerMenu.classList.add('translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = ''; // Rétablir le défilement du body
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

        // Empêcher le défilement du contenu principal quand le menu est ouvert
        drawerMenu.addEventListener('wheel', (e) => {
            e.stopPropagation();
        });
    </script>

    <style>
        /* Styles pour améliorer le défilement sur mobile */
        #drawerMenu {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Masquer la scrollbar pour un look plus clean */
        #drawerMenu::-webkit-scrollbar {
            width: 4px;
        }
        
        #drawerMenu::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        #drawerMenu::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }
        
        #drawerMenu::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</body>
</html>