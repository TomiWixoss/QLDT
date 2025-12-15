@extends('layouts.admin')

@section('title', 'POS - Tact Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold">Point of Sale (POS)</h1>
            <p class="text-gray-500 mt-1">Bán hàng tại quầy</p>
        </div>
    </div>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex flex-col items-center justify-center py-12">
                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-500 mt-4">Tính năng đang phát triển</h3>
                <p class="text-gray-400 mt-2">POS sẽ được triển khai trong Epic 8</p>
            </div>
        </div>
    </div>
</div>
@endsection
