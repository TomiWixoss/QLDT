<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập Admin - Tact</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200 flex items-center justify-center p-4">
    <div class="card w-full max-w-md bg-base-100 shadow-xl">
        <div class="card-body">
            {{-- Logo --}}
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-primary">Tact Admin</h1>
                <p class="text-gray-500 mt-2">Đăng nhập để quản lý hệ thống</p>
            </div>

            {{-- Session Message --}}
            @if (session('message'))
                <div class="alert alert-warning mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    {{ session('message') }}
                </div>
            @endif

            {{-- Error Alert --}}
            @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                {{-- Email --}}
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium">Email</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="input input-bordered @error('email') input-error @enderror"
                        placeholder="admin@tact.vn" required autofocus>
                </div>

                {{-- Password --}}
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium">Mật khẩu</span>
                    </label>
                    <input type="password" name="password"
                        class="input input-bordered @error('password') input-error @enderror"
                        placeholder="••••••••" required>
                </div>

                {{-- Remember Me --}}
                <div class="form-control mb-6">
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" name="remember" class="checkbox checkbox-primary checkbox-sm">
                        <span class="label-text">Ghi nhớ đăng nhập</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <div class="form-control">
                    <button type="submit" class="btn btn-primary w-full">
                        Đăng nhập
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
