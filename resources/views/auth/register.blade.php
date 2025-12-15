@extends('layouts.guest')

@section('title', 'Đăng ký - Tact')

@section('content')
<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title text-2xl font-bold justify-center mb-6">Đăng ký tài khoản</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Full Name --}}
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-medium">Họ và tên</span>
                </label>
                <input type="text" name="full_name" value="{{ old('full_name') }}"
                    class="input input-bordered w-full @error('full_name') input-error @enderror"
                    placeholder="Nguyễn Văn A" required>
                @error('full_name')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-medium">Email</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="input input-bordered w-full @error('email') input-error @enderror"
                    placeholder="email@example.com" required>
                @error('email')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            {{-- Phone --}}
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-medium">Số điện thoại</span>
                </label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                    class="input input-bordered w-full @error('phone') input-error @enderror"
                    placeholder="0901234567" required>
                @error('phone')
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
                    placeholder="Tối thiểu 8 ký tự" required>
                @error('password')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            {{-- Password Confirmation --}}
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text font-medium">Xác nhận mật khẩu</span>
                </label>
                <input type="password" name="password_confirmation"
                    class="input input-bordered w-full"
                    placeholder="Nhập lại mật khẩu" required>
            </div>

            {{-- Submit Button --}}
            <div class="form-control">
                <button type="submit" class="btn btn-primary w-full">
                    Đăng ký
                </button>
            </div>
        </form>

        <div class="divider">hoặc</div>

        {{-- Google OAuth Button --}}
        <a href="{{ route('auth.google') }}" class="btn btn-outline w-full gap-2">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Đăng ký với Google
        </a>

        <p class="text-center text-sm mt-4">
            Đã có tài khoản?
            <a href="{{ route('login') }}" class="link link-primary">Đăng nhập</a>
        </p>
    </div>
</div>
@endsection
