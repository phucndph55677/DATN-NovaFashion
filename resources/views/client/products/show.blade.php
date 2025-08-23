@extends('client.layouts.app')

@section('title', 'Sản Phẩm')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div id="product_detail" class="row">
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

                    <div class="product-detail" data-product-id="{{ $product->id }}">
                        <div class="container">
                            <div class="row">
                                @php
                                    $variant = $product->variants->first(); // hoặc chọn variant theo logic khác
                                @endphp

                                <div class="col-md-6">
                                    <div class="product-detail__gallery">
                                        <div class="product-gallery__slide">
                                            <div class="product-gallery__slide--big">
                                                <div class="thumb-product">
                                                    <img class="product-img" style="width: 550px; height: 750px;" src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}">
                                                </div>     
                                            </div>
                                            
                                            <div class="product-gallery__slide--small">
                                                <div class="swiper-nav-prev">
                                                    <span class="icon-ic_down"></span>
                                                </div>
                                                <div class="swiper-nav-next">
                                                    <span class="icon-ic_down"></span>
                                                </div>
                                                <div class="swiper-container swiper">
                                                    <div class="swiper-wrapper">
                                                        @if($product->photoAlbums && $product->photoAlbums->count() > 0)
                                                            @foreach($product->photoAlbums as $album)
                                                                <div class="swiper-slide">
                                                                    <img class="product-photo-album" src="{{ asset('storage/' . $album->image) }}" alt="Album Image">
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            {{-- Nếu không có album thì fallback về ảnh variant hoặc ảnh mặc định --}}
                                                            <div class="swiper-slide">
                                                                <img class="product-photo-album" src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}" alt="Default">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="product-information">
                                        <h1 style="text-transform: uppercase;">{{ $product->name }}</h1>

                                        <div class="price-product">
                                            @if ($variant && $variant->sale > 0 && $variant->sale < $variant->price)
                                                <ins><span>{{ number_format($variant->sale, 0, ',', '.') }} VND</span></ins>
                                                <del><span>{{ number_format($variant->price, 0, ',', '.') }} VND</span></del>
                                            @elseif ($variant && $variant->price > 0)
                                                <ins><span>{{ number_format($variant->price, 0, ',', '.') }} VND</span></ins>
                                                <del style="display: none;"><span></span></del>
                                            @else
                                                <ins><span>Liên hệ</span></ins>
                                                <del style="display: none;"><span></span></del>
                                            @endif
                                        </div>

                                        <div class="product-detail__sub-info">
                                            @php
                                                $avg = $averageRating ?? 0;
                                                $percentage = max(0, min(100, ($avg / 5) * 100));
                                            @endphp
                                            <p style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                                                <span>SKU: <span>{{ $product->product_code }}</span></span>
                                                <span class="sub-info-rating" style="display:flex; align-items:center; gap:8px;">
                                                    <span class="stars-outer">
                                                        <span class="stars-inner" data-width="{{ $percentage }}"></span>
                                                    </span>
                                                    <span>({{ number_format($avg, 1) }}/5 từ {{ $totalReviews }} đánh giá)</span>
                                                </span>
                                            </p>
                                        </div>

                                        <div class="product-category">
                                            <p>
                                                Danh mục: <span>{{ $product->category->name ?? 'Không có danh mục' }}</span>
                                            </p>
                                        </div>

                                        <div class="product-status">
                                            <p>
                                                <span id="product_status" style="color: #dc3545;">
                                                    Vui lòng chọn màu và size
                                                </span>
                                            </p>
                                        </div>

                                        @php
                                            $defaultColorName = optional(optional($product->variants->unique('color_id')->first())->color)->name ?? '';

                                            // Lọc các variant đang cho phép kinh doanh
                                            $activeVariants = $product->variants->where('status', '1'); 
                                        @endphp
                                        <p class="choice-label color-label" style="margin: 8px 0 6px; color:#5f6368;">
                                            Màu sắc: <span class="picked-color-name">{{ $defaultColorName }}</span>
                                        </p>
                                        <div class="list-color">
                                            <ul style="display: flex; gap: 10px;">
                                                @foreach ($activeVariants->unique('color_id') as $colorVariant)
                                                    <li class="{{ $loop->first ? 'checked' : '' }}" data-color-id="{{ $colorVariant->color_id }}">
                                                        <a href="" 
                                                            class="color-picker" 
                                                            data-image="{{ asset('storage/' . $colorVariant->image) }}"
                                                            data-size=""
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
                                        </div>

                                        <p class="choice-label size-label" style="margin: 12px 0 6px; color:#5f6368;">
                                            Kích thước: <span class="picked-size-name"></span>
                                        </p>
                                        <div class="product-size">
                                            @foreach ($activeVariants->groupBy('color_id') as $colorId => $variantsByColor)
                                                <div class="size-group {{ $loop->first ? '' : 'd-none' }}" data-color-id="{{ $colorId }}">
                                                    @foreach ($variantsByColor as $colorVariant)
                                                        <label style="display: inline-block; margin: 5px; padding: 5px 9px; border: 1px solid #ccc; border-radius: 4px;">
                                                            <input type="radio" name="size" value="{{ $colorVariant->size->id }}"
                                                                data-price="{{ $colorVariant->price }}"
                                                                data-sale="{{ $colorVariant->sale }}"
                                                                data-size-id="{{ $colorVariant->size->id }}"
                                                                data-variant-id="{{ $colorVariant->id }}"
                                                                data-quantity="{{ $colorVariant->quantity }}"
                                                                data-size-name="{{ $colorVariant->size->name }}">
                                                            <span class="text-uppercase">{{ $colorVariant->size->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="product-cart">
                                            {{-- Chỉ hiển thị 1 input số lượng --}}
                                            <div class="product-quantity">
                                                <div class="product-quantity-row">
                                                    <span class="quantity-label">Số lượng</span>
                                                    <div class="quantity-control">
                                                        <button type="button" class="quantity-decrease qty-btn" aria-label="Giảm">-</button>
                                                        <input type="number" id="quantity_input" value="1" min="1"class="qty-input" />
                                                        <button type="button" class="quantity-increase qty-btn" aria-label="Tăng">+</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div  style="display: flex; gap: 10px;">                                                
                                                {{-- Form Thêm vào giỏ --}}
                                                <form action="{{ route('carts.add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_variant_id" id="product_variant_id_add" value="{{ $variant->id }}">
                                                    <input type="hidden" name="quantity" id="quantity_add" value="1">
                                                    <button type="submit" class="btn btn--large">Thêm vào giỏ</button>
                                                </form>

                                                {{-- Form Mua hàng --}}
                                                <form action="{{ route('carts.buy') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_variant_id" id="product_variant_id_buy" value="{{ $variant->id }}">
                                                    <input type="hidden" name="quantity" id="quantity_buy" value="1">
                                                    <button type="submit" class="btn btn--large btn--outline">Mua hàng</button>
                                                </form>

                                                {{-- Yêu thích --}}
                                                <button class="btn btn--large btn--outline btn--wishlist">
                                                    <i class="icon-ic_heart"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="product-detail__tab">
                                            <div class="product-detail__tab-header">
                                                <div class="tab-item active">
                                                    <span>GIỚI THIỆU</span>
                                                </div>
                                            </div>
                                            <div class="product-detail__tab-body">
                                                <div class="tab-content hideContent active">
                                                    <p>{{ $product->description }}</p>
                                                </div>

                                                <div class="show-more">
                                                    <a>
                                                        <img class="image-down" src="https://ivymoda.com/assets/images/image-down.png" alt="image down">
                                                        <img class="image-up hideImg" src="https://ivymoda.com/assets/images/image-up.png" alt="image down">
                                                    </a>
                                                    <div class="inline"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="viewed-products">
                        <h1>ĐÁNH GIÁ SẢN PHẨM ({{ $reviews->count() }} đánh giá)</h1>
                        <div class="product-reviews block-border">
                            @foreach($reviews as $review)
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="review-user">
                                            <strong>{{ $review->order->user->name ?? 'Người dùng ẩn danh' }}</strong>
                                            <span class="review-date">{{ $review->created_at->format('d/m/Y H:i:s') }}</span>
                                        </div>
                                        <div class="review-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <span style="color: gold; font-size: 20px;">&#9733;</span>
                                                @else
                                                    <span style="color: #ccc; font-size: 20px;">&#9733;</span>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="review-content">
                                        <p>{{ $review->content }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>              
                    </div>
                    
                    <!-- Trending -->
                    <a href="https://ivymoda.com/danh-muc/dress-day-hoa-tiet-110725">
                        <section class="home-trending box-border bg-before bg-before_02">
                            <div class="img-trending-desktop">
                                <img data-original="https://cotton4u.vn/files/news/2025/07/11/0c185bb21dbd0deca33f5f464c1f4787.webp" alt="BANNER TRENDING" class="lazy" loading="lazy" />
                            </div>
                            <div class="trending-content">
                                <div class="box-trending">
                                    <!--<h3 style="text-transform: capitalize;">trending</h3>-->
                                    <!--<h2>BANNER TRENDING</h2>-->
                                    <!--<p></p>-->
                                </div>
                            </div>
                        </section>
                    </a>
                    <!-- End Trending -->
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    {{-- JS xử lý khi click màu -> hiển thị ảnh + giá tương ứng --}}
    {{-- JS xử lý khi click size + màu -> hiển thị giá tương ứng --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Click màu
            document.querySelectorAll('.color-picker').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    e.preventDefault();

                    const productId = this.dataset.product;
                    const image = this.dataset.image;
                    const price = parseInt(this.dataset.price);
                    const sale = parseInt(this.dataset.sale);
                    const colorId = this.closest('li').dataset.colorId;
                    const colorName = this.dataset.colorName || '';

                    const wrapper = document.querySelector(`.product-detail[data-product-id="${productId}"]`);
                    if (!wrapper) return;

                    // Đổi ảnh
                    wrapper.querySelector('.product-img').src = image;

                    // Cập nhật nhãn màu
                    const colorLabel = wrapper.querySelector('.picked-color-name');
                    if (colorLabel) colorLabel.textContent = colorName;

                    // Đổi giá ban đầu (khi chưa chọn size)
                    const priceEl = wrapper.querySelector('.price-product ins span');
                    const saleEl = wrapper.querySelector('.price-product del');
                    const saleSpanEl = wrapper.querySelector('.price-product del span');
                    
                    if (sale > 0 && sale < price) {
                        priceEl.textContent = sale.toLocaleString('vi-VN') + ' VND';
                        if (saleSpanEl) {
                            saleSpanEl.textContent = price.toLocaleString('vi-VN') + ' VND';
                        }
                        if (saleEl) {
                            saleEl.style.display = 'inline';
                        }
                    } else {
                        priceEl.textContent = price.toLocaleString('vi-VN') + ' VND';
                        if (saleEl) {
                            saleEl.style.display = 'none';
                        }
                    }

                    // Đánh dấu màu
                    wrapper.querySelectorAll('.list-color li').forEach(li => li.classList.remove('checked'));
                    this.closest('li').classList.add('checked');

                    // Hiển thị size theo màu
                    wrapper.querySelectorAll('.product-size .size-group').forEach(group => {
                        group.classList.add('d-none');
                    });
                    wrapper.querySelectorAll(`.product-size .size-group`).forEach(group => {
                        if (group.dataset.colorId == colorId) {
                            group.classList.remove('d-none');
                        }
                    });
                });
            });

            // Click size
            document.querySelectorAll('.product-size input[type="radio"][name="size"]').forEach(function (input) {
                input.addEventListener('change', function () {
                    const price = parseInt(this.dataset.price);
                    const sale = parseInt(this.dataset.sale);
                    const quantity = parseInt(this.dataset.quantity);
                    const sizeName = this.dataset.sizeName || '';
                    const wrapper = this.closest('.product-detail');

                    // Cập nhật nhãn kích thước
                    const sizeLabel = wrapper.querySelector('.picked-size-name');
                    if (sizeLabel) sizeLabel.textContent = sizeName;

                    const priceEl = wrapper.querySelector('.price-product ins span');
                    const saleEl = wrapper.querySelector('.price-product del');
                    const saleSpanEl = wrapper.querySelector('.price-product del span');

                    if (sale > 0 && sale < price) {
                        priceEl.textContent = sale.toLocaleString('vi-VN') + ' VND';
                        if (saleSpanEl) {
                            saleSpanEl.textContent = price.toLocaleString('vi-VN') + ' VND';
                        }
                        if (saleEl) {
                            saleEl.style.display = 'inline';
                        }
                    } else {
                        priceEl.textContent = price.toLocaleString('vi-VN') + ' VND';
                        if (saleEl) {
                            saleEl.style.display = 'none';
                        }
                    }

                    // Cập nhật tình trạng sản phẩm
                    const productStatus = wrapper.querySelector('.product-status span');
                    if (productStatus) {
                        if (quantity > 0) {
                            productStatus.textContent = `Kho hàng: ${quantity.toLocaleString('vi-VN')} sản phẩm`;
                            productStatus.style.color = '#28a745';
                        } else {
                            productStatus.textContent = 'Hết hàng';
                            productStatus.style.color = '#dc3545';
                        }
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quantityInput = document.getElementById('quantity_input');
            const quantityAdd = document.getElementById('quantity_add');
            const quantityBuy = document.getElementById('quantity_buy');

            const decreaseBtn = document.querySelector('.quantity-decrease');
            const increaseBtn = document.querySelector('.quantity-increase');

            if (!quantityInput) return;

            const syncHiddenInputs = () => {
                quantityAdd.value = quantityInput.value;
                quantityBuy.value = quantityInput.value;
            };

            decreaseBtn?.addEventListener('click', () => {
                let current = parseInt(quantityInput.value);
                if (current > 1) {
                    quantityInput.value = current - 1;
                    syncHiddenInputs();
                }
            });

            increaseBtn?.addEventListener('click', () => {
                let current = parseInt(quantityInput.value);
                quantityInput.value = current + 1;
                syncHiddenInputs();
            });

            quantityInput.addEventListener('input', () => {
                let val = parseInt(quantityInput.value);
                if (isNaN(val) || val < 1) {
                    quantityInput.value = 1;
                }
                syncHiddenInputs();
            });

            // Gọi ngay khi load trang để đồng bộ giá trị ban đầu
            syncHiddenInputs();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.product-cart form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    const wrapper = this.closest('.product-detail');
                    const picked = wrapper?.querySelector('.product-size input[type="radio"][name="size"]:checked');
                    if (!picked) {
                        e.preventDefault();
                        if (typeof showToast === 'function') {
                            showToast('Vui lòng chọn size trước khi tiếp tục', 'warning');
                        } else {
                            alert('Vui lòng chọn size trước khi tiếp tục');
                        }
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.product-size input[type="radio"][name="size"]').forEach(function (input) {
                input.addEventListener('change', function () {
                    const variantId = this.dataset.variantId;
                    const price = parseInt(this.dataset.price);
                    const sale = parseInt(this.dataset.sale);

                    // Cập nhật cả hai input product_variant_id
                    const variantAdd = document.getElementById('product_variant_id_add');
                    const variantBuy = document.getElementById('product_variant_id_buy');
                    if (variantAdd) variantAdd.value = variantId;
                    if (variantBuy) variantBuy.value = variantId;

                    // Cập nhật lại giá hiển thị
                    const wrapper = this.closest('.product-detail') || document;
                    const priceEl = wrapper.querySelector('.price-product ins span');
                    const saleEl = wrapper.querySelector('.price-product del');
                    const saleSpanEl = wrapper.querySelector('.price-product del span');

                    if (sale > 0 && sale < price) {
                        priceEl.textContent = sale.toLocaleString('vi-VN') + ' VND';
                        if (saleSpanEl) {
                            saleSpanEl.textContent = price.toLocaleString('vi-VN') + ' VND';
                        }
                        if (saleEl) {
                            saleEl.style.display = 'inline';
                        }
                    } else {
                        priceEl.textContent = price.toLocaleString('vi-VN') + ' VND';
                        if (saleEl) {
                            saleEl.style.display = 'none';
                        }
                    }

                    // Cập nhật tình trạng sản phẩm
                    const quantity = parseInt(this.dataset.quantity);
                    const productStatus = wrapper.querySelector('.product-status span');
                    if (productStatus) {
                        if (quantity > 0) {
                            productStatus.textContent = `Kho hàng: ${quantity.toLocaleString('vi-VN')} sản phẩm`;
                            productStatus.style.color = '#28a745';
                        } else {
                            productStatus.textContent = 'Hết hàng';
                            productStatus.style.color = '#dc3545';
                        }
                    }
                });
            });
        });
    </script>

    {{-- CSS xử lý giao diện Đánh giá sản phẩm --}}
    <style>
        h1{
            color: black;
            /* text-align: center;  */
            margin-bottom: 20px
        }

        .viewed-products {
            margin-top: 30px;
            font-family: Arial, sans-serif;
        }

        .viewed-products--title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            /* border-left: 4px solid #ff4d4f; */
            padding-left: 10px;
        }

        .product-reviews {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            border: 1px solid #eee;
        }

        .review-item {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .review-item:last-child {
            border-bottom: none;
        }

        .review-header {
            margin-bottom: 8px;
        }

        .review-user {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .review-user strong {
            font-size: 16px;
            color: #222;
        }

        .review-date {
            font-size: 13px;
            color: #999;
        }

        .review-rating {
            font-size: 14px;
            color: #ffb400;
            margin-top: 3px;
        }

        .review-content p {
            margin: 0;
            font-size: 14px;
            line-height: 1.6;
            color: #444;
        }
    </style>

    {{-- CSS xử lý % số sao --}}
    <style>
        /* Quantity row styling */
        .product-quantity { margin: 12px 0 16px; }
        .product-quantity-row { display: flex; align-items: center; justify-content: flex-start; gap: 12px; flex-wrap: wrap; }
        .quantity-label { font-weight: 600; color: #333; }
        .quantity-control { display: inline-flex; align-items: center; gap: 6px; background: #f6f7f8; padding: 6px; border-radius: 8px; border: 1px solid #e5e7eb; }
        .qty-btn { width: 34px; height: 34px; line-height: 34px; text-align: center; border: 1px solid #d1d5db; background: #fff; border-radius: 6px; font-weight: 700; cursor: pointer; }
        .qty-btn:hover { background: #f3f4f6; }
        .qty-input { width: 64px; text-align: center; height: 34px; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; }
        @media (max-width: 576px) { .qty-input { width: 56px; } }

        /* Căn chỉnh thẩm mỹ khu vực thông tin sản phẩm */
        .product-information { padding-top: 11px; }
        .product-information h1 { font-size: 28px; font-weight: 700; line-height: 1.25; margin-bottom: 19px; text-transform: uppercase; }
        @media (max-width: 576px) { .product-information h1 { font-size: 22px; } }

        .price-product { display: flex; align-items: baseline; gap: 15px; margin: 11px 0 19px; }
        .price-product ins { text-decoration: none; color: #222; font-weight: 700; font-size: 22px; }
        .price-product del { color: #9aa0a6; font-size: 15px; }

        .product-detail__sub-info p { margin: 0 0 15px; color: #5f6368; display: flex; align-items: center; gap: 17px; flex-wrap: wrap; }
        .product-detail__sub-info .stars-outer { transform: translateY(1px); }

        .product-category { margin-top: 15px; }
        .product-category p { margin: 0; color: #5f6368; }

        .product-status { margin-top: 15px; }
        .product-status p { margin: 0; }
        .product-status span { display: inline-block; padding: 4px 8px; border-radius: 6px; background: #f6f7f8; font-weight: 600; }

        /* Nút chọn Màu (Color) */
        .list-color ul { gap: 12px !important; }
        .list-color li a span { width: 32px !important; height: 32px !important; border-radius: 50%; box-shadow: 0 1px 2px rgba(0,0,0,.06); transition: transform .15s ease, box-shadow .15s ease, outline-color .15s ease; }
        .list-color li a:hover span { transform: translateY(-1px); box-shadow: 0 3px 10px rgba(0,0,0,.12); }
        .list-color li.checked a span { outline: 2px solid #222; outline-offset: 2px; }

        /* Nút chọn Size */
        .product-size label { display: inline-flex !important; align-items: center; gap: 6px; margin: 6px 8px 0 0 !important; padding: 8px 14px !important; border: 1.5px solid #ddd !important; border-radius: 8px !important; background: #fff; color: #333; transition: all .15s ease; }
        .product-size label:hover { border-color: #222 !important; transform: translateY(-1px); box-shadow: 0 3px 10px rgba(0,0,0,.06); }
        .product-size input[type="radio"] { display: none; }
        .product-size input[type="radio"] + span { letter-spacing: .3px; font-weight: 600; }
        /* Khi chọn size: chỉ đổi viền, không đổi nền */
        .product-size label:has(input[type="radio"]:checked) { border-color: #222 !important; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        @media (max-width: 576px) {
            .product-size label { padding: 7px 12px !important; }
            .list-color li a span { width: 28px !important; height: 28px !important; }
        }

        /* Fractional star rating */
        .stars-outer {
            position: relative;
            display: inline-block;
            color: #ccc;
            font-size: 20px;
            line-height: 1;
        }

        .stars-outer::before {
            content: '★★★★★';
        }

        .stars-inner {
            position: absolute;
            top: 0;
            left: 0;
            white-space: nowrap;
            overflow: hidden;
            color: gold;
            width: 0;
        }

        .stars-inner::before {
            content: '★★★★★';
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.stars-inner[data-width]').forEach(function(el){
                var pct = parseFloat(el.getAttribute('data-width')) || 0;
                el.style.width = pct + '%';
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.color-swatch').forEach(function(sw){
                var color = sw.getAttribute('data-color') || '#ffffff';
                var hasBorder = sw.getAttribute('data-has-border') === '1';
                sw.style.display = 'inline-block';
                sw.style.width = '25px';
                sw.style.height = '25px';
                sw.style.borderRadius = '50%';
                sw.style.backgroundColor = color;
                sw.style.border = hasBorder ? '1px solid #ccc' : '1px solid transparent';
            });
        });
    </script>
    
    <style>
        /* Kiểu lượng - phiên bản pill, cùng hàng, tối giản */
        .product-quantity-row { gap: 16px; }
        .quantity-control { background: #fff; border: 1.5px solid #ddd; border-radius: 10px; padding: 6px; gap: 6px; }
        .qty-btn { width: 38px; height: 38px; border: none; background: #f3f4f6; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; cursor: pointer; }
        .qty-btn:hover { background: #e9ecef; }
        .qty-input { width: 60px; height: 38px; border: none; text-align: center; background: transparent; outline: none; font-weight: 600; }
        .quantity-control:has(.qty-input:focus) { box-shadow: inset 0 0 0 2px rgba(0,0,0,.06); }
    </style>
@endsection