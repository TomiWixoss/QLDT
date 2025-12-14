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

        <p class="text-center text-sm">
            Đã có tài khoản?
            <a href="{{ route('login') }}" class="link link-primary">Đăng nhập</a>
        </p>
    </div>
</div>
@endsection
