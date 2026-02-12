<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store()
    {
        $attrs = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($attrs)) {
            throw ValidationException::withMessages([
                // 'email' => 'Your provided credentials could not be verified.'
                'email' => 'Invalid Email',
                'password' => 'Invalid Password'
            ]);
        }

        request()->session()->regenerate();

        $role = auth()->user()->role->name ?? null;

        switch ($role) {
            case 'Admin':
                return redirect('/admin/dashboard')->with('greeting', 'Welcome back, Admin!');
            case 'Student':
                return redirect('/student/dashboard')->with('greeting', 'Welcome back, Student!');
            case 'Teacher':
                return redirect('/teacher/dashboard')->with('greeting', 'Welcome back, Teacher!');
            default:
                auth()->logout();
                return redirect('/')->withErrors([
                    'role' => 'Người dùng không có vai trò hợp lệ.'
                ]);
        }
    }

    public function destroy()
    {
        // logout functionality
        auth()->logout();
        // invalidate the user
        request()->session()->invalidate();
        // regenerte the CSRF token
        request()->session()->regenerateToken();
        // redirect to the login page
        return redirect('/');
    }
}
