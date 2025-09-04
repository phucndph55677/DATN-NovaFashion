@extends('client.layouts.app')

@section('title', 'Trang Chủ')

@section('content')
    <body>
        <main id="main" class="site-main">
            <div class="container">
                <!-- Promotion -->
                {{-- <div class="nav-info d-flex">
                    <div class="left-nav-info item-nav-info">
                        <a href="{{ route('products.sale', 50) }}">
                            <span>SALE OFF 50%</span>
                        </a>
                    </div>
                    <div class="center-nav-info item-nav-info">
                        <a href="{{ route('products.sale', 30) }}">
                            <span>SALE OFF 30%</span>
                        </a>
                    </div>
                    <div class="right-nav-info item-nav-info">
                        <a href="{{ route('products.sale', 10) }}">
                            <span>SALE OFF 10% </span>
                        </a>
                    </div>
                </div> --}}
                <!-- End Promotion -->

                <!--Slider Trang chủ - Đầu trang-->
                @if ($banners_top_home->count() > 0)
                    <section class="home-banner bg-before">
                        <div id="banner-top-home" class="slider-banner owl-carousel">
                            @foreach ($banners_top_home as $banner)
                                <div class="item-banner">
                                    <a href="{{ $banner->product_link ?? '#' }}">
                                        <img src="{{ asset('storage/' . $banner->image) }}" style="height: 550px;"  alt="{{ $banner->name }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <!-- Trending Box -->
                        <div class="trending-content">
                            <div class="box-trending">
                                <h3 style="text-transform: capitalize;">Trending</h3>
                                <h2>NovaFashion</h2>
                                <p>Thời trang - Phong cách - Dẫn đầu xu hướng</p>
                            </div>
                        </div>
                    </section>
                @endif
                <!--/Slider-->

                <!-- Ưu đãi Voucher -->
                <section class="home-new-prod my-5">
                    <h2 class="title-section">Deal hot hôm nay / Săn deal liền tay</h2>

                    <div class="swiper mySwiper px-3">
                        <div class="swiper-wrapper">
                            @foreach ($vouchers as $voucher)
                                @php
                                    $percent = $voucher->quantity > 0 
                                        ? round(($voucher->total_used / $voucher->quantity) * 100, 2) 
                                        : 0;
                                @endphp

                                <div class="swiper-slide">
                                    <div class="border rounded-3 p-4 bg-light h-100 d-flex flex-column justify-content-between" style="min-width: 250px;">
                                        <div>
                                            <h5 class="fw-bold mb-2" style="font-weight: 600; font-size: 18px;">Giảm {{ number_format($voucher->sale_price, 0, ',', '.') }} VND</h5>
                                            <p class="mb-1">Đơn Tối Thiểu {{ number_format($voucher->min_order_value, 0, ',', '.') }} VND</p>
                                            <p class="mb-1 text-danger">HSD: {{ ($voucher->end_date)->format('d-m-Y H:i') }}</p>
                                            <p class="mb-1 d-flex justify-content-between">
                                                <span>Đã Dùng: {{ $voucher->total_used }}/{{ $voucher->quantity }}</span>
                                                <span class="text-success fw-bold">{{ $percent }}%</span>
                                            </p>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $percent }}%;"
                                                    aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div><hr>                                 
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <button class="btn btn-link p-0 text-decoration-underline fw-bold" 
                                                style="font-size: 14px;" data-bs-toggle="modal"
                                                data-bs-target="#voucherModal{{ $voucher->id }}">Điều kiện</button>
                                            {{-- <a href="#" class="btn btn-dark btn-sm">Dùng mã</a> --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Nút điều hướng đẹp hơn -->
                        <div class="swiper-button-next text-dark"></div>
                        <div class="swiper-button-prev text-dark"></div>
                    </div>
                </section>

                <!--  Modal -->
                @foreach ($vouchers as $voucher)
                    <div class="modal" id="voucherModal{{ $voucher->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border border-light-subtle shadow-sm">
                                <div class="modal-header border-0 align-items-center justify-content-between px-4 pt-4 pb-0">
                                    <h5 class="modal-title text-uppercase fs-5 fw-bold m-0">CHI TIẾT MÃ ƯU ĐÃI</h5>
                                    <!-- Nút X bên phải, ngang hàng -->
                                    <button type="button" class="btn border-0 bg-transparent p-0" data-bs-dismiss="modal" aria-label="Đóng"
                                        style="font-size: 2rem; line-height: 1; color: #7e7e7e; font-weight: 390;">
                                        &times;
                                    </button>
                                </div>

                                <!-- Body -->
                                <div class="modal-body px-4 py-3">
                                    <div class="border rounded-4 text-center px-3 py-4 mb-4">
                                        <h5 class="fw-bold mb-2" style="font-weight: 600; font-size: 18px;">Giảm {{ number_format($voucher->sale_price, 0, ',', '.') }} VND</h5>
                                        <p class="mb-1" style="font-size: 12px; font-weight: 500;">Đơn Tối Thiểu {{ number_format($voucher->min_order_value, 0, ',', '.') }} VND</p>
                                        <!-- Mã và Copy -->
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <div class="border rounded px-4 py-2 fw-bold fs-6 bg-light"
                                                id="voucher-code-{{ $voucher->id }}" style="font-weight: 900;">
                                                {{ $voucher->voucher_code }}
                                            </div>
                                            <button class="btn btn-outline-dark btn-sm rounded-circle"
                                                onclick="copyVoucherCode('{{ $voucher->id }}')" title="Sao chép mã">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>

                                        <!-- Thông báo sao chép -->
                                        <div class="text-success text-center mt-2 d-none"
                                            id="copied-message-{{ $voucher->id }}">
                                            <i class="bi bi-check-circle-fill"></i> Đã sao chép mã!
                                        </div>
                                    </div>

                                    <p class="mb-1" style="font-size: 13px">- Thời gian:
                                        {{ $voucher->start_date->format('d/m/Y H:i') }} - {{ $voucher->end_date->format('d/m/Y H:i') }}
                                    </p>

                                    <ul class="ps-3 mb-0" style="font-size: 13px;">
                                        <li>- Địa điểm áp dụng: NovaFashion</li>
                                        <li>- Mô tả: {{ $voucher->description }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!-- End Ưu đãi Voucher -->

                <!-- TỰ HÀO VIỆT NAM ƠI -->
                <section class="home-new-prod">
                    <div class="title-section">
                        TỰ HÀO VIỆT NAM ƠI
                        <img src="https://flagcdn.com/w80/vn.png" alt="Cờ Việt Nam" style="width:24px; height:auto; margin-right:6px;">
                    </div>

                    <div class="exclusive-tabs">
                        <div class="exclusive-content">
                            <div class="exclusive-inner active">
                                <div class="list-products new-prod-slider owl-carousel">
                                    @foreach ($productsVN as $product)
                                        @php
                                            $variant = $product->variants->first(); // hoặc chọn variant theo logic khác

                                            $favorites = Auth::check() ? Auth::user()->favorites->pluck('product_id')->toArray() : [];
                                            $isFavorite = in_array($product->id, $favorites);
                                        @endphp

                                        <div class="item-new-prod">
                                            <div class="product" data-product-id="{{ $product->id }}">
                                                <div class="thumb-product">
                                                    <a href="{{ route('products.show', $product->id) }}">
                                                        <img class="product-img" src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}">
                                                        {{-- <img class="hover-img" src=""> --}}
                                                    </a>
                                                </div>
                                                <div class="info-product">
                                                    <div class="list-color">
                                                        <ul>
                                                            @foreach ($product->variants->unique('color_id') as $colorVariant)
                                                                <li class="{{ $loop->first ? 'checked' : '' }}" data-color-id="{{ $colorVariant->color_id }}">
                                                                    <a href=""
                                                                        class="color-picker"
                                                                        data-image="{{ asset('storage/' . $colorVariant->image) }}"
                                                                        data-price="{{ $colorVariant->price }}"
                                                                        data-sale="{{ $colorVariant->sale }}"
                                                                        data-product="{{ $product->id }}"
                                                                        data-color-name="{{ $colorVariant->color->name ?? '' }}">
                                                                        @php $needsBorder = strtolower($colorVariant->color->color_code) === '#ffffff' ? '1' : '0'; @endphp
                                                                        <span class="color-swatch" data-color="{{ $colorVariant->color->color_code }}" data-has-border="{{ $needsBorder }}"></span>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>

                                                        <div style="display:inline-block; cursor:pointer;">
                                                            <svg
                                                                id="favorite-icon-{{ $product->id }}"
                                                                width="24" height="24"
                                                                fill="{{ $isFavorite ? 'red' : 'none' }}"
                                                                stroke="{{ $isFavorite ? 'white' : 'currentColor' }}"
                                                                stroke-width="1"
                                                                onclick="
                                                                    event.preventDefault();
                                                                    let userLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
                                                                    const toast = document.getElementById('toast');
                                                                    if(!userLoggedIn){
                                                                        document.getElementById('toast-message').innerText = 'Vui lòng đăng nhập để thêm sản phẩm yêu thích.';
                                                                        toast.style.display = 'flex';
                                                                        toast.style.opacity = '1';
                                                                        setTimeout(()=> {
                                                                            toast.style.opacity = '0';
                                                                            setTimeout(()=>{ toast.style.display='none'; }, 400);
                                                                        }, 3000);
                                                                        return;
                                                                    }

                                                                    // Toggle màu icon
                                                                    let icon = this;
                                                                    if(icon.getAttribute('fill') === 'red'){
                                                                        icon.setAttribute('fill', 'none');
                                                                        icon.setAttribute('stroke', 'currentColor');
                                                                    } else {
                                                                        icon.setAttribute('fill', 'red');
                                                                        icon.setAttribute('stroke', 'white');
                                                                    }

                                                                    // Submit form ẩn
                                                                    document.getElementById('favorite-form-{{ $product->id }}').submit();
                                                                "
                                                            >
                                                                <path d="M20.8 4.6c-1.5-1.4-3.9-1.4-5.4 0l-.9.9-.9-.9c-1.5-1.4-3.9-1.4-5.4 0-1.6 1.5-1.6 4 0 5.5l6.3 6.2 6.3-6.2c1.6-1.5 1.6-4 0-5.5z" />
                                                            </svg>

                                                            <form id="favorite-form-{{ $product->id }}" action="{{ route('account.favorites.toggle') }}" method="POST" class="d-none">
                                                                @csrf
                                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <h3 class="title-product">
                                                        <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                                                    </h3>
                                                    <div class="price-product">
                                                        @if ($variant && $variant->sale > 0 && $variant->sale < $variant->price)
                                                            <ins><span>{{ number_format($variant->sale, 0, ',', '.') }} VND</span></ins>
                                                            <del><span>{{ number_format($variant->price, 0, ',', '.') }} VND</span></del>
                                                        @elseif ($variant)
                                                            <ins><span>{{ number_format($variant->price, 0, ',', '.') }} VND</span></ins>
                                                        @else
                                                            <ins><span>Liên hệ</span></ins> {{-- Trường hợp không có biến thể --}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="link-product">
                                    <a href="{{ optional($parentVN)->slug ? route('categories.index', $parentVN->slug) : '#' }}" class="all-product">
                                        Xem tất cả
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- TỰ HÀO VIỆT NAM ƠI -->

                <!-- Sắm ngay kẻo lỡ -->
                <section class="home-new-prod">
                    <div class="title-section">Sắm ngay kẻo lỡ</div>
                    <div class="exclusive-tabs">
                        <div class="exclusive-head">
                            <ul>
                                <li class="exclusive-tab active arrival-tab" data-tab="tab-women">
                                    XINH TƯƠI RẠNG NGỜI
                                </li>
                                <li class="exclusive-tab arrival-tab" data-tab="tab-men">
                                    PHONG CÁCH CỰC CHẤT
                                </li>
                            </ul>
                        </div>

                        <div class="exclusive-content">
                            <div class="exclusive-inner active" id="tab-women">
                                <div class="list-products new-prod-slider owl-carousel">
                                    @foreach ($productsNu as $product)
                                        @php
                                            $variant = $product->variants->first(); // hoặc chọn variant theo logic khác

                                            $favorites = Auth::check() ? Auth::user()->favorites->pluck('product_id')->toArray() : [];
                                            $isFavorite = in_array($product->id, $favorites);
                                        @endphp

                                        <div class="item-new-prod">
                                            <div class="product" data-product-id="{{ $product->id }}">
                                                <div class="thumb-product">
                                                    <a href="{{ route('products.show', $product->id) }}">
                                                        <img class="product-img" src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}">
                                                        {{-- <img class="hover-img" src=""> --}}
                                                    </a>
                                                </div>
                                                <div class="info-product">
                                                    <div class="list-color">
                                                        <ul>
                                                            @foreach ($product->variants->unique('color_id') as $colorVariant)
                                                                <li class="{{ $loop->first ? 'checked' : '' }}" data-color-id="{{ $colorVariant->color_id }}">
                                                                    <a href=""
                                                                        class="color-picker"
                                                                        data-image="{{ asset('storage/' . $colorVariant->image) }}"
                                                                        data-price="{{ $colorVariant->price }}"
                                                                        data-sale="{{ $colorVariant->sale }}"
                                                                        data-product="{{ $product->id }}"
                                                                        data-color-name="{{ $colorVariant->color->name ?? '' }}">
                                                                        @php $needsBorder = strtolower($colorVariant->color->color_code) === '#ffffff' ? '1' : '0'; @endphp
                                                                        <span class="color-swatch" data-color="{{ $colorVariant->color->color_code }}" data-has-border="{{ $needsBorder }}"></span>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>

                                                        <div style="display:inline-block; cursor:pointer;">
                                                            <svg
                                                                id="favorite-icon-{{ $product->id }}"
                                                                width="24" height="24"
                                                                fill="{{ $isFavorite ? 'red' : 'none' }}"
                                                                stroke="{{ $isFavorite ? 'white' : 'currentColor' }}"
                                                                stroke-width="1"
                                                                onclick="
                                                                    event.preventDefault();
                                                                    let userLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
                                                                    const toast = document.getElementById('toast');
                                                                    if(!userLoggedIn){
                                                                        document.getElementById('toast-message').innerText = 'Vui lòng đăng nhập để thêm sản phẩm yêu thích.';
                                                                        toast.style.display = 'flex';
                                                                        toast.style.opacity = '1';
                                                                        setTimeout(()=> {
                                                                            toast.style.opacity = '0';
                                                                            setTimeout(()=>{ toast.style.display='none'; }, 400);
                                                                        }, 3000);
                                                                        return;
                                                                    }

                                                                    // Toggle màu icon
                                                                    let icon = this;
                                                                    if(icon.getAttribute('fill') === 'red'){
                                                                        icon.setAttribute('fill', 'none');
                                                                        icon.setAttribute('stroke', 'currentColor');
                                                                    } else {
                                                                        icon.setAttribute('fill', 'red');
                                                                        icon.setAttribute('stroke', 'white');
                                                                    }

                                                                    // Submit form ẩn
                                                                    document.getElementById('favorite-form-{{ $product->id }}').submit();
                                                                "
                                                            >
                                                                <path d="M20.8 4.6c-1.5-1.4-3.9-1.4-5.4 0l-.9.9-.9-.9c-1.5-1.4-3.9-1.4-5.4 0-1.6 1.5-1.6 4 0 5.5l6.3 6.2 6.3-6.2c1.6-1.5 1.6-4 0-5.5z" />
                                                            </svg>

                                                            <form id="favorite-form-{{ $product->id }}" action="{{ route('account.favorites.toggle') }}" method="POST" class="d-none">
                                                                @csrf
                                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <h3 class="title-product">
                                                        <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                                                    </h3>
                                                    <div class="price-product">
                                                        @if ($variant && $variant->sale > 0 && $variant->sale < $variant->price)
                                                            <ins><span>{{ number_format($variant->sale, 0, ',', '.') }} VND</span></ins>
                                                            <del><span>{{ number_format($variant->price, 0, ',', '.') }} VND</span></del>
                                                        @elseif ($variant)
                                                            <ins><span>{{ number_format($variant->price, 0, ',', '.') }} VND</span></ins>
                                                        @else
                                                            <ins><span>Liên hệ</span></ins> {{-- Trường hợp không có biến thể --}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="link-product">
                                    <a href="{{ optional($parentNu)->slug ? route('categories.index', $parentNu->slug) : '#' }}" class="all-product">
                                        Xem tất cả Nữ
                                    </a>
                                </div>
                            </div>
                            <div class="exclusive-inner" id="tab-men">
                                <div class="list-products new-prod-slider owl-carousel">
                                    @foreach ($productsNam as $product)
                                        @php
                                            $variant = $product->variants->first(); // hoặc chọn variant theo logic khác

                                            $favorites = Auth::check() ? Auth::user()->favorites->pluck('product_id')->toArray() : [];
                                            $isFavorite = in_array($product->id, $favorites);
                                        @endphp

                                        <div class="item-new-prod">
                                            <div class="product" data-product-id="{{ $product->id }}">
                                                <div class="thumb-product">
                                                    <a href="{{ route('products.show', $product->id) }}">
                                                        <img class="product-img" src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}">
                                                        {{-- <img class="hover-img" src=""> --}}
                                                    </a>
                                                </div>
                                                <div class="info-product">
                                                    <div class="list-color">
                                                        <ul>
                                                            @foreach ($product->variants->unique('color_id') as $colorVariant)
                                                                <li class="{{ $loop->first ? 'checked' : '' }}" data-color-id="{{ $colorVariant->color_id }}">
                                                                    <a href=""
                                                                        class="color-picker"
                                                                        data-image="{{ asset('storage/' . $colorVariant->image) }}"
                                                                        data-price="{{ $colorVariant->price }}"
                                                                        data-sale="{{ $colorVariant->sale }}"
                                                                        data-product="{{ $product->id }}"
                                                                        data-color-name="{{ $colorVariant->color->name ?? '' }}">
                                                                        @php $needsBorder = strtolower($colorVariant->color->color_code) === '#ffffff' ? '1' : '0'; @endphp
                                                                        <span class="color-swatch" data-color="{{ $colorVariant->color->color_code }}" data-has-border="{{ $needsBorder }}"></span>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>

                                                        <div style="display:inline-block; cursor:pointer;">
                                                            <svg
                                                                id="favorite-icon-{{ $product->id }}"
                                                                width="24" height="24"
                                                                fill="{{ $isFavorite ? 'red' : 'none' }}"
                                                                stroke="{{ $isFavorite ? 'white' : 'currentColor' }}"
                                                                stroke-width="1"
                                                                onclick="
                                                                    event.preventDefault();
                                                                    let userLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
                                                                    const toast = document.getElementById('toast');
                                                                    if(!userLoggedIn){
                                                                        document.getElementById('toast-message').innerText = 'Vui lòng đăng nhập để thêm sản phẩm yêu thích.';
                                                                        toast.style.display = 'flex';
                                                                        toast.style.opacity = '1';
                                                                        setTimeout(()=> {
                                                                            toast.style.opacity = '0';
                                                                            setTimeout(()=>{ toast.style.display='none'; }, 400);
                                                                        }, 3000);
                                                                        return;
                                                                    }

                                                                    // Toggle màu icon
                                                                    let icon = this;
                                                                    if(icon.getAttribute('fill') === 'red'){
                                                                        icon.setAttribute('fill', 'none');
                                                                        icon.setAttribute('stroke', 'currentColor');
                                                                    } else {
                                                                        icon.setAttribute('fill', 'red');
                                                                        icon.setAttribute('stroke', 'white');
                                                                    }

                                                                    // Submit form ẩn
                                                                    document.getElementById('favorite-form-{{ $product->id }}').submit();
                                                                "
                                                            >
                                                                <path d="M20.8 4.6c-1.5-1.4-3.9-1.4-5.4 0l-.9.9-.9-.9c-1.5-1.4-3.9-1.4-5.4 0-1.6 1.5-1.6 4 0 5.5l6.3 6.2 6.3-6.2c1.6-1.5 1.6-4 0-5.5z" />
                                                            </svg>

                                                            <form id="favorite-form-{{ $product->id }}" action="{{ route('account.favorites.toggle') }}" method="POST" class="d-none">
                                                                @csrf
                                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <h3 class="title-product">
                                                        <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                                                    </h3>
                                                    <div class="price-product">
                                                        @if ($variant && $variant->sale > 0 && $variant->sale < $variant->price)
                                                            <ins><span>{{ number_format($variant->sale, 0, ',', '.') }} VND</span></ins>
                                                            <del><span>{{ number_format($variant->price, 0, ',', '.') }} VND</span></del>
                                                        @elseif ($variant)
                                                            <ins><span>{{ number_format($variant->price, 0, ',', '.') }} VND</span></ins>
                                                        @else
                                                            <ins><span>Liên hệ</span></ins> {{-- Trường hợp không có biến thể --}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="link-product">
                                    <a href="{{ optional($parentNam)->slug ? route('categories.index', $parentNam->slug) : '#' }}" class="all-product">
                                        Xem tất cả Nam
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Sắm ngay kẻo lỡ -->

                <!--Slider Trang chủ - Giữa trang-->
                @if ($banners_mid_home->count() > 0)
                    <section class="home-banner bg-before">
                        <div id="banner-mid-home" class="slider-banner owl-carousel">
                            @foreach ($banners_mid_home as $banner)
                                <div class="item-banner">
                                    <a href="{{ $banner->product_link ?? '#' }}">
                                        <img src="{{ asset('storage/' . $banner->image) }}" style="height: 529px;"  alt="{{ $banner->name }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
                <!--/Slider-->

                <!-- BỘ SƯU TẬP GIA ĐÌNH -->
                <section class="home-new-prod">
                    <div class="title-section">
                        BỘ SƯU TẬP GIA ĐÌNH
                    </div>

                    <div class="exclusive-tabs">
                        <div class="exclusive-content">
                            <div class="exclusive-inner active">
                                <div class="list-products new-prod-slider owl-carousel">
                                    @foreach ($productsFML as $product)
                                        @php
                                            $variant = $product->variants->first(); // hoặc chọn variant theo logic khác

                                            $favorites = Auth::check() ? Auth::user()->favorites->pluck('product_id')->toArray() : [];
                                            $isFavorite = in_array($product->id, $favorites);
                                        @endphp

                                        <div class="item-new-prod">
                                            <div class="product" data-product-id="{{ $product->id }}">
                                                <div class="thumb-product">
                                                    <a href="{{ route('products.show', $product->id) }}">
                                                        <img class="product-img" src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}">
                                                        {{-- <img class="hover-img" src=""> --}}
                                                    </a>
                                                </div>
                                                <div class="info-product">
                                                    <div class="list-color">
                                                        <ul>
                                                            @foreach ($product->variants->unique('color_id') as $colorVariant)
                                                                <li class="{{ $loop->first ? 'checked' : '' }}" data-color-id="{{ $colorVariant->color_id }}">
                                                                    <a href=""
                                                                        class="color-picker"
                                                                        data-image="{{ asset('storage/' . $colorVariant->image) }}"
                                                                        data-price="{{ $colorVariant->price }}"
                                                                        data-sale="{{ $colorVariant->sale }}"
                                                                        data-product="{{ $product->id }}"
                                                                        data-color-name="{{ $colorVariant->color->name ?? '' }}">
                                                                        @php $needsBorder = strtolower($colorVariant->color->color_code) === '#ffffff' ? '1' : '0'; @endphp
                                                                        <span class="color-swatch" data-color="{{ $colorVariant->color->color_code }}" data-has-border="{{ $needsBorder }}"></span>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>

                                                        <div style="display:inline-block; cursor:pointer;">
                                                            <svg
                                                                id="favorite-icon-{{ $product->id }}"
                                                                width="24" height="24"
                                                                fill="{{ $isFavorite ? 'red' : 'none' }}"
                                                                stroke="{{ $isFavorite ? 'white' : 'currentColor' }}"
                                                                stroke-width="1"
                                                                onclick="
                                                                    event.preventDefault();
                                                                    let userLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
                                                                    const toast = document.getElementById('toast');
                                                                    if(!userLoggedIn){
                                                                        document.getElementById('toast-message').innerText = 'Vui lòng đăng nhập để thêm sản phẩm yêu thích.';
                                                                        toast.style.display = 'flex';
                                                                        toast.style.opacity = '1';
                                                                        setTimeout(()=> {
                                                                            toast.style.opacity = '0';
                                                                            setTimeout(()=>{ toast.style.display='none'; }, 400);
                                                                        }, 3000);
                                                                        return;
                                                                    }

                                                                    // Toggle màu icon
                                                                    let icon = this;
                                                                    if(icon.getAttribute('fill') === 'red'){
                                                                        icon.setAttribute('fill', 'none');
                                                                        icon.setAttribute('stroke', 'currentColor');
                                                                    } else {
                                                                        icon.setAttribute('fill', 'red');
                                                                        icon.setAttribute('stroke', 'white');
                                                                    }

                                                                    // Submit form ẩn
                                                                    document.getElementById('favorite-form-{{ $product->id }}').submit();
                                                                "
                                                            >
                                                                <path d="M20.8 4.6c-1.5-1.4-3.9-1.4-5.4 0l-.9.9-.9-.9c-1.5-1.4-3.9-1.4-5.4 0-1.6 1.5-1.6 4 0 5.5l6.3 6.2 6.3-6.2c1.6-1.5 1.6-4 0-5.5z" />
                                                            </svg>

                                                            <form id="favorite-form-{{ $product->id }}" action="{{ route('account.favorites.toggle') }}" method="POST" class="d-none">
                                                                @csrf
                                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <h3 class="title-product">
                                                        <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                                                    </h3>
                                                    <div class="price-product">
                                                        @if ($variant && $variant->sale > 0 && $variant->sale < $variant->price)
                                                            <ins><span>{{ number_format($variant->sale, 0, ',', '.') }} VND</span></ins>
                                                            <del><span>{{ number_format($variant->price, 0, ',', '.') }} VND</span></del>
                                                        @elseif ($variant)
                                                            <ins><span>{{ number_format($variant->price, 0, ',', '.') }} VND</span></ins>
                                                        @else
                                                            <ins><span>Liên hệ</span></ins> {{-- Trường hợp không có biến thể --}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="link-product">
                                    <a href="{{ optional($parentFML)->slug ? route('categories.index', $parentFML->slug) : '#' }}" class="all-product">
                                        Xem tất cả
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- BỘ SƯU TẬP GIA ĐÌNH -->

                <!-- Brand Trang chủ - Cuối trang-->
                @if ($banners_bottom_home->count() > 0)
                    <section class="list-ads-brand">
                        <div id="banner-bottom-home" class="slider-ads-brand owl-carousel">
                            @foreach ($banners_bottom_home as $banner)
                                <div class="item-slider-ads">
                                    <a href="{{ $banner->product_link ?? '#' }}">
                                        <img src="{{ asset('storage/' . $banner->image) }}"  style="width: 666px; height: 280px;" alt="{{ $banner->name }}"/>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
                <!-- End Brand -->
            </div>
        </main>
    </body>
@endsection

@section('scripts')
    <!-- Chatbot assets -->
    <link rel="stylesheet" href="{{ asset('css/main-chatbot.css') }}">
    <script src="{{ asset('js/ui-chatbot.js') }}" defer></script>
    <script src="{{ asset('js/main-chatbot.js') }}" defer></script>

    <!-- Chatbot markup -->
    <div id="nf-chatbot" class="nf-chatbot" aria-live="polite" aria-label="Hỗ trợ NovaFashion">
        <div class="nf-chatbot__header">
            <div class="nf-chatbot__left">
                <button class="nf-chatbot__back" type="button" aria-label="Quay lại danh sách">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <div class="nf-chatbot__brand">
                    <span class="nf-chatbot__dot"></span>
                    <span class="nf-chatbot__title">Nova AI</span>
                </div>
            </div>
            <button class="nf-chatbot__close" type="button" aria-label="Đóng">×</button>
        </div>
        <div class="nf-chatbot__users" aria-label="Danh sách người dùng">
            <div class="nf-chatbot__users-list">
                <button class="nf-chatbot__user" data-user="Nova AI">
                    <div class="nf-user__avatar">NF</div>
                    <div class="nf-user__meta"><div class="nf-user__name">Nova AI</div><div class="nf-user__desc">Trợ lý tư vấn sản phẩm</div></div>
                </button>
                <!-- Tạm thời ẩn Admin chat để tập trung vào AI
                <button class="nf-chatbot__user" data-user="Admin">
                    <div class="nf-user__avatar">A</div>
                    <div class="nf-user__meta"><div class="nf-user__name">Admin</div><div class="nf-user__desc">CSKH NovaFashion</div></div>
                </button>
                -->
            </div>
        </div>
        <div class="nf-chatbot__body"></div>
        <div class="nf-chatbot__footer">
            <div class="nf-input-wrap" role="group" aria-label="Soạn tin nhắn">
                <input type="text" class="nf-chatbot__input" placeholder="Nhập tin nhắn...">
                <div class="nf-input-actions">
                    <button class="nf-btn nf-send nf-chatbot__send" type="button" aria-label="Gửi" disabled>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22 2L11 13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 2l-7 20-4-9-9-4 20-7z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round" fill="currentColor"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <button id="nf-chatbot-toggle" class="nf-chatbot-toggle" aria-label="Mở chat Nova AI">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
            stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" 
                d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
        </svg>
    </button>

    {{-- JS xử lý Banner, Slider --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Đợi DOM load xong
            setTimeout(function() {
                // Kiểm tra jQuery và Owl Carousel
                if (typeof $ === 'undefined' || typeof $.fn.owlCarousel === 'undefined') {
                    console.log('jQuery hoặc Owl Carousel chưa được load');
                    return;
                }

                // Danh sách các slider cần khởi tạo
                const sliders = ['#banner-top-home', '#banner-mid-home', '#banner-bottom-home'];

                sliders.forEach(function(selector) {
                    var $banner = $(selector);
                    if (!$banner.length) return;

                    // Kiểm tra xem Owl đã được khởi tạo chưa
                    if ($banner.hasClass('owl-loaded')) {
                        // Cố gắng bật autoplay nếu đang tắt
                        var owlData = $banner.data('owl.carousel');
                        if (owlData) {
                            if (!owlData.settings.autoplay) {
                                $banner.trigger('stop.owl.autoplay');
                                $banner.trigger('play.owl.autoplay', [3000]);
                            }
                        }

                        // Fallback: tự động next slide mỗi 3s nếu vì lý do nào đó autoplay không chạy
                        if (!$banner.data('manualAutoplay')) {
                            var manualId = setInterval(function () {
                                $banner.trigger('next.owl.carousel');
                            }, 3000);
                            $banner.data('manualAutoplay', manualId);

                            $banner.on('mouseenter', function () {
                                var id = $banner.data('manualAutoplay');
                                if (id) clearInterval(id);
                            });
                            $banner.on('mouseleave', function () {
                                var id = setInterval(function () {
                                    $banner.trigger('next.owl.carousel');
                                }, 3000);
                                $banner.data('manualAutoplay', id);
                            });
                        }
                        return;
                    }

                    // Nếu chưa có Owl, khởi tạo mới
                    var slideCount = $banner.find('.item-banner').length;

                    if (slideCount > 1) {
                        $banner.owlCarousel({
                            items: 1,
                            loop: true,
                            margin: 0,
                            dots: true,
                            nav: true,
                            autoplay: true,
                            autoplayTimeout: 3000,
                            autoplayHoverPause: true,
                            smartSpeed: 600,
                            navText: [
                                '<i class="bi bi-chevron-left"></i>',
                                '<i class="bi bi-chevron-right"></i>'
                            ],
                            responsive: {
                                0: { items: 1 },
                                600: { items: 1 },
                                1000: { items: 1 }
                            }
                        });
                    }
                });
            }, 1000);
        });
    </script>

    <!--Copy Voucher -->
    <script>
        function copyVoucherCode(id) {
            const codeText = document.getElementById('voucher-code-' + id).innerText;
            navigator.clipboard.writeText(codeText).then(() => {
                const msg = document.getElementById('copied-message-' + id);
                msg.classList.remove('d-none');
                setTimeout(() => {
                    msg.classList.add('d-none');
                }, 3000);
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kiểm tra xem Swiper đã được load chưa
            if (typeof Swiper !== 'undefined') {
                const swiper = new Swiper(".mySwiper", {
                    slidesPerView: 1.2,
                    spaceBetween: 16,
                    breakpoints: {
                        576: { slidesPerView: 2.2 },
                        768: { slidesPerView: 3.2 },
                        992: { slidesPerView: 4.2 }
                    },
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                });
            } else {
                console.log('Swiper chưa được load');
            }
        });
    </script>

    {{-- JS xử lý khi click màu -> hiển thị ảnh + giá tương ứng --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.color-picker').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const productId = this.dataset.product;
                    const image = this.dataset.image;
                    const price = parseInt(this.dataset.price) || 0;
                    const sale = parseInt(this.dataset.sale) || 0;

                    const wrapper = document.querySelector(`.product[data-product-id="${productId}"]`);
                    if (!wrapper) {
                        console.log('Không tìm thấy product wrapper:', productId);
                        return;
                    }

                    // 1. Đổi ảnh sản phẩm
                    const productImg = wrapper.querySelector('.product-img');
                    if (productImg) {
                        productImg.src = image;
                    }

                    // 2. Đổi giá
                    const priceEl = wrapper.querySelector('.price-product ins span');
                    const saleEl = wrapper.querySelector('.price-product del span');

                    if (priceEl) {
                        if (sale > 0 && sale < price) {
                            priceEl.textContent = sale.toLocaleString('vi-VN') + ' VND';
                            if (saleEl) {
                                saleEl.textContent = price.toLocaleString('vi-VN') + ' VND';
                                saleEl.parentElement.style.display = 'inline';
                            }
                        } else {
                            priceEl.textContent = price.toLocaleString('vi-VN') + ' VND';
                            if (saleEl) {
                                saleEl.textContent = '';
                                saleEl.parentElement.style.display = 'none';
                            }
                        }
                    }

                    // 3. Đánh dấu màu được chọn
                    wrapper.querySelectorAll('.list-color li').forEach(li => li.classList.remove('checked'));
                    this.closest('li').classList.add('checked');
                });
            });
        });
    </script>

    {{-- JS xử lý khi ĐĂNG NHẬP thực hiển thểm/bỏ sản phẩm yêu thích --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                const toast = document.getElementById('toast');
                document.getElementById('toast-message').innerText = "{{ session('success') }}";
                toast.style.display = 'flex';
                toast.style.opacity = '1';
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => { toast.style.display = 'none'; }, 400);
                }, 3000);
            @elseif(session('error'))
                const toast = document.getElementById('toast');
                document.getElementById('toast-message').innerText = "{{ session('error') }}";
                toast.style.display = 'flex';
                toast.style.opacity = '1';
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => { toast.style.display = 'none'; }, 400);
                }, 3000);
            @endif
        });
    </script>

    {{-- ========================================
         CSS CHO CHỌN MÀU SẮC
    ======================================== --}}
    <style>
        .list-color ul { gap: 8px !important; }
        .list-color li a .color-swatch { 
            display: inline-block !important;
            width: 20px !important; 
            height: 20px !important; 
            border-radius: 50% !important; 
            box-shadow: 0 1px 2px rgba(0,0,0,.06) !important; 
            transition: transform .15s ease, box-shadow .15s ease, outline-color .15s ease !important; 
        }
        .list-color li a:hover .color-swatch { 
            transform: translateY(-1px) !important; 
            box-shadow: 0 3px 10px rgba(0,0,0,.12) !important; 
        }
        .list-color li.checked a .color-swatch { 
            outline: 2px solid #222 !important; 
            outline-offset: 2px !important; 
        }
        
        /* Ẩn tất cả các loại tích ✓ trong màu sắc */
        .list-color li.checked a .color-swatch::before,
        .list-color li.checked a .color-swatch::after {
            display: none !important;
            content: none !important;
        }
        
        /* Ẩn tất cả icon check có thể có */
        .list-color li.checked a .color-swatch i,
        .list-color li.checked a .color-swatch svg,
        .list-color li.checked a .color-swatch .check-icon,
        .list-color li.checked a .color-swatch .tick-icon {
            display: none !important;
        }
        
        /* Đảm bảo không có text nào trong màu */
        .list-color li.checked a .color-swatch {
            font-size: 0 !important;
            line-height: 0 !important;
        }
        
        /* CSS mạnh để ẩn tất cả tích có thể có */
        .list-color li.checked a .color-swatch * {
            display: none !important;
        }
        
        /* Ẩn tất cả pseudo-elements */
        .list-color li.checked a .color-swatch::before,
        .list-color li.checked a .color-swatch::after,
        .list-color li.checked a::before,
        .list-color li.checked a::after {
            display: none !important;
            content: none !important;
            visibility: hidden !important;
        }
        @media (max-width: 576px) {
            .list-color li a .color-swatch { 
                width: 18px !important; 
                height: 18px !important; 
            }
        }
    </style>

    {{-- ========================================
         TẠO MÀU SẮC CHO COLOR SWATCH
    ======================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Đợi một chút để đảm bảo DOM đã sẵn sàng
            setTimeout(function() {
                document.querySelectorAll('.color-swatch').forEach(function(sw){
                    var color = sw.getAttribute('data-color') || '#ffffff';
                    var hasBorder = sw.getAttribute('data-has-border') === '1';
                    sw.style.backgroundColor = color;
                    sw.style.border = hasBorder ? '1px solid #ccc' : '1px solid transparent';
                });
            }, 100);
        });
    </script>
@endsection
