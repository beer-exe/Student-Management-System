<x-private-layout>

    <!-- Navbar -->
    <x-navbar role="{{ auth()->user()->role->name }}">
        <div class="nav">
            <a class="nav-link" href="/admin/dashboard">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>

            <x-nav-link idNumber="1" link_name="Quản Lý Học Sinh" icon_class="fa-solid fa-user-graduate">
                <x-sub-nav-link href="/admin/students/create">Thêm</x-sub-nav-link>
                <x-sub-nav-link href="/admin/students/show">Xem</x-sub-nav-link>
            </x-nav-link>

            <x-nav-link idNumber="2" link_name="Quản Lý Giáo Viên" icon_class="fa-solid fa-chalkboard-user">
                <x-sub-nav-link href="/admin/teachers/create">Thêm</x-sub-nav-link>
                <x-sub-nav-link href="/admin/teachers/show">Xem</x-sub-nav-link>
            </x-nav-link>

            <x-nav-link idNumber="3" link_name="Quản Lý Môn Học" icon_class="fa-solid fa-book">
                <x-sub-nav-link href="/admin/subjects/create">Thêm</x-sub-nav-link>
                <x-sub-nav-link href="/admin/subjects/show">Xem</x-sub-nav-link>
                <x-sub-nav-link href="/admin/subjects/assign">Gán giáo viên</x-sub-nav-link>
            </x-nav-link>

            <x-nav-link idNumber="4" link_name="Quản Lý Ban" icon_class="fa-solid fa-school">
                <x-sub-nav-link href="/admin/streams/create">Thêm</x-sub-nav-link>
                <x-sub-nav-link href="/admin/streams/show">Xem</x-sub-nav-link>
            </x-nav-link>

            <x-nav-link idNumber="5" link_name="Quản Lý Lớp" icon_class="fa-solid fa-chalkboard">
                <x-sub-nav-link href="/admin/class/create">Thêm</x-sub-nav-link>
                <x-sub-nav-link href="/admin/class/show">Xem</x-sub-nav-link>
            </x-nav-link>

            <div class="sb-sidenav-menu-heading">Tiện ích bổ sung</div>
            <a class="nav-link" href="/admin/profile">
                <div class="sb-nav-link-icon"><i class="fa fa-user" aria-hidden="true"></i></div>
                Hồ sơ
            </a>
            <a class="nav-link getPopup" href="/admin/settings">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                Cài đặt
            </a>
            <a class="nav-link getPopup" href="/admin/messages">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-message"></i></div>
                Tin nhắn
            </a>
            <a class="nav-link getPopup" href="/logout">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></div>
                Đăng xuất
            </a>
        </div>
    </x-navbar>
    <x-nav-top></x-nav-top>

    <div id="layoutSidenav_content">
        <div class="container-fluid mt-2">
            <!-- Slotted content -->
            @yield('content')
            <!--  -->
        </div>
    </div>
    <!--  -->

</x-private-layout>
