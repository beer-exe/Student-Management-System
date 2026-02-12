<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentStdController extends Controller
{
    public function showProfilePage()
    {
        //        dd(Student::where('user_id', auth()->user()->id)->get());
        $student_data = Student::select(['first_name', 'last_name'])->where('user_id', auth()->user()->id)->first();
        //        dd($student_data);
        return view('pages.students.profile', ['student' => $student_data]);
    }

    public function showSettingsPage(): View|Factory|Application
    {
        return view('pages.students.settings');
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
                return back()->with('error', 'Old password is incorrect');
            }

            $user->update(['password' => bcrypt($request->password)]);
        }

        if ($emailChanged) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/')->with('success', 'Thông tin đã được cập nhật. Vui lòng đăng nhập lại.');
        }

        return redirect('/')->with('success', 'Thông tin đã được cập nhật thành công. Vui lòng đăng nhập lại.');
    }
}
