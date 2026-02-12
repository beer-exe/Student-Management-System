@extends('pages.teachers.teacher-content')

@section('content')

    {{-- PHẦN HIỂN THỊ THÔNG BÁO LỖI VÀ THÀNH CÔNG (MỚI THÊM) --}}
    <div class="mt-3">
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        
        {{-- Hiển thị thông báo nếu GV chưa có lớp --}}
        @if(isset($currentClass) && !$currentClass)
             <div class="alert alert-warning shadow-sm">
                <i class="bi bi-exclamation-triangle-fill"></i> Bạn chưa được phân công chủ nhiệm lớp nào. Vui lòng liên hệ Admin.
            </div>
        @endif
    </div>

    <h2>Thêm Học Sinh Mới</h2>
    
    {{-- Hiển thị tên lớp nếu có biến truyền sang --}}
    @if(isset($currentClass) && $currentClass)
        <p class="text-muted">Đang thêm vào lớp: <strong>{{ $currentClass->name }}</strong> @if(isset($gradeName)) (Khối {{ $gradeName }}) @endif</p>
    @endif

    <form action="{{ route('teacher.student.store') }}" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
        @csrf
        
        <h5>Thông Tin Học Sinh</h5>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="fname" class="form-label">Tên: <span class="text-danger">*</span></label>
                    {{-- SỬA LỖI: dùng value="{{ }}" thay vì :value --}}
                    <input type="text" class="form-control" id="fname" name="std_first_name" value="{{ old('std_first_name') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="lname" class="form-label">Họ: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="lname" name="std_last_name" value="{{ old('std_last_name') }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="gender" class="form-label">Giới tính: <span class="text-danger">*</span></label>
                    <select name="gender" id="gender" class="form-select" required>
                        <option value="">-- Chọn giới tính --</option>
                        {{-- Giữ lại lựa chọn cũ khi reload --}}
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Nam</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Nữ</option>
                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="nic" class="form-label">CMND/CCCD: </label>
                    <input type="text" class="form-control" id="nic" name="std_nic" value="{{ old('std_nic') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="dob" class="form-label">Ngày sinh: <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email" class="form-label">Email: <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu: <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
            </div>
        </div>
        <hr>
        <h5>Thông Tin Người Giám Hộ</h5>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="initials" class="form-label">Chữ viết tắt (VD: Mr. / Mrs.): <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="initials" name="initials" value="{{ old('initials') }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="g_fname" class="form-label">Tên: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="g_fname" name="g_first_name" value="{{ old('g_first_name') }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="g_lname" class="form-label">Họ: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="g_lname" name="g_last_name" value="{{ old('g_last_name') }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="g_nic" class="form-label">CMND/CCCD: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="g_nic" name="g_nic" value="{{ old('g_nic') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="g_phone" class="form-label">Số điện thoại: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="g_phone" name="g_phone" value="{{ old('g_phone') }}" required>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Thêm học sinh</button>
            <button type="reset" class="btn btn-secondary">Đặt lại</button>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            // set page title
            $(document).prop('title', 'Add New Student | Student Management System');
        });
    </script>

@endsection