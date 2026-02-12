<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherStudentController extends Controller
{
    protected $teacherId;

    public function __construct()
    {
        // Get the authenticated teacher's ID once to avoid multiple queries
        $this->teacherId = Teacher::where('user_id', auth()->id())->first()->id;
    }

    public function index()
    {
        return view('pages.teachers.students.index');
    }

    public function create()
    {
        return view('pages.teachers.students.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'std_first_name' => 'required|string|max:30',
            'std_last_name' => 'required|string|max:50',
            'gender' => 'required|string',
            'std_nic' => 'nullable|string|max:12',
            'dob' => 'required|date',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:5',
            'initials' => 'required|string|max:10',
            'g_first_name' => 'required|string|max:30',
            'g_last_name' => 'required|string|max:50',
            'g_nic' => 'required|string|max:12',
            'g_phone' => 'required|string|max:10',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => 3,
            ]);

            $guardian = Guardian::create([
                'initials' => $request->initials,
                'first_name' => $request->g_first_name,
                'last_name' => $request->g_last_name,
                'nic' => $request->g_nic,
                'phone_number' => $request->g_phone,
            ]);

            $student = Student::create([
                'first_name' => $request->std_first_name,
                'last_name' => $request->std_last_name,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'nic' => $request->std_nic ?? '',
                'user_id' => $user->id,
                'guardian_id' => $guardian->id,
            ]);

            $classId = DB::table('classes')->where('teacher_id', $this->teacherId)->first()->id;
            DB::table('class_student')->insert([
                'class_id' => $classId,
                'student_id' => $student->id,
            ]);
        });

        return redirect('/teacher/students/show')->with('success', 'Học sinh được thêm thành công');
    }

    public function showAllStudents()
    {
        $studentsOfTeacher = Student::whereHas('classes', function ($query) {
            $query->where('teacher_id', $this->teacherId);
        })
            ->with(['classes' => function ($query) {
                $query->where('teacher_id', $this->teacherId);
            }])
            ->distinct()
            ->paginate(20);

        return view('pages.teachers.students.index', ['students' => $studentsOfTeacher]);
    }

    public function show(Student $student)
    {
        $student->load('guardian');

        $subjects = DB::table('subjects')
            ->join('student_subjects', 'subjects.id', '=', 'student_subjects.subject_id')
            ->where('student_subjects.student_id', $student->id)
            ->select('subjects.code', 'subjects.name')
            ->get();

        return view('pages.teachers.students.show', [
            'student' => $student,
            'subjects' => $subjects,
        ]);
    }

    public function edit(Student $student)
    {
        $student->load('guardian');
        return view('pages.teachers.students.edit', ['student' => $student]);
    }

    public function update(Student $student, Request $request)
    {
        $request->validate([
            'std_first_name' => 'required|string|max:30',
            'std_last_name' => 'required|string|max:50',
            'gender' => 'required|string|max:5',
            'std_nic' => 'nullable|string|max:12',
            'dob' => 'required|date',
            'initials' => 'required|string|max:10',
            'g_first_name' => 'required|string|max:30',
            'g_last_name' => 'required|string|max:50',
            'g_nic' => 'required|string|max:12',
            'g_phone' => 'required|string|max:10',
        ]);

        DB::transaction(function () use ($student, $request) {
            $student->guardian->update([
                'initials' => $request->initials,
                'first_name' => $request->g_first_name,
                'last_name' => $request->g_last_name,
                'nic' => $request->g_nic,
                'phone_number' => $request->g_phone,
            ]);

            $student->update([
                'first_name' => $request->std_first_name,
                'last_name' => $request->std_last_name,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'nic' => $request->std_nic ?? '',
            ]);
        });

        return redirect('/teacher/students/show')->with('success', 'Học sinh được cập nhật thành công');
    }

    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            $student->user()->delete();
            $student->delete();
        });

        return redirect('/teacher/students/show')->with('success', 'Học sinh đã được xóa thành công!');
    }

    public function assignSubjectsView(Student $student)
    {
        $class = $student->classes()->first();

        $subjectStreamId = $class->subject_stream_id;

        $subjects = DB::table('subjects')
            ->join('subject_stream_subject', 'subjects.id', '=', 'subject_stream_subject.subject_id')
            ->where('subject_stream_subject.subject_stream_id', $subjectStreamId)
            ->select('subjects.*')
            ->get();

        $assignedSubjectIds = DB::table('student_subjects')
            ->where('student_id', $student->id)
            ->pluck('subject_id')
            ->toArray();

        return view('pages.teachers.students.assign-subjects', [
            'student' => $student,
            'subjects' => $subjects,
            'assignedSubjectIds' => $assignedSubjectIds,
        ]);
    }

    public function assignSubjects(Request $request, Student $student)
    {
        $request->validate([
            'subjects' => ['required'],
        ]);
        $selectedSubjectIds = $request->input('subjects', []);

        $existingSubjectIds = DB::table('student_subjects')
            ->where('student_id', $student->id)
            ->pluck('subject_id')
            ->toArray();

        $subjectsToInsert = array_diff($selectedSubjectIds, $existingSubjectIds);

        $subjectsToDelete = array_diff($existingSubjectIds, $selectedSubjectIds);

        DB::transaction(function () use ($subjectsToInsert, $subjectsToDelete, $student) {
            foreach ($subjectsToInsert as $subjectId) {
                DB::table('student_subjects')->insert([
                    'student_id' => $student->id,
                    'subject_id' => $subjectId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('student_subjects')
                ->where('student_id', $student->id)
                ->whereIn('subject_id', $subjectsToDelete)
                ->delete();
        });

        return redirect('/teacher/students/show')->with('success', 'Đã cập nhật chủ đề thành công!');
    }
}
