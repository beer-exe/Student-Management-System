<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SubjectStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StreamController extends Controller
{
    public function index()
    {
        $streams = SubjectStream::select(['id', 'stream_name'])
            ->with(['classes.students'])
            ->get()
            ->map(function ($stream) {
                $studentCount = $stream->classes->sum(fn($class) => $class->students->count());
                return [
                    'id' => $stream->id,
                    'stream_name' => $stream->stream_name,
                    'student_count' => $studentCount,
                ];
            });

        return view('pages.admin.stream.index', ['streams' => $streams]);
    }

    public function create()
    {
        return view('pages.admin.stream.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'stream_name' => ['required', 'string', 'max:255'],
            'stream_code' => ['string', 'max:255', 'nullable'],
            'stream_description' => ['string', 'max:255', 'nullable'],
        ]);

        SubjectStream::create([
            'stream_name' => $request->stream_name,
            'stream_code' => $request->stream_code,
            'stream_description' => $request->stream_description,
        ]);

        return redirect()->route('admin.streams.index')->with('success', 'Đã thêm ban môn học mới thành công!');
    }

    public function show(SubjectStream $stream)
    {
        // TODO: Implement show() method.
    }

    public function edit(SubjectStream $stream)
    {
        return view('pages.admin.stream.edit', ['stream' => $stream]);
    }

    public function update(Request $request, SubjectStream $stream)
    {
        $request->validate([
            'stream_name' => ['required', 'string', 'max:255'],
            'stream_code' => ['string', 'max:255', 'nullable'],
            'stream_description' => ['string', 'max:255', 'nullable'],
        ]);

        $stream->update([
            'stream_name' => $request->stream_name,
            'stream_code' => $request->stream_code,
            'stream_description' => $request->stream_description,
        ]);

        return redirect()->route('admin.streams.index')->with('success', 'Đã cập nhật ban thành công!');
    }

    public function destroy(SubjectStream $stream)
    {
        $stream->delete();
        return redirect()->route('admin.streams.index')->with('success', 'Đã xóa ban thành công!');
    }

    public function assignSubjectsView(SubjectStream $stream)
    {
        $subjects = Subject::select(['id', 'name', 'code'])->get();

        $assignedSubjectIds = $stream->subjects()->pluck('subjects.id')->toArray();

        return view('pages.admin.stream.assign-subjects', [
            'stream' => $stream,
            'subjects' => $subjects,
            'assignedSubjectIds' => $assignedSubjectIds
        ]);
    }


    public function assignSubjects(Request $request, SubjectStream $stream)
    {
        $validatedData = $request->validate([
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        foreach ($validatedData['subjects'] as $subject) {
            DB::table('subject_stream_subject')->insert([
                'subject_id' => $subject,
                'subject_stream_id' => $stream->id,
                'created_at' => now(),
            ]);
        }

        return redirect()->route('admin.streams.index')->with('success', 'Các môn học được phân bổ vào ban thành công');
    }
}
