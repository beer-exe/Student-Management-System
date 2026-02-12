@extends('pages.admin.admin-content')

@section('content')
<h2>Danh Sách Chương Trình Học</h2>

<!-- Popup messages -->
@if (session('success'))
<x-popup-message type="success" :message="session('success')" />
@endif

@if (session('info'))
<x-popup-message type="info" :message="session('info')" />
@endif

@if (session('warning'))
<x-popup-message type="warning" :message="session('warning')" />
@endif

@if (session('error'))
<x-popup-message type="error" :message="session('error')" />
@endif
<!--  -->

<!-- Slotted content -->
<table class="table table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Tên Chương Trình</th>
            <th>Số Học Sinh Được Gán</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = 1;
        @endphp

        @foreach ($streams as $stream)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $stream['stream_name'] }}</td>
            <td>{{ $stream['student_count'] }}</td>
            <td>
                <a href="/admin/streams/{{ $stream['id'] }}/assign" class="btn btn-info btn-sm">Gán</a>
                <a href="/admin/streams/{{ $stream['id'] }}/edit" class="btn btn-warning btn-sm">Chỉnh sửa</a>
                <form action="/admin/streams/{{ $stream['id'] }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                </form>
            </td>
        </tr>
        @php
        $i++;
        @endphp
        @endforeach

    </tbody>
</table>
<!--  -->

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'All Streams | Student Management System');
    });
</script>
@endsection