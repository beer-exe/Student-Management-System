@extends('pages.teachers.teacher-content');
<!-- Slotted content -->
@section('content')
<h2>Chỉnh Sửa Thông Báo</h2>
<form action="/teacher/announcements/{{$announcement->id}}/edit" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    @csrf

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Cập nhật thông báo</button>
        <button type="reset" class="btn btn-secondary">Đặt lại</button>
    </div>
</form>
<!--  -->

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'Edit Announcement | Student Management System');
    });
</script>

@endsection