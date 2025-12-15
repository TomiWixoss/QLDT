@extends('layouts.admin')

@section('title', 'Sửa người dùng - Tact Admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold">Sửa người dùng: {{ $user->full_name }}</h1>
    </div>

    {{-- Form Card --}}
    <div class="card bg-base-100 shadow max-w-2xl">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                {{-- Username --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Tên đăng nhập *</span></label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}"
                        class="input input-bordered @error('username') input-error @enderror" required>
                    @error('username')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Email *</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="input input-bordered @error('email') input-error @enderror" required>
                    @error('email')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Full Name --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Họ tên *</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}"
                        class="input input-bordered @error('full_name') input-error @enderror" required>
                    @error('full_name')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Số điện thoại</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="input input-bordered @error('phone') input-error @enderror">
                    @error('phone')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Role --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Vai trò *</span></label>
                    <select name="role_id" class="select select-bordered @error('role_id') select-error @enderror" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Password (Optional) --}}
                <div class="divider">Đổi mật khẩu (tùy chọn)</div>

                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Mật khẩu mới</span></label>
                    <input type="password" name="password"
                        class="input input-bordered @error('password') input-error @enderror"
                        placeholder="Để trống nếu không đổi">
                    @error('password')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control mb-6">
                    <label class="label"><span class="label-text font-medium">Xác nhận mật khẩu mới</span></label>
                    <input type="password" name="password_confirmation"
                        class="input input-bordered"
                        placeholder="Nhập lại mật khẩu mới">
                </div>

                {{-- Submit --}}
                <div class="form-control">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
