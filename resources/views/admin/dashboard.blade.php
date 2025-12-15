@extends('layouts.admin')

@section('title', 'Dashboard - Tact Admin')

@section('content')
<div class="space-y-6">
    {{-- Welcome Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold">Xin chào, {{ $user->full_name }}!</h1>
            <p class="text-gray-500 mt-1">
                Vai trò: <span class="badge badge-primary">{{ $user->role->name ?? 'Staff' }}</span>
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <p class="text-sm text-gray-500">{{ now()->format('l, d/m/Y') }}</p>
        </div>
    </div>

    {{-- Placeholder Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Stat Card 1 --}}
        <div class="stat bg-base-100 rounded-box shadow">
            <div class="stat-figure text-primary">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div class="stat-title">Đơn hàng hôm nay</div>
            <div class="stat-value text-primary">0</div>
            <div class="stat-desc">Chưa có dữ liệu</div>
        </div>

        {{-- Stat Card 2 --}}
        <div class="stat bg-base-100 rounded-box shadow">
            <div class="stat-figure text-secondary">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-title">Doanh thu hôm nay</div>
            <div class="stat-value text-secondary">0đ</div>
            <div class="stat-desc">Chưa có dữ liệu</div>
        </div>

        {{-- Stat Card 3 --}}
        <div class="stat bg-base-100 rounded-box shadow">
            <div class="stat-figure text-accent">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="stat-title">Khách hàng mới</div>
            <div class="stat-value text-accent">0</div>
            <div class="stat-desc">Chưa có dữ liệu</div>
        </div>

        {{-- Stat Card 4 --}}
        <div class="stat bg-base-100 rounded-box shadow">
            <div class="stat-figure text-warning">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="stat-title">Sản phẩm tồn kho thấp</div>
            <div class="stat-value text-warning">0</div>
            <div class="stat-desc">Chưa có dữ liệu</div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h2 class="card-title">Thao tác nhanh</h2>
            <div class="flex flex-wrap gap-2 mt-4">
                <button class="btn btn-outline btn-sm" disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Thêm sản phẩm
                </button>
                <button class="btn btn-outline btn-sm" disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Xem đơn hàng
                </button>
                <button class="btn btn-outline btn-sm" disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Xem báo cáo
                </button>
            </div>
            <p class="text-sm text-gray-500 mt-2">
                Các tính năng sẽ được kích hoạt trong các story tiếp theo.
            </p>
        </div>
    </div>
</div>
@endsection
