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
                                                        <p class="item-side-title">Size
                                                            <span class="icon-ic_plus"></span>
                                                            <span class="icon-ic_minus" style="display: none;"></span>
                                                        </p>
                                                        <div class="sub-list-side" id="size-filter" style="display: none;">
                                                            @foreach ($sizes as $size)
                                                                <div class="size-option" style="display: inline-block; margin-right: 5px; margin-bottom: 5px;">
                                                                    <input type="checkbox" id="size-{{ $size->id }}" name="att_size[]" 
                                                                        value="{{ $size->size_code }}"
                                                                        {{ in_array($size->size_code, (array) request()->input('att_size', [])) ? 'checked' : '' }} 
                                                                        class="size-checkbox" style="display: none;">
                                                                    <label for="size-{{ $size->id }}" class="size-label" style="display: inline-block; padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; margin: 2px;">
                                                                        {{ $size->name }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </li>


                                                    {{-- Color --}}
                                                    <li class="item-side item-side-color">
                                                        <p class="item-side-title">Màu sắc
                                                            <span class="icon-ic_plus"></span>
                                                            <span class="icon-ic_minus"></span>
                                                        </p>
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
                                                        <p class="item-side-title">Mức giá<span class="icon-ic_plus"></span><span class="icon-ic_minus"></span></p>
                                                        <div class="sub-list-side">
                                                            <div class="price-options">
                                                                <label class="price-option">
                                                                    <input type="radio" name="price_range" value="0-100000" 
                                                                        {{ request('price_range') == '0-100000' ? 'checked' : '' }}>
                                                                    <span>Dưới 100.000đ</span>
                                                                </label>
                                                                <label class="price-option">
                                                                    <input type="radio" name="price_range" value="100000-300000"
                                                                        {{ request('price_range') == '100000-300000' ? 'checked' : '' }}>
                                                                    <span>100.000đ - 300.000đ</span>
                                                                </label>
                                                                <label class="price-option">
                                                                    <input type="radio" name="price_range" value="300000-500000"
                                                                        {{ request('price_range') == '300000-500000' ? 'checked' : '' }}>
                                                                    <span>300.000đ - 500.000đ</span>
                                                                </label>
                                                                <label class="price-option">
                                                                    <input type="radio" name="price_range" value="500000-1000000"
                                                                        {{ request('price_range') == '500000-1000000' ? 'checked' : '' }}>
                                                                    <span>500.000đ - 1.000.000đ</span>
                                                                </label>
                                                                <label class="price-option">
                                                                    <input type="radio" name="price_range" value="1000000-"
                                                                        {{ request('price_range') == '1000000-' ? 'checked' : '' }}>
                                                                    <span>Trên 1.000.000đ</span>
                                                                </label>
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
                                                                                <a href="javascript:void(0)"
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
<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý nút bỏ lọc (đã có ở trên) - giữ lại 1 lần duy nhất
            const resetBtnInit = document.querySelector('button[type="reset"]');
                if (resetBtnInit) {
                    resetBtnInit.addEventListener('click', function(e) {
                        e.preventDefault();

                        @if(request()->is('account/search*'))
                            // Nếu đang ở trang tìm kiếm -> giữ lại từ khóa q, bỏ hết filter
                            window.location.href = "/account/search?q={{ request('q') }}";
                        @else
                            // Nếu đang ở category -> quay về category gốc
                            window.location.href = "{{ route('categories.index', [$slug ?? null, $subslug ?? null, $childslug ?? null]) }}";
                        @endif
                    });
                }

            // Click màu: đổi ảnh + giá + trạng thái checked
            document.querySelectorAll('.color-picker').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const productId = this.dataset.product;
                    const image = this.dataset.image;
                    const price = parseInt(this.dataset.price) || 0;
                    const sale = parseInt(this.dataset.sale) || 0;

                    const wrapper = document.querySelector(`.product[data-product-id="${productId}"]`);
                    if (!wrapper) return;

                    // 1) Đổi ảnh
                    const productImg = wrapper.querySelector('.product-img');
                    if (productImg) productImg.src = image;

                    // 2) Đổi giá
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

                    // 3) Checked state
                    wrapper.querySelectorAll('.list-color li').forEach(li => li.classList.remove('checked'));
                    this.closest('li').classList.add('checked');
                });
            });
        });
    </script>
    
    <style>
        /* Style cho phần lọc size */
        #size-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 10px;
        }
        
        .size-option {
            display: inline-block;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        
        .size-label {
            display: inline-block;
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .size-checkbox:checked + .size-label {
            background-color: #000;
            color: #fff;
            border-color: #000;
        }
        
        .size-checkbox {
            display: none;
        }
        
        /* CSS cho phần lọc giá */
        .price-options {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 10px 0;
        }
        
        .price-option {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #e0e0e0;
        }
        
        .price-option:hover {
            background-color: #f8f9fa;
            border-color: #d0d0d0;
        }
        
        .price-option input[type="radio"] {
            margin-right: 10px;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        
        .price-option span {
            font-size: 14px;
            color: #333;
        }
        
        .price-option input[type="radio"]:checked + span {
            font-weight: 500;
            color: #000;
        }

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
        /* ==============================
           CSS CHO CHỌN MÀU SẮC (PRODUCT LIST)
           ============================== */
        .list-color ul { gap: 8px !important; }
        .list-color li a span {
            display: inline-block !important;
            width: 20px !important;
            height: 20px !important;
            border-radius: 50% !important;
            box-shadow: 0 1px 2px rgba(0,0,0,.06) !important;
            transition: transform .15s ease, box-shadow .15s ease, outline-color .15s ease !important;
        }
        .list-color li a:hover span {
            transform: translateY(-1px) !important;
            box-shadow: 0 3px 10px rgba(0,0,0,.12) !important;
        }
        .list-color li.checked a span {
            outline: 2px solid #222 !important;
            outline-offset: 2px !important;
        }
        @media (max-width: 576px) {
            .list-color li a span {
                width: 18px !important;
                height: 18px !important;
            }
        }
    </style>
@endsection
