@extends('layouts.guest')

@section('title', 'Đăng nhập - Tact')

@section('content')
<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title text-2xl font-bold justify-center mb-6">Đăng nhập</h2>

        {{-- Session Status --}}
        @if (session('success'))
            <div class="alert alert-success mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-medium">Email</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="input input-bordered w-full @error('email') input-error @enderror"
                    placeholder="email@example.com" required autofocus>
                @error('email')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-medium">Mật khẩu</span>
                </label>
                <input type="password" name="password"
                    class="input input-bordered w-full @error('password') input-error @enderror"
                    placeholder="Nhập mật khẩu" required>
                @error('password')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="form-control mb-6">
                <label class="label cursor-pointer justify-start gap-2">
                    <input type="checkbox" name="remember" class="checkbox checkbox-primary checkbox-sm"
                        {{ old('remember') ? 'checked' : '' }}>
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

        <div class="divider">hoặc</div>

        {{-- Google OAuth Button (Placeholder for Story 1.4) --}}
        <button class="btn btn-outline w-full" disabled>
            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Đăng nhập với Google (Sắp có)
        </button>

        <p class="text-center text-sm mt-4">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="link link-primary">Đăng ký ngay</a>
        </p>
    </div>
</div>
@endsection
