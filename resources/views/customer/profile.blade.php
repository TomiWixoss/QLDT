@extends('layouts.customer')

@section('title', 'Tài khoản của tôi - Tact')

@section('content')
<div class="max-w-2xl mx-auto px-4">
    <h1 class="text-2xl font-semibold mb-8">Tài khoản của tôi</h1>

    {{-- Loyalty Points Card --}}
    <div class="card bg-gradient-to-r from-blue-500 to-blue-600 text-white mb-8">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Điểm tích lũy</p>
                    <p class="text-3xl font-bold">{{ number_format($customer->points) }} điểm</p>
                    <p class="text-sm opacity-90 mt-1">
                        = {{ number_format($customer->points * config('tact.points_value', 1000)) }}đ
                    </p>
                </div>
                <div class="text-right">
                    <svg class="w-16 h-16 opacity-50" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
            </div>
            <div class="divider divider-neutral opacity-30 my-2"></div>
            <p class="text-xs opacity-75">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tích 1 điểm cho mỗi 100.000đ mua hàng
            </p>
        </div>
    </div>

    {{-- Profile Information Form --}}
    <div class="card bg-base-100 shadow-xl mb-8">
        <div class="card-body">
            <h2 class="card-title text-lg mb-4">Thông tin cá nhân</h2>

            @if (session('success'))
                <div class="alert alert-success mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                {{-- Email (Read-only) --}}
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium">Email</span>
                    </label>
                    <input type="email" value="{{ $customer->email }}"
                        class="input input-bordered bg-base-200" disabled>
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Email không thể thay đổi</span>
                    </label>
                </div>

                {{-- Full Name --}}
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium">Họ và tên</span>
                    </label>
                    <input type="text" name="full_name" value="{{ old('full_name', $customer->full_name) }}"
                        class="input input-bordered @error('full_name') input-error @enderror"
                        placeholder="Nhập họ và tên" required>
                    @error('full_name')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-medium">Số điện thoại</span>
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone', $customer->phone) }}"
                        class="input input-bordered @error('phone') input-error @enderror"
                        placeholder="VD: 0912345678">
                    @error('phone')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="form-control">
                    <button type="submit" class="btn btn-primary">
                        Cập nhật thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Change Password Form --}}
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title text-lg mb-4">Đổi mật khẩu</h2>

            @if (session('password_success'))
                <div class="alert alert-success mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('password_success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                {{-- Current Password --}}
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium">Mật khẩu hiện tại</span>
                    </label>
                    <input type="password" name="current_password"
                        class="input input-bordered @error('current_password') input-error @enderror"
                        placeholder="Nhập mật khẩu hiện tại" required>
                    @error('current_password')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium">Mật khẩu mới</span>
                    </label>
                    <input type="password" name="password"
                        class="input input-bordered @error('password') input-error @enderror"
                        placeholder="Tối thiểu 8 ký tự" required>
                    @error('password')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Confirm New Password --}}
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-medium">Xác nhận mật khẩu mới</span>
                    </label>
                    <input type="password" name="password_confirmation"
                        class="input input-bordered"
                        placeholder="Nhập lại mật khẩu mới" required>
                </div>

                {{-- Submit Button --}}
                <div class="form-control">
                    <button type="submit" class="btn btn-outline">
                        Đổi mật khẩu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
