<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة السلوكيات - إنضباط</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">إدارة السلوكيات</h1>
            <div class="space-x-2 space-x-reverse">
                <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 px-3 py-1 rounded">لوحة التحكم</a>
                <a href="{{ route('comportements.create') }}" class="bg-green-500 hover:bg-green-600 px-3 py-1 rounded">إضافة سلوك</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
                <h2 class="text-lg font-bold">قائمة السلوكيات</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right">السلوك (فرنسي)</th>
                            <th class="px-6 py-3 text-right">السلوك (عربي)</th>
                            <th class="px-6 py-3 text-right">النقاط المخصومة</th>
                            <th class="px-6 py-3 text-right">الفئة</th>
                            <th class="px-6 py-3 text-right">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($comportements as $comportement)
                        <tr>
                            <td class="px-6 py-4">{{ $comportement->nom_fr }}</td>
                            <td class="px-6 py-4 font-arabic">{{ $comportement->nom_ar }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">
                                    -{{ $comportement->points_retires }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
                                    {{ $comportement->categorie }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('comportements.edit', $comportement->id) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                   تعديل
                                </a>
                                <form action="{{ route('comportements.destroy', $comportement->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"
                                            onclick="return confirm('هل أنت متأكد من حذف هذا السلوك؟')">
                                        حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>