<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentController extends Controller
{
    public function index()
    {
        return view('pages.students.dashboard');
    }

    public function create()
    {
        return view('pages.admin.student.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'std_first_name' => ['required', 'string', 'max:30'],
            'std_last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string', 'max:5'],
            'std_nic' => ['nullable', 'string', 'max:12'],
            'dob' => ['required', 'date'],
            'index' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:5'],

            'initials' => ['nullable', 'string', 'max:10'],
            'g_first_name' => ['nullable', 'string', 'max:30'],
            'g_last_name' => ['nullable', 'string', 'max:50'],
            'g_nic' => ['nullable', 'string', 'max:12'],
            'g_phone' => ['nullable', 'string', 'max:10'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => 3,
                'created_at' => now(),
            ]);

            $guardian = Guardian::create([
                'initials' => $request->initials,
                'first_name' => $request->g_first_name,
                'last_name' => $request->g_last_name,
                'nic' => $request->g_nic,
                'phone_number' => $request->g_phone,
                'created_at' => now(),
            ]);

            Student::create([
                'first_name' => $request->std_first_name,
                'last_name' => $request->std_last_name,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'nic' => $request->std_nic ?? "",
                'created_at' => now(),
                'user_id' => $user->id,
                'guardian_id' => $guardian->id,
            ]);
        });

        return redirect('/admin/students/show')->with('success', 'Học sinh được thêm thành công!');
    }


    public function showAllStudents()
    {
        return view('pages.admin.student.index', [
            'students' => Student::select(['id', 'first_name', 'last_name'])->paginate(20)
        ]);
    }

    public function show(Student $student)
    {
        $student->load('guardian');

        $subjects = DB::table('subjects')
            ->join('student_subjects', 'subjects.id', '=', 'student_subjects.subject_id')
            ->where('student_subjects.student_id', $student->id)
            ->select('subjects.code', 'subjects.name')
            ->get();

        return view('pages.admin.student.show', ['student' => $student, 'subjects' => $subjects]);
    }

    public function edit(Student $student)
    {
        return view('pages.admin.student.edit', ['student' => $student]);
    }

    public function update(Student $student, Request $request)
    {
        $request->validate([
            'std_first_name' => ['required', 'string', 'max:30'],
            'std_last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string'],
            'std_nic' => ['string', 'max:12', 'nullable'],
            'dob' => ['required', 'date'],
            'initials' => ['required', 'string', 'max:10'],
            'g_first_name' => ['required', 'string', 'max:30'],
            'g_last_name' => ['required', 'string', 'max:50'],
            'g_nic' => ['required', 'string', 'max:12'],
            'g_phone' => ['required', 'string', 'max:10'],
        ]);

        $guardianData = [
            'initials' => $request->initials,
            'first_name' => $request->g_first_name,
            'last_name' => $request->g_last_name,
            'nic' => $request->g_nic,
            'phone_number' => $request->g_phone,
            'updated_at' => now(),
        ];

        if ($student->guardian) {
            $student->guardian->update($guardianData);
        } else {
            $guardian = \App\Models\Guardian::create($guardianData);
            $student->guardian_id = $guardian->id;
            $student->save();
        }

        $student->update([
            'first_name' => $request->std_first_name,
            'last_name' => $request->std_last_name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'nic' => $request->std_nic ?? "",
            'updated_at' => now(),
        ]);

        return redirect('/admin/students/show')->with('success', 'Sinh viên được cập nhật thành công!');
    }

    public function destroy(Student $student)
    {
        $student->user()->delete();
        return redirect('/admin/students/show')->with('success', 'Sinh viên đã được xóa thành công!');
    }

    public function uploadStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        $fieldMappings = [
            'first_name' => ['first name', 'firstname', 'f name'],
            'last_name' => ['last name', 'lastname', 'surname'],
            'gender' => ['gender', 'sex'],
            'nic' => ['nic', 'national id', 'id'],
            'dob' => ['dob', 'date of birth', 'birthdate'],
            'index_no' => ['index', 'index no.', 'index number'],
        ];

        $headerRow = [];
        foreach ($sheet->getRowIterator(1, 1)->current()->getCellIterator() as $cell) {
            $headerRow[] = strtolower(trim($cell->getValue()));
        }

        $columnMap = [];
        foreach ($fieldMappings as $dbField => $possibleHeaders) {
            foreach ($possibleHeaders as $header) {
                $columnIndex = array_search($header, $headerRow);
                if ($columnIndex !== false) {
                    $columnMap[$dbField] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1);
                    break;
                }
            }
        }

        $requiredFields = ['first_name', 'last_name', 'gender', 'nic', 'dob', 'index_no'];
        foreach ($requiredFields as $field) {
            if (!isset($columnMap[$field])) {
                return redirect()->back()->withErrors("Thiếu trường bắt buộc: $field trong bảng tính!");
            }
        }

        foreach ($sheet->getRowIterator(2) as $row) {
            $data = [];
            $rowIndex = $row->getRowIndex();
            foreach ($columnMap as $dbField => $columnLetter) {
                $cellValue = $sheet->getCell($columnLetter . $rowIndex)->getValue();

                if ($dbField === 'dob' && is_numeric($cellValue)) {
                    $data[$dbField] = Carbon::instance(Date::excelToDateTimeObject($cellValue))->format('Y-m-d');
                } else {
                    $data[$dbField] = $cellValue;
                }
            }

            $data['user_id'] = 1;

            Student::create($data);
        }
        return redirect('/admin/students/show')->with('success', 'Học sinh đã tải lên thành công!');
    }
}
