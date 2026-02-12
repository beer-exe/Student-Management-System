<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SubjectController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        return view('pages.admin.subject.add');
    }

    public function showAllSubjects()
    {
        return view('pages.admin.subject.index', ['subjects' => Subject::select('id', 'name', 'code', 'description')->paginate(10)]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'code' => ['required', 'string', 'min:2'],
            'description' => ['nullable', 'string'],
        ]);

        Subject::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return redirect('/admin/subjects/show')->with('success', 'Môn học được thêm thành công!');
    }

    public function edit(Subject $subject)
    {
        return view('pages.admin.subject.edit', ['subject' => $subject]);
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'code' => ['required', 'string', 'min:2'],
            'description' => ['nullable', 'string'],
        ]);

        $subject->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return redirect('/admin/subjects/show')->with('success', 'Môn học được cập nhật thành công!');
    }

    public function destroy(Request $request, Subject $subject)
    {
        $subject->delete();
        return redirect('/admin/subjects/show')->with('success', 'Môn học đã được xóa thành công!');
    }

    public function assignTeachersView()
    {
        return view('pages.admin.subject.assign-teachers', ['subjects' => Subject::all(), 'teachers' => Teacher::all()]);
    }

    public function assignTeachers(Request $request)
    {
        $request->validate([
            'teacher' => ['required'],
            'subjects' => ['required'],
        ]);

        $existingSubjects = DB::table('subject_teacher')
            ->where('teacher_id', $request->teacher)
            ->pluck('subject_id')
            ->toArray();

        $newSubjects = array_diff($request->subjects, $existingSubjects);

        $removedSubjects = array_diff($existingSubjects, $request->subjects);

        if (!empty($newSubjects)) {
            foreach ($newSubjects as $subject_id) {
                DB::table('subject_teacher')->insert([
                    'subject_id' => $subject_id,
                    'teacher_id' => $request->teacher,
                    'created_at' => now(),
                ]);
            }
        }

        if (!empty($removedSubjects)) {
            DB::table('subject_teacher')
                ->where('teacher_id', $request->teacher)
                ->whereIn('subject_id', $removedSubjects)
                ->delete();
        }

        return redirect('/admin/teachers/show')->with('success', 'Môn học được giao cho giáo viên thành công!');
    }

    public function showAssignedSubjectsForTeacher(Teacher $teacher)
    {
        $subjects = $teacher->subjects;
        return response($subjects);
    }

    public function uploadSubjects(Request $request)
    {
        $request->validate([
            'file' => ['file', 'mimes:xls,xlsx'],
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($sheet->getRowIterator() as $rowIndex => $row) {
            if ($rowIndex == 1) continue;

            $name = $sheet->getCell("A$rowIndex")->getValue();
            $code = $sheet->getCell("B$rowIndex")->getValue();
            $description = $sheet->getCell("C$rowIndex")->getValue();

            if ($name) {
                Subject::create([
                    'name' => $name,
                    'code' => $code,
                    'description' => $description,
                ]);
            }
        }

        return redirect('/admin/subjects/show')->with('success', 'Môn học được tải lên thành công!');
    }
}
