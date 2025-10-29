@extends('layouts.app')

@section('title', 'رفع قائمة التلاميذ')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">رفع قائمة التلاميذ</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(session('erreurs_details'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                <h4 class="font-bold mb-2">تفاصيل الأخطاء:</h4>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach(session('erreurs_details') as $erreur)
                        <li>{{ $erreur }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="font-bold text-blue-800 mb-2">تعليمات الرفع:</h3>
            <ul class="list-disc list-inside text-blue-700 text-sm space-y-1">
                <li>يجب أن يكون الملف بصيغة CSV (فاصلة ,)</li>
                <li>يجب أن يحتوي الملف على 4 أعمدة بالترتيب التالي:</li>
                <ol class="list-decimal list-inside mr-4 mt-1 space-y-1">
                    <li><strong>code_massar</strong> - الرمز الماسي</li>
                    <li><strong>nom_ar</strong> - الاسم العائلي (بالعربية)</li>
                    <li><strong>prenom_ar</strong> - الاسم الشخصي (بالعربية)</li>
                    <li><strong>classe</strong> - القسم</li>
                </ol>
                <li>سيتم تجاهل التلاميذ الموجودين مسبقاً في النظام</li>
                <li>الحد الأقصى لحجم الملف: 10MB</li>
            </ul>
        </div>

        <form action="{{ route('eleves.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label for="fichier_eleves" class="block text-sm font-medium text-gray-700 mb-2">
                    اختر ملف التلاميذ (CSV)
                </label>
                <input type="file" name="fichier_eleves" id="fichier_eleves" 
                       accept=".csv,.txt,.xlsx,.xls"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                       required>
                @error('fichier_eleves')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    رفع القائمة
                </button>
                
                <a href="{{ route('dashboard') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                   إلغاء
                </a>
            </div>
        </form>

        <!-- Exemple de fichier CSV -->
        <div class="mt-8 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h4 class="font-bold text-gray-800 mb-3">مثال على شكل الملف:</h4>
            <div class="bg-white border border-gray-300 rounded p-3 font-mono text-sm">
                <div class="text-gray-500"># code_massar,nom_ar,prenom_ar,classe</div>
                <div>E123456789,العلوي,محمد,1BAC-SCIENCES</div>
                <div>E987654321,الفضيلي,فاطمة,2BAC-LETTRES</div>
                <div>E456123789,المرابط,أحمد,TC-SCIENCES</div>
            </div>
        </div>
    </div>
</div>
@endsection