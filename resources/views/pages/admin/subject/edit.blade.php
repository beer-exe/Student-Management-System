@extends('pages.admin.admin-content');
<!-- Slotted content -->
@section('content')
<h2>Chỉnh Sửa Môn Học: {{$subject->name}}</h2>
<form action="/admin/subjects/{{$subject->id}}" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    @csrf
    @method('PATCH')
    <div class="mb-3">
        <label for="name" class="form-label">Tên môn học: </label>
        <input type="text" class="form-control" id="name" name="name" value="{{$subject->name}}" required>
        <x-form-error name="name" />
    </div>

    <div class="mb-3">
        <label for="code" class="form-label">Mã môn học: </label>
        <input type="text" class="form-control" id="code" name="code" value="{{$subject->code}}" required>
        <x-form-error name="code" />
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Mô tả môn học: </label>
        <textarea class="form-control" id="description" name="description" rows="3">{{$subject->description}}</textarea>
        <x-form-error name="description" />
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-warning">Câp nhật</button>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>
<!--  -->

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'Edit Subject | Student Management System');
    });
</script>

@endsection