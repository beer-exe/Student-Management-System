<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherMainController extends Controller
{
    public function showProfilePage()
    {
        $teacher = Teacher::select(['first_name', 'last_name'])->where('user_id', auth()->user()->id)->first();
        return view('pages.teachers.profile', ['teacher' => $teacher]);
    }

    public function showSettingsPage()
    {
        return view('pages.teachers.settings');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'string', 'max:255'],
            'old_password' => ['nullable', 'string', 'min:8'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        $emailChanged = false;

        if ($request->email != $user->email) {
            $request->validate(['email' => ['unique:users,email']]);
            $user->update(['email' => $request->email]);
            $emailChanged = true;
        }

        if ($request->old_password && $request->password) {
            if (!password_verify($request->old_password, $user->password)) {
                return back()->with('error', 'Mật khẩu cũ không đúng!');
            }

            $user->update(['password' => bcrypt($request->password)]);
        }

        if ($emailChanged) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/')->with('success', 'Thông tin đã được cập nhật. Vui lòng đăng nhập lại!');
        }

        return redirect('/')->with('success', 'Thông tin đăng nhập đã được cập nhật thành công. Vui lòng đăng nhập lại!');
    }
}
