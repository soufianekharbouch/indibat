@extends('layouts.app')

@section('title', 'تغيير كلمة المرور')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">تغيير كلمة المرور</h1>

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

        <form method="POST" action="{{ route('profil.change-password') }}" class="space-y-6">
            @csrf

            <!-- Mot de passe actuel -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                    كلمة المرور الحالية
                </label>
                <div class="relative">
                    <input type="password" 
                           name="current_password" 
                           id="current_password"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 pr-12"
                           placeholder="أدخل كلمة المرور الحالية">
                    <button type="button" 
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none toggle-password"
                            data-target="current_password">
                        <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
                @error('current_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nouveau mot de passe -->
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                    كلمة المرور الجديدة
                </label>
                <div class="relative">
                    <input type="password" 
                           name="new_password" 
                           id="new_password"
                           required
                           minlength="8"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 pr-12"
                           placeholder="أدخل كلمة المرور الجديدة (8 أحرف على الأقل)">
                    <button type="button" 
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none toggle-password"
                            data-target="new_password">
                        <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
                @error('new_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmation nouveau mot de passe -->
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    تأكيد كلمة المرور الجديدة
                </label>
                <div class="relative">
                    <input type="password" 
                           name="new_password_confirmation" 
                           id="new_password_confirmation"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 pr-12"
                           placeholder="أعد إدخال كلمة المرور الجديدة">
                    <button type="button" 
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none toggle-password"
                            data-target="new_password_confirmation">
                        <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
                @error('new_password_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Indicateur de force du mot de passe -->
            <div id="password-strength" class="hidden">
                <div class="flex items-center space-x-2 space-x-reverse mb-2">
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div id="password-strength-bar" class="h-2 rounded-full transition-all duration-300"></div>
                    </div>
                    <span id="password-strength-text" class="text-sm font-medium"></span>
                </div>
                <ul id="password-requirements" class="text-xs text-gray-600 space-y-1">
                    <li id="req-length" class="flex items-center">
                        <svg class="w-4 h-4 ml-1 text-gray-400 requirement-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>8 أحرف على الأقل</span>
                    </li>
                </ul>
            </div>

            <div class="flex gap-4">
                <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    تغيير كلمة المرور
                </button>
                
                <a href="{{ route('dashboard') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 flex items-center">
                   إلغاء
                </a>
            </div>
        </form>

        <!-- Conseils de sécurité -->
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h3 class="font-bold text-yellow-800 mb-2">نصائح لأمان أفضل:</h3>
            <ul class="text-sm text-yellow-700 list-disc list-inside space-y-1">
                <li>استخدم كلمة مرور قوية تحتوي على أحرف وأرقام ورموز</li>
                <li>لا تستخدم كلمات مرور مستخدمة في حسابات أخرى</li>
                <li>غير كلمة المرور بانتظام</li>
                <li>لا تشارك كلمة المرور مع أي شخص</li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour basculer la visibilité du mot de passe
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const eyeIcon = this.querySelector('.eye-icon');
            const eyeOffIcon = this.querySelector('.eye-off-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        });
    });

    // Validation de la force du mot de passe
    const newPasswordInput = document.getElementById('new_password');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');
    const passwordStrength = document.getElementById('password-strength');
    const reqLength = document.getElementById('req-length');

    newPasswordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = checkPasswordStrength(password);
        
        if (password.length > 0) {
            passwordStrength.classList.remove('hidden');
        } else {
            passwordStrength.classList.add('hidden');
        }

        // Mettre à jour la barre de force
        strengthBar.style.width = strength.percentage + '%';
        strengthBar.className = 'h-2 rounded-full transition-all duration-300 ' + strength.color;
        strengthText.textContent = strength.text;
        strengthText.className = 'text-sm font-medium ' + strength.textColor;

        // Mettre à jour les exigences
        const lengthIcon = reqLength.querySelector('.requirement-icon');
        if (password.length >= 8) {
            lengthIcon.classList.remove('text-gray-400');
            lengthIcon.classList.add('text-green-500');
        } else {
            lengthIcon.classList.remove('text-green-500');
            lengthIcon.classList.add('text-gray-400');
        }
    });

    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = '';

        // Longueur
        if (password.length >= 8) strength += 25;
        
        // Lettres minuscules et majuscules
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
        
        // Chiffres
        if (password.match(/\d/)) strength += 25;
        
        // Caractères spéciaux
        if (password.match(/[^a-zA-Z\d]/)) strength += 25;

        if (strength === 0) {
            return { percentage: 0, text: 'ضعيفة', color: 'bg-red-500', textColor: 'text-red-600' };
        } else if (strength <= 25) {
            return { percentage: 25, text: 'ضعيفة', color: 'bg-red-500', textColor: 'text-red-600' };
        } else if (strength <= 50) {
            return { percentage: 50, text: 'متوسطة', color: 'bg-yellow-500', textColor: 'text-yellow-600' };
        } else if (strength <= 75) {
            return { percentage: 75, text: 'جيدة', color: 'bg-blue-500', textColor: 'text-blue-600' };
        } else {
            return { percentage: 100, text: 'قوية', color: 'bg-green-500', textColor: 'text-green-600' };
        }
    }

    // Validation en temps réel de la confirmation
    const confirmInput = document.getElementById('new_password_confirmation');
    
    function validatePasswordMatch() {
        const password = newPasswordInput.value;
        const confirm = confirmInput.value;
        
        if (confirm.length > 0 && password !== confirm) {
            confirmInput.classList.add('border-red-500');
            confirmInput.classList.remove('border-gray-300');
        } else {
            confirmInput.classList.remove('border-red-500');
            confirmInput.classList.add('border-gray-300');
        }
    }

    newPasswordInput.addEventListener('input', validatePasswordMatch);
    confirmInput.addEventListener('input', validatePasswordMatch);
});
</script>

<style>
.requirement-icon {
    transition: color 0.3s ease;
}

#password-strength-bar {
    transition: all 0.3s ease;
}

.toggle-password {
    transition: color 0.2s ease;
}

.toggle-password:hover {
    color: #374151;
}
</style>
@endsection