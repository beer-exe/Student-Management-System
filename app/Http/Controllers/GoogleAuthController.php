<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
                
                Auth::login($user);
                
                return $this->redirectBasedOnRole($user->role_id);
            } else {
                
                $newUser = User::create([
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'role_id' => 3,
                    'password' => Hash::make(Str::random(16)),
                    'is_active' => 1
                ]);

                Student::create([
                    'first_name' => $googleUser->user['given_name'] ?? 'Google',
                    'last_name' => $googleUser->user['family_name'] ?? 'User',
                    'user_id' => $newUser->id,
                    'gender' => 'Other', 
                    'dob' => now(),
                ]);

                Auth::login($newUser);
                return redirect()->route('student.dashboard')->with('success', 'Đăng nhập thành công! Vui lòng cập nhật thông tin.');
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Lỗi đăng nhập Google: ' . $e->getMessage());
        }
    }

    private function redirectBasedOnRole($role_id) {
        switch ($role_id) {
            case 1: return redirect()->route('admin.dashboard');
            case 2: return redirect()->route('teacher.dashboard');
            case 3: return redirect()->route('student.dashboard');
            default: return redirect('/');
        }
    }
}