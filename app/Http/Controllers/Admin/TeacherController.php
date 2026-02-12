<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        return view('pages.teachers.dashboard');
    }

    public function create()
    {
        return view('pages.admin.teacher.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'salutation' => ['required', 'string', 'max:5'],
            'initials' => ['required', 'string', 'max:15'],
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:50'],
            'nic' => ['required', 'string', 'max:12'],
            'dob' => ['required', 'date'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:5'],
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2,
            'created_at' => now(),
        ]);

        Teacher::create([
            'salutation' => $request->salutation,
            'initials' => $request->initials,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'nic' => $request->nic,
            'dob' => $request->dob,
            'user_id' => $user->id,
            'created_at' => now(),
        ]);

        return redirect('/admin/teachers/show')->with('success', 'Giáo viên đã thêm thành công!');
    }

    public function showAllTeachers()
    {
        return view('pages.admin.teacher.index', [
            'teachers' => Teacher::with(['user', 'subjects'])
                ->select(['id', 'first_name', 'last_name', 'user_id'])
                ->paginate(20)
        ]);
    }

    public function show(Teacher $teacher)
    {
        return view('pages.admin.teacher.show', ['teacher' => $teacher]);
    }

    public function edit(Teacher $teacher)
    {
        $subjects = Cache::remember('subjects_list', 60, function () {
            return Subject::get();
        });
        return view('pages.admin.teacher.edit', ['teacher' => $teacher, 'subjects' => $subjects]);
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'salutation' => ['required', 'string', 'max:5'],
            'initials' => ['required', 'string', 'max:15'],
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:50'],
            'nic' => ['required', 'string', 'max:12'],
            'dob' => ['required', 'date'],
        ]);

        $teacher->update([
            'salutation' => $request->salutation,
            'initials' => $request->initials,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'nic' => $request->nic,
            'dob' => $request->dob,
        ]);

        return redirect('/admin/teachers/show')->with('success', 'Giáo viên đã cập nhật thành công!');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->user()->delete();
        return redirect('/admin/teachers/show')->with('success', 'Giáo viên đã xóa thành công!');
    }

    public function assignClassView(Teacher $teacher)
    {
        // $classes = Cache::remember('classes_list', 60, function () {
        //     return Classes::all();
        // });
        return redirect('/admin/teachers/show')->with('info', 'Tính năng chưa được hoàn thiện!');
    }

    public function assignClasses(Request $request, Teacher $teacher)
    {
        // TODO: implement the assignClasses method
    }
}
