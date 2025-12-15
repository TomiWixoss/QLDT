@extends('layouts.admin')

@section('title', 'Sản phẩm - Tact Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold">Quản lý sản phẩm</h1>
            <p class="text-gray-500 mt-1">Danh sách sản phẩm trong hệ thống</p>
        </div>
    </div>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex flex-col items-center justify-center py-12">
                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-500 mt-4">Tính năng đang phát triển</h3>
                <p class="text-gray-400 mt-2">Quản lý sản phẩm sẽ được triển khai trong Epic 3</p>
            </div>
        </div>
    </div>
</div>
@endsection
