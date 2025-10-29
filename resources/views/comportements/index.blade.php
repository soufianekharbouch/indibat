@extends('layouts.app')

@section('title', 'إدارة السلوكيات')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">إدارة السلوكيات</h1>
        <div class="flex gap-3">
            <a href="{{ route('comportements.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                إضافة سلوك جديد
            </a>
            <form action="{{ route('comportements.recalculer') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg" 
                        onclick="return confirm('هل تريد إعادة حساب نقاط جميع التقارير؟')">
                    إعادة حساب النقاط
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السلوك (بالفرنسية)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السلوك (بالعربية)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النقاط المخصومة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفئة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($comportements as $comportement)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $comportement->nom_fr }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $comportement->nom_ar }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-bold">
                            -{{ number_format($comportement->points_retires, 2) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($comportement->categorie == 'leger')
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">خفيف</span>
                        @elseif($comportement->categorie == 'moyen')
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">متوسط</span>
                        @else
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">خطير</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('comportements.edit', $comportement->id) }}" class="text-blue-600 hover:text-blue-900 ml-4">تعديل</a>
                        <form action="{{ route('comportements.destroy', $comportement->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4" 
                                    onclick="return confirm('هل أنت متأكد من حذف هذا السلوك؟')">حذف</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection