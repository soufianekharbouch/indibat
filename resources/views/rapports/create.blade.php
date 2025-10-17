<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير جديد - إنضباط</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">تقرير جديد</h1>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 px-3 py-1 rounded">رجوع</a>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">تقرير عن: {{ $eleve->nom_ar }} {{ $eleve->prenom_ar }}</h2>
            
            <form method="POST" action="{{ route('rapport.store') }}">
                @csrf
                <input type="hidden" name="eleve_id" value="{{ $eleve->id }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">تاريخ الحصة</label>
                        <input type="date" name="date_seance" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">وقت الحصة</label>
                        <input type="time" name="heure_seance" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">المادة</label>
                    <input type="text" name="matiere" required 
                           class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                           value="{{ auth()->user()->matiere }}">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">السلوكيات <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach($comportements as $comportement)
                        <label class="flex items-center p-2 border border-gray-200 rounded hover:bg-gray-50">
                            <input type="checkbox" name="comportements[]" value="{{ $comportement->id }}" class="ml-2">
                            <span class="flex-1">{{ $comportement->nom_ar }}</span>
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">-{{ $comportement->points_retires }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('comportements')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">ملاحظات إضافية</label>
                    <textarea name="notes_additionnelles" rows="4" 
                              class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                              placeholder="أضف أي ملاحظات إضافية هنا..."></textarea>
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                    إرسال التقرير
                </button>
            </form>
        </div>
    </div>
</body>
</html>