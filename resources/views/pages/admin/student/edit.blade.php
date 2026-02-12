@extends('pages.admin.admin-content')

@section('content')

{{-- HIỂN THỊ LỖI NẾU CÓ --}}
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
</div>

<h2>Cập Nhật Học Sinh: {{$student->first_name}} {{$student->last_name}}</h2>

<form action="/admin/students/{{$student->id}}" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    @csrf
    @method('PATCH')
    
    <h5>Thông Tin Học Sinh</h5>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="fname" class="form-label">Tên: <span class="text-danger">*</span></label>
                {{-- Sửa: Dùng old() và ?? '' --}}
                <input type="text" class="form-control" id="fname" name="std_first_name" 
                       value="{{ old('std_first_name', $student->first_name ?? '') }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="lname" class="form-label">Họ: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="lname" name="std_last_name" 
                       value="{{ old('std_last_name', $student->last_name ?? '') }}" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label for="gender" class="form-label">Giới tính: <span class="text-danger">*</span></label>
                <select name="gender" id="gender" class="form-select" required>
                    <option value="">-- Chọn giới tính --</option>
                    {{-- Logic chọn lại giới tính cũ --}}
                    <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Nam</option>
                    <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Nữ</option>
                    <option value="Other" {{ old('gender', $student->gender) == 'Other' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label for="nic" class="form-label">CMND/CCCD: </label>
                {{-- ĐÂY LÀ CHỖ GÂY LỖI: Đã thêm ?? '' --}}
                <input type="text" class="form-control" id="nic" name="std_nic" 
                       value="{{ old('std_nic', $student->nic ?? '') }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label for="dob" class="form-label">Ngày sinh: <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="dob" name="dob" 
                       value="{{ old('dob', $student->dob ?? '') }}" required>
            </div>
        </div>
    </div>
    <hr>
    
    <h5>Thông Tin Người Giám Hộ</h5>
    {{-- Sử dụng toán tử ?-> (safe navigation) để tránh lỗi nếu guardian bị null --}}
    <div class="row">
        <div class="col-md-4">
            <div class="mb-3">
                <label for="initials" class="form-label">Chữ viết tắt: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="initials" name="initials" 
                       value="{{ old('initials', $student->guardian?->initials ?? '') }}" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label for="g_fname" class="form-label">Tên: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="g_fname" name="g_first_name" 
                       value="{{ old('g_first_name', $student->guardian?->first_name ?? '') }}" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label for="g_lname" class="form-label">Họ: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="g_lname" name="g_last_name" 
                       value="{{ old('g_last_name', $student->guardian?->last_name ?? '') }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label for="g_nic" class="form-label">CMND/CCCD (Người giám hộ): <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="g_nic" name="g_nic" 
                       value="{{ old('g_nic', $student->guardian?->nic ?? '') }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="g_phone" class="form-label">Số điện thoại: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="g_phone" name="g_phone" 
                       value="{{ old('g_phone', $student->guardian?->phone_number ?? '') }}" required>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-warning">Cập nhật</button>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</form>

<script>
    $(document).ready(function() {
        $(document).prop('title', 'Edit Student | Student Management System');
    });
</script>

@endsection