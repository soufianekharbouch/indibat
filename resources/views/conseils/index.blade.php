@extends('layouts.app')

@section('title', 'لوحة التحكم - إنضباط')

@section('content')
    <div class="container mx-auto p-4">
        @if(auth()->user()->isProf())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <span class="text-yellow-800 font-bold">
                    لديك {{ $conseils->where('statut', 'ouvert')->where('prof_a_repondu', false)->count() }} مجلس انضباط بانتظار رأيك
                </span>
            </div>
        </div>
        @endif

        @if(auth()->user()->isAdmin() || auth()->user()->isRoot())
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-blue-800 font-bold">
                        إدارة جميع مجالس الانضباط
                    </span>
                </div>
                <div class="text-sm text-blue-600">
                    <span class="bg-blue-100 px-2 py-1 rounded">مفتوح: {{ $conseils->where('statut', 'ouvert')->count() }}</span>
                    <span class="bg-gray-100 px-2 py-1 rounded mr-2">مغلق: {{ $conseils->where('statut', 'ferme')->count() }}</span>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b">
                <h2 class="text-lg font-bold">قائمة مجالس الانضباط</h2>
            </div>
            
            @if(auth()->user()->isProf())
            <!-- Vue simplifiée pour les professeurs -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">الطالب</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">الحالة</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">الردود</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">تاريخ الإنشاء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($conseils as $conseil)
                        <tr data_url="{{ route('conseils.show', $conseil->id) }}" 
                            class="hover:bg-gray-50 cursor-pointer transition duration-200 conseil-row"
                            data-conseil-id="{{ $conseil->id }}">
                            <td class="px-4 py-4">
                                <div class="font-bold text-gray-900">{{ $conseil->eleve->nom_ar }} {{ $conseil->eleve->prenom_ar }}</div>
                                <div class="text-sm text-gray-600 mt-1">{{ $conseil->eleve->classe }}</div>
                                <div class="text-xs text-gray-500">{{ $conseil->eleve->code_massar }}</div>
                            </td>
                            <td class="px-4 py-4">
                                @if($conseil->statut === 'ouvert')
                                    <div class="flex flex-col items-start space-y-1">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                                            مفتوح
                                        </span>
                                        @if($conseil->est_en_retard)
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">
                                            متأخر
                                        </span>
                                        @endif
                                        @if(!$conseil->prof_a_repondu)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                            ⚡ بانتظار رأيك
                                        </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                        مغلق
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-col items-center">
                                    <span class="text-lg font-bold {{ $conseil->nombre_reponses == $conseil->total_profs ? 'text-green-600' : ($conseil->nombre_reponses > 0 ? 'text-blue-600' : 'text-gray-600') }}">
                                        {{ $conseil->nombre_reponses }}/{{ $conseil->total_profs }}
                                    </span>
                                    <span class="text-xs text-gray-500 mt-1">إجابة</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900">{{ $conseil->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $conseil->created_at->format('H:i') }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <!-- Vue complète pour les admins et root -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">الطالب</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">الحالة</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">الردود</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">تاريخ الإنشاء</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">أنشئ بواسطة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($conseils as $conseil)
                        <tr data_url="{{ route('conseils.show', $conseil->id) }}" 
                            class="hover:bg-gray-50 cursor-pointer transition duration-200 conseil-row"
                            data-conseil-id="{{ $conseil->id }}">
                            <td class="px-4 py-4">
                                <div class="font-bold text-gray-900">{{ $conseil->eleve->nom_ar }} {{ $conseil->eleve->prenom_ar }}</div>
                                <div class="text-sm text-gray-600 mt-1">{{ $conseil->eleve->classe }}</div>
                                <div class="text-xs text-gray-500">{{ $conseil->eleve->code_massar }}</div>
                            </td>
                            <td class="px-4 py-4">
                                @if($conseil->statut === 'ouvert')
                                    <div class="flex flex-col items-start space-y-1">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                                            مفتوح
                                        </span>
                                        @if($conseil->est_en_retard)
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">
                                            متأخر
                                        </span>
                                        @endif
                                        <span class="text-xs text-gray-600">
                                            {{ $conseil->jours_restants }} يوم متبقي
                                        </span>
                                    </div>
                                @else
                                    <div class="flex flex-col items-start">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                            مغلق
                                        </span>
                                        <span class="text-xs text-gray-500 mt-1">
                                            {{ $conseil->updated_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-col items-center">
                                    <span class="text-lg font-bold {{ $conseil->nombre_reponses == $conseil->total_profs ? 'text-green-600' : ($conseil->nombre_reponses > 0 ? 'text-blue-600' : 'text-red-600') }}">
                                        {{ $conseil->nombre_reponses }}/{{ $conseil->total_profs }}
                                    </span>
                                    <span class="text-xs text-gray-500 mt-1">إجابة</span>
                                    @if($conseil->statut === 'ouvert')
                                    <span class="text-xs {{ $conseil->nombre_reponses == $conseil->total_profs ? 'text-green-600' : 'text-orange-600' }} font-bold mt-1">
                                        {{ $conseil->total_profs - $conseil->nombre_reponses }} متبقي
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900">{{ $conseil->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $conseil->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900">{{ $conseil->admin->prenom }} {{ $conseil->admin->nom }}</div>
                                <div class="text-xs text-gray-500">{{ $conseil->admin->matiere }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Gestion du clic sur les lignes des conseils
        document.addEventListener('DOMContentLoaded', function() {
            const conseilRows = document.querySelectorAll('.conseil-row');
            
            conseilRows.forEach(row => {
                row.addEventListener('click', function() {
                    const conseilUrl = this.getAttribute('data_url');
                    window.location.href = conseilUrl;
                });
            });
        });
    </script>

    <style>
        /* Styles pour améliorer l'affichage mobile */
        @media (max-width: 768px) {
            .conseil-row td {
                padding: 12px 8px;
            }
            
            .conseil-row .text-lg {
                font-size: 1.125rem;
            }
        }
        
        /* Amélioration de l'expérience tactile sur mobile */
        .conseil-row {
            -webkit-tap-highlight-color: transparent;
        }
        
        .conseil-row:active {
            background-color: #f3f4f6;
        }
    </style>
@endsection