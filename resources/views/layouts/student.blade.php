<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Halaman Calon student</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@0.271.0/dist/umd/lucide.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body x-data="{ sidebarOpen: window.innerWidth > 768 }" @resize.window="sidebarOpen = window.innerWidth > 768" class="flex min-h-screen bg-gray-50">
    {{-- sidebar --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-16'"
        class="relative bg-white shadow-sidebar p-4 transition-all duration-300 ease-in-out"
        x-effect="lucide.createIcons()">
        <div class="flex items-center justify-between gap-2 mb-4">
            <h1 x-show="sidebarOpen"
                class="bg-gradient-to-r from-limeLight to-limeDark text-white text-xl font-bold px-4 rounded-md w-sm items-center flex h-14 md:w-full">
                Halaman
                PPDB</h1>
            <button @click="sidebarOpen = !sidebarOpen"
                class="flex items-center justify-center w-8 h-8  bg-limeDark text-white rounded-full shadow hover:bg-limeLight transition duration-300 ease-in-out md:hidden"
                :class="sidebarOpen ? 'left-64' : 'left-16'">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav class="space-y-2">
            <a href="{{ route('student.dashboard') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center px-4 py-2 rounded-md transition {{ request()->routeIs('student.dashboard') ? 'bg-limeDark text-white' : 'hover:bg-sidebarHover hover:text-white' }}">
                <div class="flex items-center space-x-2">
                    <i data-lucide="home" class="w-5 h-5"></i><span x-show="sidebarOpen">dashboard</span>
                </div>
            </a>
            <div x-data="{ open: {{ request()->is('student/registration*') ? 'true' : 'false' }} }">
                <button
                    @click.prevent="
        if (!sidebarOpen) {
            window.location.href = '{{ route('student.personalData') }}';
        } else {
            open = !open;
        }
    "
                    :class="sidebarOpen ? 'justify-between' : 'justify-center'"
                    class="flex item-center w-full px-4 py-2 rounded transition {{ request()->is('student/registration*') ? 'bg-limeDark text-white' : 'hover:bg-sidebarHover hover:text-white' }}">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        <span x-show="sidebarOpen">Pendaftaran</span>
                    </div>
                    <i x-show="sidebarOpen" data-lucide="chevron-down" class="w-5 h-6 transition-transform"
                        :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open && sidebarOpen" class="ml-6 mt-1 space-y-1" x-transition
                    @click.outside="open = false">
                    <a href="{{ route('student.personalData') }}"
                        class="block px-4 py-1 text-sm rounded-md {{ request()->routeIs('student.personalData') ? 'bg-limeDark text-white' : 'hover:bg-sidebarHover hover:text-white' }}">
                        Data diri siswa
                    </a>
                    <a href="{{ route('student.parentData') }}"
                        class="block px-4 py-1 text-sm rounded-md {{ request()->routeIs('student.parentData') ? 'bg-limeDark text-white' : 'hover:bg-sidebarHover hover:text-white' }}">
                        Data orang tua
                    </a>
                    <a href="{{ route('student.uploadFile') }}"
                        class="block px-4 py-1 text-sm rounded-md {{ request()->routeIs('student.uploadFile') ? 'bg-limeDark text-white' : 'hover:bg-sidebarHover hover:text-white' }}">
                        Upload berkas
                    </a>
                </div>
            </div>
            <a href="{{ route('student.administrationPage') }}"
                :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center px-4 py-2 rounded-md transition {{ request()->routeIs('student.administrationPage') ? 'bg-limeDark text-white' : 'hover:bg-sidebarHover hover:text-white' }}">
                <div class="flex items-center space-x-2">
                    <i data-lucide="clipboard-list" class="w-5 h5"></i><span x-show="sidebarOpen">Administrasi</span>
                </div>
            </a>
            <a href="{{ route('student.announcementPage') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center space-x-2 px-4 py-2 rounded-md transition {{ request()->routeIs('student.announcementPage') ? 'bg-limeDark text-white' : ' hover:bg-sidebarHover hover:text-white' }}">
                <div class="flex items-center space-x-2">
                    <i data-lucide="megaphone" class="w-5 h-5"></i><span x-show="sidebarOpen">Pengumuman</span>
                </div>
            </a>
        </nav>
        {{-- Logout Button --}}
        <form action="{{ route('logout') }}" method="POST" class="mt-6">
            @csrf
            <button type="submit" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex w-full items-center space-x-2 text-left px-4 py-2 rounded-md bg-red-500 text-white hover:bg-red-600 transition">
                <div class="flex items-center space-x-2">
                    <i data-lucide="log-out" class="w-5 h-5"></i><span x-show="sidebarOpen">Logout</span>
                </div>
            </button>
        </form>
    </aside>


    {{-- main content --}}
    <main class="flex-1 p-6 bg-gray-50">
        @yield('content')
    </main>


    {{-- Javascript --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons(); // fallback in case alpine:init missed
        });
    </script>
</body>

</html>
