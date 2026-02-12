@extends('pages.admin.admin-content');
<!-- Slotted content -->
@section('content')

    <div class="d-flex mb-3">
        <div class="p-2">
            <h2>Thêm Môn Học Mới</h2>
        </div>
        <div class="ms-auto p-2">
            <form action="/admin/subjects/upload" method="post" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                    <i class="fa-solid fa-upload"></i> Tải lên hàng loạt
                </button>
                <input type="file" name="file" id="fileInput" accept=".xls, .xlsx" style="display: none;" onchange="submitForm()"/>
                <x-form-error name="file"/>
            </form>
        </div>
    </div>

    <form action="/admin/subjects" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tên môn học: </label>
            <input type="text" class="form-control" id="name" name="name" :value="old('name')" required>
            <x-form-error name="name"/>
        </div>

        <div class="mb-3">
            <label for="code" class="form-label">Mã môn học: </label>
            <input type="text" class="form-control" id="code" name="code" :value="old('code')" required>
            <x-form-error name="code"/>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả môn học: </label>
            <textarea class="form-control" id="description" name="description" rows="3"
                      :value="old('description')"></textarea>
            <x-form-error name="description"/>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Thêm môn học</button>
            <button type="reset" class="btn btn-secondary">Đặt lại</button>
        </div>
    </form>
    <!--  -->

    <script>
        $(document).ready(function () {
            // set page title
            $(document).prop('title', 'Add New Subject | Student Management System');
        });

        function submitForm() {
            const fileInput = document.getElementById('fileInput');
            if (fileInput.files.length > 0) {
                document.getElementById('uploadForm').submit();
            }
        }
    </script>

@endsection
