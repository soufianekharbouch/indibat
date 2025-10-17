<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء مجلس انضباط - إنضباط</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">إنشاء مجلس انضباط</h1>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 px-3 py-1 rounded">رجوع</a>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">مجلس انضباط للطالب: {{ $eleve->nom_ar }} {{ $eleve->prenom_ar }}</h2>
            
            <form method="POST" action="{{ route('conseils.store') }}">
                @csrf
                <input type="hidden" name="eleve_id" value="{{ $eleve->id }}">
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الأساتذة المعنيون</label>
                    <div class="border border-gray-300 rounded-lg p-4 max-h-60 overflow-y-auto">
                        <label class="flex items-center mb-2">
                            <input type="checkbox" id="selectAllProfs" class="ml-2">
                            <span class="font-bold">اختيار الكل</span>
                        </label>
                        <hr class="my-2">
                        @foreach($profs as $prof)
                        <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                            <input type="checkbox" name="profs[]" value="{{ $prof->id }}" class="ml-2 prof-checkbox">
                            <span>{{ $prof->prenom }} {{ $prof->nom }} - {{ $prof->matiere }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('profs')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الأسباب الرئيسية</label>
                    <div class="border border-gray-300 rounded-lg p-4 max-h-60 overflow-y-auto">
                        <label class="flex items-center mb-2">
                            <input type="checkbox" id="selectAllRaisons" class="ml-2">
                            <span class="font-bold">اختيار الكل</span>
                        </label>
                        <hr class="my-2">
                        @foreach($raisons as $key => $raison)
                        <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                            <input type="checkbox" name="raisons[]" value="{{ $key }}" class="ml-2 raison-checkbox">
                            <span>{{ $raison }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('raisons')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">وصف إضافي</label>
                    <textarea name="description" rows="4" 
                              class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                              placeholder="أضف وصفًا مفصلاً للحالة..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">تاريخ الإغلاق (اختياري)</label>
                    <input type="date" name="date_fermeture" 
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    <p class="text-sm text-gray-500 mt-1">سيتم إغلاق المجلس تلقائياً في هذا التاريخ</p>
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                    إنشاء مجلس الانضباط
                </button>
            </form>
        </div>
    </div>

    <script>
        // Sélectionner tous les professeurs
        document.getElementById('selectAllProfs').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.prof-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Sélectionner toutes les raisons
        document.getElementById('selectAllRaisons').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.raison-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Validation pour s'assurer qu'au moins une raison est sélectionnée
        document.querySelector('form').addEventListener('submit', function(e) {
            const raisonsCheckboxes = document.querySelectorAll('.raison-checkbox:checked');
            if (raisonsCheckboxes.length === 0) {
                e.preventDefault();
                alert('يرجى اختيار سبب واحد على الأقل');
                return false;
            }
        });
    </script>
</body>
</html>