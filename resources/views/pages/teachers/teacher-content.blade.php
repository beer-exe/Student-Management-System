<x-private-layout>
    <!-- Navbar -->
    <x-navbar role="{{ auth()->user()->role->name }}">
        <div class="nav">
            <a class="nav-link" href="/teacher/dashboard">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Bảng điều khiển
            </a>

            <x-nav-link idNumber="1" link_name="Quản Lý Học Sinh" icon_class="fa-solid fa-user-graduate">
                <x-sub-nav-link href="/teacher/students/create">Thêm</x-sub-nav-link>
                <x-sub-nav-link href="/teacher/students/show">Xem</x-sub-nav-link>
            </x-nav-link>

            <x-nav-link idNumber="2" link_name="Quản Lý Thông Báo" icon_class="fa-solid fa-bullhorn">
                <x-sub-nav-link href="/teacher/announcements/create">Đăng</x-sub-nav-link>
                <x-sub-nav-link href="/teacher/announcements/show">Xem</x-sub-nav-link>
            </x-nav-link>

            <div class="sb-sidenav-menu-heading">Tiện ích bổ sung</div>
            <a class="nav-link" href="/teacher/profile">
                <div class="sb-nav-link-icon"><i class="fa fa-user" aria-hidden="true"></i></div>
                Hồ sơ
            </a>
            <a class="nav-link getPopup" href="/teacher/settings">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                Cài đặt 
            </a>
            <a class="nav-link getPopup" href="/logout">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></div>
                Đăng xuất
            </a>
        </div>
    </x-navbar>
    <x-nav-top></x-nav-top>
    <div id="layoutSidenav_content">
        <div class="container-fluid">
            <!-- Slotted content -->
            @yield('content')
            <!--  -->
        </div>
    </div>
    <!--  -->

</x-private-layout>