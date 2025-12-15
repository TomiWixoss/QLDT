@extends('layouts.admin')

@section('title', 'Quản lý người dùng - Tact Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold">Quản lý người dùng</h1>
            <p class="text-gray-500 mt-1">Quản lý tài khoản nhân viên</p>
        </div>
    </div>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex flex-col items-center justify-center py-12">
                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-500 mt-4">Tính năng đang phát triển</h3>
                <p class="text-gray-400 mt-2">Quản lý người dùng sẽ được triển khai trong Story 1.8</p>
            </div>
        </div>
    </div>
</div>
@endsection
