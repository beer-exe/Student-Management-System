@extends('pages.teachers.teacher-content')

@section('content')
<h2>Cài Đặt</h2>

<!-- Popup messages -->
@if (session('success'))
<script>
    Swal.fire({
        icon: "success",
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 1500
    });
</script>
@endif

@if (session('warning'))
<script>
    Swal.fire({
        icon: "warning",
        title: "{{ session('warning') }}",
        showConfirmButton: true,
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: "error",
        title: "{{ session('error') }}",
        showConfirmButton: true,
    });
</script>
@endif
@if (session('info'))
<script>
    Swal.fire({
        icon: "info",
        title: "{{ session('info') }}",
        showConfirmButton: true,
    });
</script>
@endif
<!--  -->

<div class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    <form action="/teacher/settings" method="post">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email: </label>
            <input type="email" value="{{auth()->user()->email}}" name="email" id="email" class="form-control" aria-describedby="emailHelp" required>
            <div id="emailHelp" class="form-text">Lưu ý: Sau khi bạn thay đổi email và gửi biểu mẫu, hệ thống sẽ tự động đăng xuất bạn.</div>
            <x-form-error name="email" />
        </div>
        <div class="mb-3">
            <label for="old_password" class="form-label">Mật khẩu cũ: </label>
            <input type="password" name="old_password" id="old_password" class="form-control">
            <x-form-error name="old_password" />
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu mới: </label>
            <input type="password" name="password" id="password" class="form-control">
            <x-form-error name="password" />
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Nhập lại mật khẩu mới</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            <x-form-error name="password_confirmation" />
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Cập nhật </button>
            <button type="reset" class="btn btn-outline-secondary">Đặt lại</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'Settings | Student Management System');
    });
</script>

@endsection
