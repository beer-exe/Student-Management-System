<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\SubjectStream;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function index()
    {
        $classes = DB::table('classes')
            ->leftJoin('class_student', 'classes.id', '=', 'class_student.class_id')
            ->leftJoin('teachers', 'classes.teacher_id', '=', 'teachers.id')
            ->leftJoin('grades', 'classes.grade_id', '=', 'grades.id')
            ->leftJoin('subject_streams', 'classes.subject_stream_id', '=', 'subject_streams.id')
            ->select(
                'classes.id',
                'classes.grade_id',
                'classes.teacher_id',
                'classes.subject_stream_id',
                'classes.name',
                'classes.year',
                'teachers.first_name as teacher_first_name',
                'teachers.last_name as teacher_last_name',
                'grades.name as grade_name',
                'subject_streams.stream_name as subject_stream_name',
                DB::raw('COUNT(class_student.student_id) as students_count')
            )
            ->groupBy(
                'classes.id',
                'classes.grade_id',
                'classes.teacher_id',
                'classes.subject_stream_id',
                'classes.name',
                'classes.year',
                'teachers.first_name',
                'teachers.last_name',
                'grades.name',
                'subject_streams.stream_name'
            )
            ->paginate(20);

        return view('pages.admin.class.index', ['classes' => $classes]);
    }

    public function create()
    {
        $grades = Cache::remember('grades', 600, function () {
            return Grade::select(['id', 'name'])->get();
        });

        $teachers = Cache::remember('teachers', 600, function () {
            return Teacher::select(['id', 'salutation', 'first_name', 'last_name'])->get();
        });

        $streams = SubjectStream::select(['id', 'stream_name'])->get();

        return view('pages.admin.class.add', compact('teachers', 'grades', 'streams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade' => ['required'],
            'class_name' => ['required', 'string'],
            'subject_stream' => ['required'],
            'teacher' => ['required'],
            'year' => ['required', 'numeric'],
        ]);

        Classes::create([
            'grade_id' => $request->grade,
            'name' => $request->class_name,
            'subject_stream_id' => $request->subject_stream,
            'teacher_id' => $request->teacher,
            'year' => $request->year,
        ]);

        return redirect('/admin/class/show')->with('success', 'Lớp được tạo thành công!');
    }

    public function show(Classes $class)
    {
        $class->load([
            'grade',
            'teacher',
            'subjectStream',
            'students.guardian'
        ]);
        return view('pages.admin.class.show', ['class' => $class]);
    }

    public function edit(Classes $class)
    {
        $grades = Cache::remember('grades', 600, function () {
            return Grade::select(['id', 'name'])->get();
        });

        $teachers = Cache::remember('teachers', 600, function () {
            return Teacher::select(['id', 'salutation', 'first_name', 'last_name'])->get();
        });

        $subjects = Cache::remember('subjects', 600, function () {
            return Subject::select(['id', 'name'])->get();
        });
        return view('pages.admin.class.edit', ['class' => $class, 'grades' => $grades, 'subjects' => $subjects, 'teachers' => $teachers]);
    }

    public function update(Request $request, Classes $class)
    {
        $request->validate([
            'grade' => ['required'],
            'class_name' => ['required', 'string'],
            'subject' => ['required'],
            'teacher' => ['required'],
            'year' => ['required', 'numeric'],
        ]);

        $class->update([
            'grade_id' => $request->grade,
            'name' => $request->class_name,
            'subject_id' => $request->subject,
            'teacher_id' => $request->teacher,
            'year' => $request->year,
        ]);

        return redirect('/admin/class/show')->with('success', 'Thông tin lớp học đã được cập nhật thành công!');
    }

    public function destroy(Classes $class)
    {
        $class->delete();
        return redirect('/admin/class/show')->with('success', 'Lớp đã được xóa thành công!');
    }

    public function assignStudentsView(Classes $class)
    {
        $unassignedStudents = DB::table('students')
            ->leftJoin('class_student', 'students.id', '=', 'class_student.student_id')
            ->whereNull('class_student.class_id')
            ->select('students.*')
            ->get();

        return view('pages.admin.class.assign-students', [
            'class' => $class,
            'students' => $unassignedStudents,
        ]);
    }

    public function assignStudents(Request $request, Classes $class)
    {
        foreach ($request->students as $studentId) {
            DB::table('class_student')->insert([
                'class_id' => $class->id,
                'student_id' => $studentId,
                'created_at' => now(),
            ]);

            $subjects = DB::table('subject_stream_subject')
                ->where('subject_stream_id', $class->subject_stream_id)
                ->pluck('subject_id');

            foreach ($subjects as $subjectId) {
                DB::table('student_subjects')->insert([
                    'subject_id' => $subjectId,
                    'student_id' => $studentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect('/admin/class/show')->with('success', 'Học sinh và các môn học được phân bổ vào lớp học đã hoàn thành xuất sắc!');
    }
}
