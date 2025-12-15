<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Tact')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200">
    <div class="drawer lg:drawer-open">
        <input id="admin-drawer" type="checkbox" class="drawer-toggle" />

        {{-- Main Content --}}
        <div class="drawer-content flex flex-col">
            {{-- Top Navbar (Mobile) --}}
            <div class="navbar bg-base-100 shadow-sm lg:hidden">
                <div class="flex-none">
                    <label for="admin-drawer" class="btn btn-square btn-ghost drawer-button">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </label>
                </div>
                <div class="flex-1">
                    <span class="text-xl font-bold">Tact Admin</span>
                </div>
            </div>

            {{-- Page Content --}}
            <main class="flex-1 p-4 lg:p-6">
                @yield('content')
            </main>
        </div>

        {{-- Sidebar --}}
        <div class="drawer-side">
            <label for="admin-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <aside class="bg-base-100 w-64 min-h-screen flex flex-col">
                {{-- Logo --}}
                <div class="p-4 border-b">
                    <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-primary">
                        Tact Admin
                    </a>
                </div>

                {{-- User Info --}}
                <div class="p-4 border-b bg-base-200">
                    <div class="flex items-center gap-3">
                        <div class="avatar placeholder">
                            <div class="bg-primary text-primary-content rounded-full w-10">
                                <span>{{ substr(Auth::user()->full_name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div>
                            <p class="font-medium text-sm">{{ Auth::user()->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->role->name ?? 'Staff' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Navigation Menu --}}
                <ul class="menu p-4 flex-1">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    {{-- More menu items will be added in future stories --}}
                </ul>

                {{-- Logout --}}
                <div class="p-4 border-t">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-error w-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</body>
</html>
