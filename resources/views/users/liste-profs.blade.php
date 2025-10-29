@extends('layouts.app')

@section('title', 'قائمة الأساتذة')

@section('content')
<div class="container mx-auto p-4">
    <!-- En-tête -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">قائمة الأساتذة</h1>
        <p class="text-gray-600 text-sm">إدارة وتتبع جميع الأساتذة في النظام</p>
    </div>

    <!-- Statistiques compactes -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs">إجمالي الأساتذة</p>
                    <p class="text-xl font-bold text-blue-600">{{ $profs->count() }}</p>
                </div>
                <div class="bg-blue-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs">التقارير</p>
                    <p class="text-xl font-bold text-green-600">{{ $profs->sum('total_rapports') }}</p>
                </div>
                <div class="bg-green-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs">المجالس</p>
                    <p class="text-xl font-bold text-purple-600">{{ $profs->sum('total_conseils') }}</p>
                </div>
                <div class="bg-purple-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs">الآراء</p>
                    <p class="text-xl font-bold text-orange-600">{{ $profs->sum('total_avis') }}</p>
                </div>
                <div class="bg-orange-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des professeurs -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">الأساتذة المسجلون</h2>
        </div>

        @if($profs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700">الأستاذ</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700">الإحصائيات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($profs as $prof)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-xs font-medium">
                                            {{ substr($prof->prenom, 0, 1) }}{{ substr($prof->nom, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="mr-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $prof->prenom }} {{ $prof->nom }}</div>
                                        <div class="text-xs text-gray-500 truncate max-w-[120px]">{{ $prof->email }}</div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ $prof->role === 'prof' ? 'أستاذ' : ($prof->role === 'admin' ? 'مدير' : 'مشرف') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-gray-600">التقارير:</span>
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">
                                            {{ $prof->total_rapports }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-gray-600">المجالس:</span>
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full font-medium">
                                            {{ $prof->total_conseils }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-gray-600">الآراء:</span>
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full font-medium">
                                            {{ $prof->total_avis }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد أساتذة</h3>
                <p class="mt-1 text-xs text-gray-500">لم يتم إضافة أي أساتذة إلى النظام حتى الآن.</p>
            </div>
        @endif
    </div>
</div>

<style>
    /* Optimisations pour mobile */
    @media (max-width: 768px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        
        table {
            font-size: 0.875rem;
        }
        
        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        
        .max-w-\[120px\] {
            max-width: 120px;
        }
    }
    
    @media (max-width: 480px) {
        .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.5rem;
        }
        
        .p-4 {
            padding: 0.75rem;
        }
        
        .text-xl {
            font-size: 1.125rem;
        }
        
        .px-4 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        .py-4 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        
        .max-w-\[120px\] {
            max-width: 100px;
        }
    }
</style>
@endsection