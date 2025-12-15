@extends('layouts.guest')

@section('title', 'Đặt mật khẩu - Tact')

@section('content')
<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title text-2xl font-bold text-center mb-2">Đặt mật khẩu</h2>
        <p class="text-center text-gray-600 mb-6">
            Vui lòng đặt mật khẩu để bảo mật tài khoản của bạn
        </p>

        {{-- Session Status --}}
        @if (session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.set') }}">
            @csrf

            {{-- Password --}}
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Mật khẩu mới</span>
                </label>
                <input type="password" name="password"
                    class="input input-bordered @error('password') input-error @enderror"
                    placeholder="Tối thiểu 8 ký tự" required autofocus>
                @error('password')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            {{-- Password Confirmation --}}
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text">Xác nhận mật khẩu</span>
                </label>
                <input type="password" name="password_confirmation"
                    class="input input-bordered"
                    placeholder="Nhập lại mật khẩu" required>
            </div>

            {{-- Submit Button --}}
            <div class="form-control">
                <button type="submit" class="btn btn-primary">
                    Đặt mật khẩu
                </button>
            </div>
        </form>

        <div class="divider"></div>

        <p class="text-center text-sm text-gray-500">
            Bạn đã đăng ký bằng Google. Đặt mật khẩu để có thể đăng nhập bằng email/mật khẩu.
        </p>
    </div>
</div>
@endsection
