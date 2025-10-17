
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

        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
                <h2 class="text-lg font-bold">قائمة مجالس الانضباط</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right">الطالب</th>
                            <th class="px-6 py-3 text-right">الحالة</th>
                            <th class="px-6 py-3 text-right">الردود</th>
                            <th class="px-6 py-3 text-right">تاريخ الإنشاء</th>
                            @if(auth()->user()->isAdmin() || auth()->user()->isRoot())
                            <th class="px-6 py-3 text-right">أنشئ بواسطة</th>
                            @endif
                            <th class="px-6 py-3 text-right">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($conseils as $conseil)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-bold">{{ $conseil->eleve->nom_ar }} {{ $conseil->eleve->prenom_ar }}</div>
                                <div class="text-sm text-gray-600">{{ $conseil->eleve->classe }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($conseil->statut === 'ouvert')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        مفتوح
                                    </span>
                                    @if($conseil->est_en_retard)
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs mt-1 block">
                                        متأخر
                                    </span>
                                    @endif
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                        مغلق
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm">
                                    {{ $conseil->nombre_reponses }}/{{ $conseil->total_profs }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm">{{ $conseil->created_at->format('d/m/Y') }}</span>
                            </td>
                            @if(auth()->user()->isAdmin() || auth()->user()->isRoot())
                            <td class="px-6 py-4">
                                <span class="text-sm">{{ $conseil->admin->prenom }} {{ $conseil->admin->nom }}</span>
                            </td>
                            @endif
                            <td class="px-6 py-4">
                                <a href="{{ route('conseils.show', $conseil->id) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                   عرض
                                </a>
                                @if(auth()->user()->isProf() && $conseil->statut === 'ouvert' && !$conseil->prof_a_repondu)
                                <a href="{{ route('conseils.show', $conseil->id) }}" 
                                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                   إبداء الرأي
                                </a>
                                @endif
                                @if((auth()->user()->isAdmin() || auth()->user()->isRoot()) && $conseil->statut === 'ouvert')
                                <a href="{{ route('conseils.show', $conseil->id) }}" 
                                   class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                   إغلاق
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection