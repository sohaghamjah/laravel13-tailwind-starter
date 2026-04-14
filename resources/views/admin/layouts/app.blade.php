<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    @stack('styles')
</head>

<body
    x-data="{
        page: 'dashboard',
        loaded: true,
        darkMode: false,
        stickyMenu: false,
        sidebarToggle: false,
        scrollTop: false
    }"
    x-init="
        darkMode = JSON.parse(localStorage.getItem('darkMode') || 'false');
        $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))
    "
    :class="{ 'dark bg-gray-900': darkMode === true }"
>

@include('admin.partials.preloader')

<!-- ===== Page Wrapper Start ===== -->
<div class="flex h-screen overflow-hidden">

    <!-- Sidebar - NOW INSIDE the x-data scope -->
    @include('admin.partials.sidebar')

    <!-- Content Area -->
    <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
        @include('admin.partials.overlay')
        @include('admin.partials.header')

        <main>
            <div class="p-4 mx-auto max-w-[1440px] md:p-6">
                @yield('content')
            </div>
        </main>
    </div>

</div>
</body>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery (required) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


<script>

const Toast = Swal.mixin({
    toast: true,
    position: "bottom",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

function showToast(type, message) {
    Toast.fire({
        icon: type,
        title: message
    });
}

@if(session('success'))
    Toast.fire({
        icon: 'success',
        title: "{{ session('success') }}"
    });
@endif

@if(session('error'))
    Toast.fire({
        icon: 'error',
        title: "{{ session('error') }}"
    });
@endif

</script>
@stack('scripts')
</html>
