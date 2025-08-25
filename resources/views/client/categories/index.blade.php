@extends('client.layouts.app')

@section('title', 'Danh Mục Sản Phẩm')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <form name="" id="" enctype="" method="get" action="">
                <div id="products" class="row">
                    <div class="container">
                        <div class="breadcrumb-products">
                            <ol class="breadcrumb__list">
                                <li class="breadcrumb__item"><a class="breadcrumb__link" href="{{ route('home') }}">Trang chủ</a></li>
                                @isset($breadcrumbs)
                                    @foreach ($breadcrumbs as $crumb)
                                        <li class="breadcrumb__item">
                                            @if (!$loop->last)
                                                <a href="{{ route('categories.index', $crumb->slug) }}" class="breadcrumb__link" title="{{ $crumb->name }}">{{ $crumb->name }}</a>
                                            @else
                                                <span class="breadcrumb__link" aria-current="page">{{ $crumb->name }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                @endisset
                            </ol>
                        </div>

                        <section class="section-list-products">
                            <div class="box-products d-flex">
                                @if ($products->count())
                                    <div class="sidebar-prod sidebar-prod-pc">
                                        <div class="filter-by-side">
                                            <form method="GET" action="{{ route('categories.index', [$slug, $subslug, $childslug]) }}">
                                                <ul class="list-side">
                                                    {{-- Size --}}
                                                    <li class="item-side item-side-size">
                                                        <p class="item-side-title">Size<span class="icon-ic_plus"></span><span class="icon-ic_minus"></span></p>
                                                        <div class="sub-list-side">
                                                            @foreach ($sizes as $size)
                                                                <label class="item-sub-list po-relative">
                                                                    <input class="field-cat size-checkbox" type="checkbox" name="att_size[]"
                                                                        value="{{ $size->size_code }}"
                                                                        {{ in_array($size->size_code, (array) request()->input('att_size')) ? 'checked' : '' }}>
                                                                    <span class="item-sub-title item-sub-pr">{{ $size->name }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </li>


                                                    {{-- Color --}}
                                                    <li class="item-side item-side-color">
                                                        <p class="item-side-title">Màu sắc<span class="icon-ic_plus"></span><span class="icon-ic_minus"></span></p>
                                                        <div class="sub-list-side">
                                                            @foreach ($colors as $color)
                                                                <label class="color-checkbox-wrapper" title="{{ $color->name }}">
                                                                    <input type="checkbox" name="att_color[]" value="{{ $color->id }}"
                                                                        {{ in_array($color->id, (array) request()->input('att_color')) ? 'checked' : '' }}>
                                                                    <span class="color-circle" title="{{ $color->name }}" style="background-color: {{ $color->color_code }};"></span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </li>

                                                    {{-- Price --}}
                                                    <li class="item-side item-side-price">
                                                        <p class="item-side-title">Mức giá <span class="icon-ic_plus"></span><span class="icon-ic_minus"></span></p>
                                                        <div class="sub-list-side" style="padding: 0px 0;">
                                                            <div class="value-range" style="display: flex; gap: 10px; align-items: center;">
                                                                <div style="flex: 1;">
                                                                    <label style="display: block; font-size: 13px; margin-bottom: 5px;">Giá từ:</label>
                                                                    <input type="text" name="product_price_from"
                                                                        value="{{ request('product_price_from') ? number_format(request('product_price_from'), 0, '', '.') : '' }}"
                                                                        oninput="this.value = this.value.replace(/\D/g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')"
                                                                        style="width: 100%; padding: 6px 8px; border: 1px solid #ccc; border-radius: 4px;">
                                                                </div>
                                                                <div style="flex: 1;">
                                                                    <label style="display: block; font-size: 13px; margin-bottom: 5px;">Đến:</label>
                                                                    <input type="text" name="product_price_to"
                                                                        value="{{ request('product_price_to') ? number_format(request('product_price_to'), 0, '', '.') : '' }}"
                                                                        oninput="this.value = this.value.replace(/\D/g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')"
                                                                        style="width: 100%; padding: 6px 8px; border: 1px solid #ccc; border-radius: 4px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>

                                                <div class="col-md-12 p-0" style="margin-top: 30px">
                                                    <div class="row m-0 p-0">
                                                        {{-- <a href="{{ route('categories.index', [$slug, $subslug, $childslug]) }}"
                                                            class="btn btn--large btn--outline" style="font-size: 13px;padding: 10px 20px;">
                                                            Bỏ lọc
                                                        </a> --}}
                                                        <div class="col-6">
                                                            <button type="reset" class="btn btn--large btn--outline" style="font-size: 13px;padding: 10px 20px;">Bỏ lọc</button>
                                                        </div>
                                                        <div class="col-6">
                                                            <button type="submit" class="btn btn--large" style="font-size: 13px;padding: 10px 20px;">Lọc</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="main-prod">
                                        <div class="top-main-prod">
                                            <h1 class="sub-title-main">{{ $category->name }}</h1>
                                            <div class="filter-prod">
                                                <div class="item-filter">
                                                    <span>Sắp xếp theo <i class="icon-ic_down"></i></span>
                                                    <div class="list-number-row">
                                                        <div class="item-number-row">
                                                            <a href="javascript:void(0)" class="sel-order-option" data-value="">Mặc định</a>
                                                        </div>
                                                                                <div class="item-number-row">
                                                            <a href="javascript:void(0)" class="sel-order-option" data-value="latest">Mới nhất</a>
                                                        </div>
                                                                    <div class="item-number-row">
                                                            <a href="javascript:void(0)" class="sel-order-option" data-value="price_desc">Giá: cao đến thấp</a>
                                                        </div>
                                                                    <div class="item-number-row">
                                                            <a href="javascript:void(0)" class="sel-order-option" data-value="price_asc">Giá: thấp đến cao</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="sidebar-prod">
                                                    <div class="filter-search">
                                                        <i class="icon-ic_filter"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="sub-main-prod">
                                            <div class="list-products list-products-cat d-flex">
                                                @foreach ($products as $product)
                                                    @php
                                                        $variant = $product->variants->first(); // hoặc chọn variant theo logic khác

                                                        $favorites = Auth::check() ? Auth::user()->favorites->pluck('product_id')->toArray() : [];
                                                        $isFavorite = in_array($product->id, $favorites);
                                                    @endphp

                                                    <div class="item-cat-product">
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
                                                                            <li class="{{ $loop->first ? 'checked' : '' }} ">
                                                                                <a href=""
                                                                                    class="color-picker"
                                                                                    data-image="{{ asset('storage/' . $colorVariant->image) }}"
                                                                                    data-price="{{ $colorVariant->price }}"
                                                                                    data-sale="{{ $colorVariant->sale }}"
                                                                                    data-product="{{ $product->id }}">
                                                                                    <span style="display:inline-block;
                                                                                        width: 20px;
                                                                                        height: 20px;
                                                                                        border-radius: 50%;
                                                                                        background-color: {{ $colorVariant->color->color_code }};
                                                                                        border: 1px solid {{ strtolower($colorVariant->color->color_code) === '#ffffff' ? '#ccc' : 'transparent' }};">
                                                                                    </span>
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
                                                                    <a href="#">{{ $product->name }}</a>
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
                                                            <div class="add-to-cart">
                                                                <a href="javascript:void(0)"><i class="icon-ic_shopping-bag"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <ul class="list-inline-pagination">
                                                <li><a href="#">«</a></li>
                                                <li id="products_active_ts"><a href="#">1</a></li>
                                                <li><a href="https://ivymoda.com/danh-muc/ao-nu/2">2</a></li>
                                                <li><a href="https://ivymoda.com/danh-muc/ao-nu/3">3</a></li>
                                                <li><a href="https://ivymoda.com/danh-muc/ao-nu/4">4</a></li>
                                                <li><a href="https://ivymoda.com/danh-muc/ao-nu/5">5</a></li>
                                                <li><a href="https://ivymoda.com/danh-muc/ao-nu/2">»</a></li>
                                                <li class="last-page"><a href="https://ivymoda.com/danh-muc/ao-nu/24">Trang cuối</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted">Không tìm thấy sản phẩm phù hợp !</p>
                                @endif
                            </div>
                        </section><br><hr><br>

                        <!-- Brand Danh mục - Cuối trang-->
                        @if ($banners_bottom_category->count() > 0)
                            <section class="list-ads-brand">
                                <div id="banner-bottom-category" class="slider-ads-brand owl-carousel">
                                    @foreach ($banners_bottom_category as $banner)
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
                </div>
            </form>
        </div>
    </main>
@endsection

@section('scripts')
    <style>
        /* Wrapper cho từng màu */
        .color-checkbox-wrapper {
            display: inline-block;
            position: relative;
            cursor: pointer;
        }

        /* Checkbox ẩn */
        .color-checkbox-wrapper input[type="checkbox"] {
            display: none;
        }

        /* Hình tròn màu */
        .color-circle {
            display: inline-block;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #ccc;
            transition: all 0.2s;
        }

        /* Khi hover */
        .color-checkbox-wrapper:hover .color-circle {
            transform: scale(1.1);
            border-color: #888;
        }

        /* Khi checked */
        .color-checkbox-wrapper input[type="checkbox"]:checked + .color-circle {
            border-color: #000; /* Viền nổi bật khi chọn */
            box-shadow: 0 0 0 2px rgba(0,0,0,0.2);
        }
    </style>
@endsection
