@extends('pages.admin.admin-content')

@section('content')
<h2>Thêm Lớp Mới</h2>

<form action="/admin/class" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    @csrf
    <div class="mb-3">
        <label for="grade" class="form-label">Khối: </label>
        <select name="grade" id="grade" class="form-select">
            <option value="">-- Chọn khối --</option>
            @foreach ($grades as $grade)
            <option value="{{$grade->id}}">{{$grade->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="class_name" class="form-label">Tên lớp: </label>
        <input type="text" class="form-control" id="class_name" name="class_name" required>
    </div>

    <div class="mb-3">
        <label for="subject_stream" class="form-label">Chương trình học: </label>
        <select name="subject_stream" id="subject_stream" class="form-select">
            <option value="">-- Chọn chương trình học --</option>
            @foreach ($streams as $stream)
            <option value="{{$stream->id}}">{{$stream->stream_name}}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="teacher" class="form-label">Giáo viên: </label>
        <select name="teacher" id="teacher" class="form-select">
            <option value="">-- Chọn giáo viên --</option>
            @foreach ($teachers as $teacher)
            <option value="{{$teacher->id}}">{{$teacher->salutation}} {{$teacher->first_name}} {{$teacher->last_name}}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="year" class="form-label">Năm học: </label>
        <select name="year" id="year" class="form-select">
            <option value="">-- Chọn năm học --</option>
            <option value="{{date("Y")-3}}">{{date("Y")-3}}</option>
            <option value="{{date("Y")-2}}">{{date("Y")-2}}</option>
            <option value="{{date("Y")-1}}">{{date("Y")-3}}</option>
            <option value="{{date("Y")}}">{{date("Y")}}</option>
            <option value="{{date("Y")+1}}">{{date("Y")+1}}</option>
            <option value="{{date("Y")+2}}">{{date("Y")+2}}</option>
            <option value="{{date("Y")+3}}">{{date("Y")+3}}</option>
        </select>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Thêm lớp</button>
        <button type="reset" class="btn btn-outline-secondary">Đặt lại</button>
    </div>

</form>

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'Add New Class');
    })
</script>

@endsection
