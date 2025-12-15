<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Không có quyền truy cập</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-grid-pattern {
            background-image:
                linear-gradient(to right, #e5e7eb 1px, transparent 1px),
                linear-gradient(to bottom, #e5e7eb 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="min-h-screen bg-base-200 flex items-center justify-center p-4 relative">
    {{-- Background pattern for visual interest --}}
    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>

    <div class="card w-full max-w-md bg-base-100 shadow-xl relative z-10">
        <div class="card-body text-center">
            {{-- Error Icon --}}
            <div class="text-error mb-4">
                <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            {{-- Error Code --}}
            <h1 class="text-6xl font-bold text-error">403</h1>

            {{-- Error Message --}}
            <h2 class="text-xl font-semibold mt-4">Không có quyền truy cập</h2>
            <p class="text-gray-500 mt-2">
                {{ $exception->getMessage() ?: 'Bạn không có quyền truy cập trang này' }}
            </p>

            {{-- User Info (if logged in) --}}
            @auth
            <div class="badge badge-outline mt-2">
                Đăng nhập: {{ Auth::user()->email }} ({{ Auth::user()->role?->name }})
            </div>
            @endauth

            {{-- Action Buttons --}}
            <div class="card-actions justify-center mt-6 gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Quay lại Dashboard
                </a>
                <button onclick="history.back()" class="btn btn-ghost">
                    Quay lại trang trước
                </button>
            </div>

            {{-- Help Text --}}
            <p class="text-sm text-gray-400 mt-4">
                Nếu bạn cho rằng đây là lỗi, vui lòng liên hệ quản trị viên.
            </p>
        </div>
    </div>
</body>
</html>
