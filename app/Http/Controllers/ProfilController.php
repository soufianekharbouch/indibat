<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('profil.change-password');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'new_password.required' => 'كلمة المرور الجديدة مطلوبة',
            'new_password.min' => 'كلمة المرور الجديدة يجب أن تكون على الأقل 8 أحرف',
            'new_password.confirmed' => 'كلمة المرور الجديدة غير متطابقة',
            'new_password_confirmation.required' => 'تأكيد كلمة المرور الجديدة مطلوب',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = auth()->user();

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'كلمة المرور الحالية غير صحيحة')
                ->withInput();
        }

        // Vérifier que le nouveau mot de passe est différent de l'ancien
        if (Hash::check($request->new_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'كلمة المرور الجديدة يجب أن تكون مختلفة عن كلمة المرور الحالية')
                ->withInput();
        }

        // Mettre à jour le mot de passe
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('profil.change-password')
            ->with('success', 'تم تغيير كلمة المرور بنجاح');
    }
}