@extends('pages.admin.admin-content')

@section('content')

{{-- PHẦN 1: HEADER & NÚT THAO TÁC --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.classes.index') }}">Danh sách lớp</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $class->name }}</li>
        </ol>
    </nav>
    <div>
        {{-- Nút để chuyển sang trang gán học sinh vào lớp --}}
        <a href="{{ route('admin.classes.assignView', $class->id) }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill"></i> Thêm học sinh vào lớp
        </a>
        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

{{-- PHẦN 2: THÔNG TIN LỚP HỌC (CARD INFO) --}}
<div class="card shadow-sm mb-4 border-left-primary">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h3 class="text-primary fw-bold mb-3">{{ $class->name }}</h3>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Năm học:</div>
                    <div class="col-sm-8 fw-bold">{{ $class->year }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Khối lớp:</div>
                    <div class="col-sm-8">
                        <span class="badge bg-info text-dark">{{ $class->grade->name ?? 'Chưa gán' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 border-start">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">GV Chủ nhiệm:</div>
                    <div class="col-sm-8 text-success fw-bold">
                        {{-- Kiểm tra xem có GVCN chưa --}}
                        @if($class->teacher)
                            {{ $class->teacher->salutation }} {{ $class->teacher->first_name }} {{ $class->teacher->last_name }}
                        @else
                            <span class="text-danger fst-italic">Chưa phân công</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Phân ban:</div>
                    <div class="col-sm-8">
                        {{ $class->subjectStream->stream_name ?? 'Chưa phân ban' }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Sĩ số:</div>
                    <div class="col-sm-8">
                        <span class="badge bg-primary rounded-pill">{{ $class->students->count() }} Học sinh</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- PHẦN 3: DANH SÁCH HỌC SINH --}}
<div class="card shadow-lg">
    <div class="card-header bg-white py-3">
        <h5 class="m-0 font-weight-bold text-primary">Danh Sách Học Sinh</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="studentsTable" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">STT</th>
                        <th style="width: 10%">Mã HS</th>
                        <th style="width: 25%">Họ và Tên</th>
                        <th style="width: 10%">Giới tính</th>
                        <th style="width: 15%">Ngày sinh</th>
                        <th style="width: 20%">Phụ huynh & SĐT</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($class->students as $key => $student)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $student->index_no ?? 'N/A' }}</span>
                        </td>
                        <td class="fw-bold text-primary">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </td>
                        <td>
                            @if($student->gender == 'Male')
                                <span class="badge bg-info">Nam</span>
                            @elseif($student->gender == 'Female')
                                <span class="badge bg-danger">Nữ</span>
                            @else
                                <span class="badge bg-secondary">Khác</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($student->dob)->format('d/m/Y') }}</td>
                        <td>
                            @if($student->guardian)
                                <div><small class="text-muted">PH:</small> {{ $student->guardian->first_name }}</div>
                                <div><small class="text-muted">SĐT:</small> <strong>{{ $student->guardian->phone_number }}</strong></div>
                            @else
                                <span class="text-muted fst-italic">Chưa có thông tin</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Chưa có học sinh nào trong lớp này. 
                            <a href="{{ route('admin.classes.assignView', $class->id) }}">Thêm ngay</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Đặt tiêu đề trang
        $(document).prop('title', '{{ $class->name }} - Class Details | SMS');
    });
</script>

@endsection