@extends('layouts.customer')

@section('title', 'Tact - Cửa hàng điện thoại chính hãng')

@section('content')
<div class="space-y-8">
    {{-- Welcome Section --}}
    <section class="text-center py-8">
        @auth('customer')
            <h1 class="text-3xl font-bold mb-2">
                Xin chào, {{ Auth::guard('customer')->user()->full_name }}!
            </h1>
            <p class="text-base-content/70">
                Chào mừng bạn quay trở lại Tact
            </p>
        @else
            <h1 class="text-3xl font-bold mb-2">
                Chào mừng đến với Tact
            </h1>
            <p class="text-base-content/70">
                Cửa hàng điện thoại chính hãng - Uy tín - Chất lượng
            </p>
        @endauth
    </section>

    {{-- Trust Signals --}}
    <section class="grid grid-cols-3 gap-4 text-center">
        <div class="card bg-base-200 p-4">
            <div class="text-2xl mb-2">✓</div>
            <div class="text-sm font-medium">IMEI chính hãng</div>
        </div>
        <div class="card bg-base-200 p-4">
            <div class="text-2xl mb-2">🛡️</div>
            <div class="text-sm font-medium">Bảo hành toàn quốc</div>
        </div>
        <div class="card bg-base-200 p-4">
            <div class="text-2xl mb-2">🚚</div>
            <div class="text-sm font-medium">Giao hàng nhanh</div>
        </div>
    </section>

    {{-- Featured Products Placeholder --}}
    <section>
        <h2 class="text-xl font-bold mb-4">Sản phẩm nổi bật</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @for($i = 1; $i <= 4; $i++)
                <div class="card bg-base-100 shadow-sm border">
                    <figure class="px-4 pt-4">
                        <div class="bg-base-200 w-full aspect-square rounded-lg flex items-center justify-center">
                            <span class="text-base-content/30">Sản phẩm {{ $i }}</span>
                        </div>
                    </figure>
                    <div class="card-body p-4">
                        <h3 class="card-title text-sm">Điện thoại mẫu</h3>
                        <p class="text-primary font-bold">0 ₫</p>
                    </div>
                </div>
            @endfor
        </div>
    </section>
</div>
@endsection
