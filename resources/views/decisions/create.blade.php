@extends('layouts.app')

@section('title', 'اتخاذ إجراء إداري - إنضباط')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-lg font-bold text-gray-800">اتخاذ إجراء إداري</h1>
        <a href="{{ route('eleve.show', $eleve->id) }}" class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded">
            رجوع لملف التلميذ
        </a>
    </div>

    <div class="mb-4">
        <p class="text-gray-700"><span class="font-semibold">التلميذ:</span>
            {{ $eleve->nom_ar }} {{ $eleve->prenom_ar }} ({{ $eleve->classe }})
        </p>
    </div>

    <form method="POST" action="{{ route('decisions.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="eleve_id" value="{{ $eleve->id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">تاريخ الإجراء</label>
                <input type="date" name="decision_date"
                       value="{{ now()->format('Y-m-d') }}"
                       class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">وقت الإجراء</label>
                <input type="time" name="decision_time"
                       value="{{ now()->format('H:i') }}"
                       class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-2">نوع(أنواع) الإجراء</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                @foreach($choices as $c)
                    <label class="flex items-center gap-2 p-2 border rounded-lg hover:bg-gray-50">
                        <input type="checkbox" name="choices[]" value="{{ $c }}" class="w-4 h-4">
                        <span class="text-sm text-gray-800">{{ $c }}</span>
                    </label>
                @endforeach
            </div>
            @error('choices')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">تفاصيل الإجراء</label>
            <textarea name="details" rows="4" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500" placeholder="اكتب وصفًا موجزًا للإجراء المتخذ..."></textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg">
                حفظ الإجراء
            </button>
        </div>
    </form>
</div>
@endsection
