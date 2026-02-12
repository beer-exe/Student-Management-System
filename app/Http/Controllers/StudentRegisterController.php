<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentRegisterController extends Controller
{
    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        return view('auth.register');
    }

public function store(Request $request)
{
    $request->validate([
        'first_name' => ['required', 'string', 'max:30'],
        'last_name' => ['required', 'string', 'max:50'],
        'gender' => ['required', 'string', 'max:5'],
        'dob' => ['required', 'date'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], 
        'password' => ['required', 'string', 'min:5', 'confirmed'],
    ], [
        'email.unique' => 'Email này đã được đăng ký, vui lòng sử dụng email khác.',
        'email.email' => 'Định dạng email không hợp lệ.',
        'email.required' => 'Vui lòng nhập email.',
        'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        'password.min' => 'Mật khẩu phải có ít nhất 5 ký tự.'
    ]);

    $user = User::create([
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => 3,
        'created_at' => now(),
    ]);

    Student::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'gender' => $request->gender,
        'dob' => $request->dob,
        'user_id' => $user->id,
        'created_at' => now(),
    ]);

    Auth::login($user);

    return redirect()->route('student.dashboard');
}
}
