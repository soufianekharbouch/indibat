@extends('layouts.app')

@section('title', 'رفع قائمة المستخدمين')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">رفع قائمة المستخدمين</h1>

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
                <li>يجب أن يحتوي الملف على 6 أعمدة بالترتيب التالي:</li>
                <ol class="list-decimal list-inside mr-4 mt-1 space-y-1">
                    <li><strong>nom</strong> - الاسم العائلي</li>
                    <li><strong>prenom</strong> - الاسم الشخصي</li>
                    <li><strong>matiere</strong> - المادة</li>
                    <li><strong>username</strong> - اسم المستخدم</li>
                    <li><strong>password</strong> - كلمة المرور (غير مشفرة)</li>
                    <li><strong>role</strong> - الصلاحية (prof, admin, root, motasarrif)</li>
                </ol>
                <li>سيتم تجاهل المستخدمين الموجودين مسبقاً في النظام</li>
                <li>الحد الأقصى لحجم الملف: 10MB</li>
                <li class="font-bold text-red-600">تحذير: كلمات المرور في الملف يجب أن تكون غير مشفرة وسيتم تشفيرها تلقائياً</li>
            </ul>
        </div>

        <form action="{{ route('users.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label for="fichier_users" class="block text-sm font-medium text-gray-700 mb-2">
                    اختر ملف المستخدمين (CSV)
                </label>
                <input type="file" name="fichier_users" id="fichier_users" 
                       accept=".csv,.txt"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                       required>
                @error('fichier_users')
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
            <div class="bg-white border border-gray-300 rounded p-3 font-mono text-sm overflow-x-auto">
                <div class="text-gray-500 whitespace-nowrap"># nom,prenom,matiere,username,password,role</div>
                <div class="whitespace-nowrap">العلوي,محمد,الرياضيات,m.alaoui,password123,prof</div>
                <div class="whitespace-nowrap">الفضيلي,فاطمة,الفيزياء,f.fadili,pass456,admin</div>
                <div class="whitespace-nowrap">المرابط,أحمد,الإعلاميات,a.mourabit,admin789,root</div>
                <div class="whitespace-nowrap">البقالي,خديجة,اللغة العربية,k.boukali,password,motasarrif</div>
            </div>
        </div>

        <!-- Informations sur les rôles -->
        <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <h4 class="font-bold text-green-800 mb-3">معلومات عن الصلاحيات:</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-bold text-green-700">prof:</span>
                    <span class="text-green-600">أستاذ - يمكنه إنشاء التقارير والمشاركة في المجالس</span>
                </div>
                <div>
                    <span class="font-bold text-blue-700">admin:</span>
                    <span class="text-blue-600">مدير - صلاحيات كاملة ما عدا إدارة النظام</span>
                </div>
                <div>
                    <span class="font-bold text-red-700">root:</span>
                    <span class="text-red-600">مشرف - صلاحيات كاملة بما في ذلك إدارة النظام</span>
                </div>
                <div>
                    <span class="font-bold text-purple-700">motasarrif:</span>
                    <span class="text-purple-600">متصرف - صلاحيات إدارية محددة</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection