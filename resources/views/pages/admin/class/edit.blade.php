@extends('pages.admin.admin-content')

@section('content')
    <h2>Chỉnh Sửa Lớp: {{$class->name}} - {{$class->year}}</h2>
    <form action="/admin/class/{{$class->id}}" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label for="grade" class="form-label">Khối: </label>
            <select name="grade" id="grade" class="form-select">
                <option value="">-- Chọn khối --</option>
                @foreach ($grades as $grade)
                    <option
                        value="{{$grade->id}}" {{$class->grade_id==$grade->id ? 'selected' : ''}}>{{$grade->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="class_name" class="form-label">Tên lớp: </label>
            <input type="text" class="form-control" id="class_name" name="class_name" value="{{$class->name}}"
                   required>
        </div>

        <div class="mb-3">
            <label for="subject" class="form-label">Môn học: </label>
            <select name="subject" id="subject" class="form-select">
                <option value="">-- Chọn môn học --</option>
                @foreach ($subjects as $subject)
                    <option
                        value="{{$subject->id}}" {{$class->subject_id == $subject->id ? 'selected' : ''}}>{{$subject->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="teacher" class="form-label">Giáo viên: </label>
            <select name="teacher" id="teacher" class="form-select">
                <option value="">-- Chọn giáo viên --</option>
                @foreach ($teachers as $teacher)
                    <option
                        value="{{$teacher->id}}" {{$class->teacher_id == $teacher->id ? 'selected' : ''}} >{{$teacher->salutation}} {{$teacher->first_name}} {{$teacher->last_name}}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="year" class="form-label">Năm học: </label>
            <select name="year" id="year" class="form-select">
                <option value="">-- Chọn năm học --</option>
                <option
                    value="{{date("Y")-3}}" {{$class->year == date("Y")-3 ? 'selected' : ''}} >{{date("Y")-3}}</option>
                <option
                    value="{{date("Y")-2}}" {{$class->year == date("Y")-2 ? 'selected' : ''}}>{{date("Y")-2}}</option>
                <option
                    value="{{date("Y")-1}}" {{$class->year == date("Y")-1 ? 'selected' : ''}}>{{date("Y")-1}}</option>
                <option value="{{date("Y")}}" {{$class->year == date("Y") ? 'selected' : ''}}>{{date("Y")}}</option>
                <option
                    value="{{date("Y")+1}}" {{$class->year == date("Y")+1 ? 'selected' : ''}}>{{date("Y")+1}}</option>
                <option
                    value="{{date("Y")+2}}" {{$class->year == date("Y")+2 ? 'selected' : ''}}>{{date("Y")+2}}</option>
                <option
                    value="{{date("Y")+3}}" {{$class->year == date("Y")+3 ? 'selected' : ''}}>{{date("Y")+3}}</option>
            </select>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-warning">Chỉnh sửa lớp</button>
            <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>

    </form>

    <script>
        $(document).ready(function () {
            // set page title
            $(document).prop('title', 'Add New Class');
        })
    </script>
@endsection
