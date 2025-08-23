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

                    {{-- Product Detail Container --}}
                    <div class="product-detail" data-product-id="{{ $product->id }}">
                        <div class="container">
                            <div class="row">
                                @php
                                    $variant = $product->variants->first();
                                    $activeVariants = $product->variants->where('status', '1');
                                    $defaultColorName = optional(optional($activeVariants->unique('color_id')->first())->color)->name ?? '';
                                @endphp

                                {{-- Product Gallery Section --}}
                                <div class="col-md-6">
                                    <div class="product-gallery">
                                        {{-- Main Product Image --}}
                                        <div class="product-gallery__main">
                                            <div class="product-gallery__main-image">
                                                <img class="product-main-img" src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}" alt="{{ $product->name }}">
                                            </div>
                                        </div>
                                            
                                        {{-- Product Thumbnails --}}
                                        <div class="product-gallery__thumbnails">
                                            @if($product->photoAlbums && $product->photoAlbums->count() > 0)
                                                {{-- First thumbnail is main product image --}}
                                                <div class="thumbnail-item active" data-image="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}">
                                                    <img src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}" alt="{{ $product->name }}">
                                                </div>
                                                
                                                {{-- Album images as thumbnails --}}
                                                            @foreach($product->photoAlbums as $album)
                                                    <div class="thumbnail-item" data-image="{{ asset('storage/' . $album->image) }}">
                                                        <img src="{{ asset('storage/' . $album->image) }}" alt="Album Image">
                                                                </div>
                                                            @endforeach
                                                        @else
                                                {{-- If no album, show main image only --}}
                                                <div class="thumbnail-item active" data-image="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}">
                                                    <img src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}" alt="{{ $product->name }}">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Product Information Section --}}
                                <div class="col-md-6">
                                    <div class="product-information">
                                        {{-- Product Title --}}
                                        <h1 style="text-transform: uppercase;">{{ $product->name }}</h1>

                                        {{-- Product Price --}}
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

                                        {{-- Product Sub Info (SKU, Rating) --}}
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

                                        {{-- Product Category --}}
                                        <div class="product-category">
                                            <p>
                                                Danh mục: <span>{{ $product->category->name ?? 'Không có danh mục' }}</span>
                                            </p>
                                        </div>

                                        {{-- Product Status --}}
                                        <div class="product-status">
                                            <p>
                                                <span id="product_status" style="color: #dc3545;">
                                                    Vui lòng chọn màu và size
                                                </span>
                                            </p>
                                        </div>

                                        {{-- Color Selection --}}
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

                                        {{-- Size Selection --}}
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

                                        {{-- Product Cart Section --}}
                                        <div class="product-cart">
                                            {{-- Quantity Selection --}}
                                            <div class="product-quantity">
                                                <div class="product-quantity-row">
                                                    <span class="quantity-label">Số lượng</span>
                                                    <div class="quantity-control">
                                                        <button type="button" class="quantity-decrease qty-btn" aria-label="Giảm">-</button>
                                                        <input type="number" id="quantity_input" value="1" min="1" class="qty-input" />
                                                        <button type="button" class="quantity-increase qty-btn" aria-label="Tăng">+</button>
                                                    </div>
                                                    <div class="quantity-error" style="display: none; color: #dc3545; font-size: 12px;"></div>
                                                </div>
                                            </div>

                                            {{-- Action Buttons --}}
                                            <div style="display: flex; gap: 10px;">                                                
                                                {{-- Add to Cart Form --}}
                                                <form action="{{ route('carts.add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_variant_id" id="product_variant_id_add" value="{{ $variant->id }}">
                                                    <input type="hidden" name="quantity" id="quantity_add" value="1">
                                                    <button type="submit" class="btn btn--large">Thêm vào giỏ</button>
                                                </form>

                                                {{-- Buy Now Form --}}
                                                <form action="{{ route('carts.buy') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_variant_id" id="product_variant_id_buy" value="{{ $variant->id }}">
                                                    <input type="hidden" name="quantity" id="quantity_buy" value="1">
                                                    <button type="submit" class="btn btn--large btn--outline">Mua hàng</button>
                                                </form>

                                                {{-- Wishlist Button --}}
                                                <button class="btn btn--large btn--outline btn--wishlist">
                                                    <i class="icon-ic_heart"></i>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Product Description Tab --}}
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

                    {{-- Product Reviews Section --}}
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
                    
                    {{-- Trending Banner --}}
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
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    {{-- ========================================
         PHẦN JAVASCRIPT - XỬ LÝ TƯƠNG TÁC
    ======================================== --}}

    {{-- ========================================
         CHỨC NĂNG GALLERY ẢNH SẢN PHẨM
    ======================================== --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Xử lý khi click vào thumbnail để đổi ảnh chính
            document.querySelectorAll('.thumbnail-item').forEach(function (thumbnail) {
                thumbnail.addEventListener('click', function () {
                    const imageUrl = this.dataset.image;
                    const mainImage = document.querySelector('.product-main-img');
                    
                    if (mainImage) {
                        mainImage.src = imageUrl;
                    }
                    
                    // Cập nhật trạng thái active cho thumbnail
                    document.querySelectorAll('.thumbnail-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });
        });
    </script>

    {{-- ========================================
         CHỨC NĂNG CHỌN MÀU SẮC
    ======================================== --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Xử lý khi người dùng chọn màu sắc
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

                    // Cập nhật ảnh chính trong gallery khi chọn màu
                    const mainImage = wrapper.querySelector('.product-main-img');
                    if (mainImage) {
                        mainImage.src = image;
                    }
                    
                    // Cập nhật thumbnail đầu tiên để hiển thị ảnh màu mới
                    const firstThumbnail = wrapper.querySelector('.thumbnail-item');
                    if (firstThumbnail) {
                        firstThumbnail.dataset.image = image;
                        const thumbnailImg = firstThumbnail.querySelector('img');
                        if (thumbnailImg) {
                            thumbnailImg.src = image;
                        }
                    }

                    // Cập nhật nhãn màu sắc đã chọn
                    const colorLabel = wrapper.querySelector('.picked-color-name');
                    if (colorLabel) colorLabel.textContent = colorName;

                    // Cập nhật hiển thị giá sản phẩm
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

                    // Đánh dấu màu sắc đã được chọn
                    wrapper.querySelectorAll('.list-color li').forEach(li => li.classList.remove('checked'));
                    this.closest('li').classList.add('checked');

                    // Hiển thị các size tương ứng với màu đã chọn
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
        });
    </script>

    {{-- ========================================
         CHỨC NĂNG CHỌN SIZE VÀ QUẢN LÝ SỐ LƯỢNG
    ======================================== --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Xử lý khi người dùng chọn kích thước
            document.querySelectorAll('.product-size input[type="radio"][name="size"]').forEach(function (input) {
                input.addEventListener('change', function () {
                    const variantId = this.dataset.variantId;
                    const price = parseInt(this.dataset.price);
                    const sale = parseInt(this.dataset.sale);
                    const quantity = parseInt(this.dataset.quantity);
                    const sizeName = this.dataset.sizeName || '';
                    const wrapper = this.closest('.product-detail');

                    // Cập nhật ID variant trong các form
                    const variantAdd = document.getElementById('product_variant_id_add');
                    const variantBuy = document.getElementById('product_variant_id_buy');
                    if (variantAdd) variantAdd.value = variantId;
                    if (variantBuy) variantBuy.value = variantId;

                    // Cập nhật nhãn kích thước đã chọn
                    const sizeLabel = wrapper.querySelector('.picked-size-name');
                    if (sizeLabel) sizeLabel.textContent = sizeName;

                    // Cập nhật hiển thị giá sản phẩm
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

                    // Cập nhật trạng thái kho hàng
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

                    // Cập nhật số lượng tối đa cho input số lượng
                    const quantityInput = wrapper.querySelector('#quantity_input');
                    if (quantityInput) {
                        quantityInput.setAttribute('data-max', quantity);
                        // Kiểm tra nếu số lượng hiện tại vượt quá số lượng có sẵn
                        const currentQty = parseInt(quantityInput.value);
                        if (currentQty > quantity) {
                            quantityInput.value = quantity;
                            // Hiển thị thông báo
                            const quantityError = wrapper.querySelector('.quantity-error');
                            if (quantityError) {
                                quantityError.textContent = `Số lượng tối đa có thể chọn: ${quantity}`;
                                quantityError.style.display = 'block';
                                setTimeout(() => {
                                    quantityError.style.display = 'none';
                                }, 3000);
                            }
                        }
                        // Cập nhật các input ẩn
                        const quantityAdd = wrapper.querySelector('#quantity_add');
                        const quantityBuy = wrapper.querySelector('#quantity_buy');
                        if (quantityAdd) quantityAdd.value = quantityInput.value;
                        if (quantityBuy) quantityBuy.value = quantityInput.value;
                    }
                });
            });
        });
    </script>

    {{-- ========================================
         HỆ THỐNG ĐIỀU KHIỂN SỐ LƯỢNG
    ======================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quantityInput = document.getElementById('quantity_input');
            const quantityAdd = document.getElementById('quantity_add');
            const quantityBuy = document.getElementById('quantity_buy');
            const decreaseBtn = document.querySelector('.quantity-decrease');
            const increaseBtn = document.querySelector('.quantity-increase');

            if (!quantityInput) return;

            // Đồng bộ các input ẩn với input số lượng
            const syncHiddenInputs = () => {
                quantityAdd.value = quantityInput.value;
                quantityBuy.value = quantityInput.value;
            };

                        // Xử lý nút giảm số lượng
            decreaseBtn?.addEventListener('click', () => {
                let current = parseInt(quantityInput.value);
                if (current > 1) {
                    quantityInput.value = current - 1;
                    syncHiddenInputs();
                }
            });

            // Xử lý nút tăng số lượng
            increaseBtn?.addEventListener('click', () => {
                let current = parseInt(quantityInput.value);
                const maxQty = parseInt(quantityInput.getAttribute('data-max')) || 999;
                if (current < maxQty) {
                    quantityInput.value = current + 1;
                    syncHiddenInputs();
                } else {
                    // Hiển thị thông báo khi vượt quá số lượng
                    const quantityError = document.querySelector('.quantity-error');
                    if (quantityError) {
                        quantityError.textContent = `Số lượng tối đa có thể chọn: ${maxQty}`;
                        quantityError.style.display = 'block';
                        setTimeout(() => {
                            quantityError.style.display = 'none';
                        }, 3000);
                    }
                }
            });

            // Kiểm tra và xác thực input số lượng
            quantityInput.addEventListener('input', () => {
                let val = parseInt(quantityInput.value);
                const maxQty = parseInt(quantityInput.getAttribute('data-max')) || 999;
                
                if (isNaN(val) || val < 1) {
                    quantityInput.value = 1;
                } else if (val > maxQty) {
                    quantityInput.value = maxQty;
                    // Hiển thị thông báo khi vượt quá số lượng
                    const quantityError = document.querySelector('.quantity-error');
                    if (quantityError) {
                        quantityError.textContent = `Số lượng tối đa có thể chọn: ${maxQty}`;
                        quantityError.style.display = 'block';
                        setTimeout(() => {
                            quantityError.style.display = 'none';
                        }, 3000);
                    }
                }
                syncHiddenInputs();
            });

            // Khởi tạo số lượng tối đa dựa trên variant đầu tiên
            const firstSizeInput = document.querySelector('.product-size input[type="radio"][name="size"]');
            if (firstSizeInput) {
                const initialQuantity = parseInt(firstSizeInput.dataset.quantity);
                quantityInput.setAttribute('data-max', initialQuantity);
            }

            // Đồng bộ ban đầu
            syncHiddenInputs();
        });
    </script>

    {{-- ========================================
         KIỂM TRA FORM TRƯỚC KHI GỬI
    ======================================== --}}
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

    {{-- ========================================
         HIỂN THỊ ĐÁNH GIÁ SAO
    ======================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.stars-inner[data-width]').forEach(function(el){
                var pct = parseFloat(el.getAttribute('data-width')) || 0;
                el.style.width = pct + '%';
            });
        });
    </script>

    {{-- ========================================
         TẠO MÀU SẮC CHO COLOR SWATCH
    ======================================== --}}
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

    {{-- ========================================
         PHẦN CSS - ĐỊNH DẠNG GIAO DIỆN
    ======================================== --}}

    {{-- ========================================
         CSS CHO GALLERY ẢNH SẢN PHẨM
    ======================================== --}}
    <style>
        .product-gallery {
            position: relative;
            margin-bottom: 30px;
        }

        .product-gallery__main {
            margin-bottom: 20px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            background: #fff;
        }

        .product-gallery__main-image {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 120%; /* Aspect ratio 5:6 */
            overflow: hidden;
        }

        .product-main-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-gallery__main-image:hover .product-main-img {
            transform: scale(1.05);
        }

        .product-gallery__thumbnails {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .thumbnail-item {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .thumbnail-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .thumbnail-item.active {
            border-color: #007bff;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .thumbnail-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Responsive - Tự động điều chỉnh theo kích thước màn hình */
        @media (max-width: 768px) {
            .product-gallery__main-image {
                padding-bottom: 100%; /* Tỷ lệ hình vuông trên mobile */
            }
            
            .thumbnail-item {
                width: 60px;
                height: 60px;
            }
            
            .product-gallery__thumbnails {
                gap: 8px;
            }
        }

        @media (max-width: 576px) {
            .product-gallery__main-image {
                padding-bottom: 90%; /* Rộng hơn một chút trên màn hình nhỏ */
            }
            
            .thumbnail-item {
                width: 50px;
                height: 50px;
            }
        }
    </style>

    {{-- ========================================
         CSS CHO THÔNG TIN SẢN PHẨM
    ======================================== --}}
    <style>
        .product-information { padding-top: 11px; }
        .product-information h1 { 
            font-size: 28px; 
            font-weight: 700; 
            line-height: 1.25; 
            margin-bottom: 19px; 
            text-transform: uppercase; 
        }
        @media (max-width: 576px) { 
            .product-information h1 { font-size: 22px; } 
        }

        .price-product { 
            display: flex; 
            align-items: baseline; 
            gap: 15px; 
            margin: 11px 0 19px; 
        }
        .price-product ins { 
            text-decoration: none; 
            color: #222; 
            font-weight: 700; 
            font-size: 22px; 
        }
        .price-product del { 
            color: #9aa0a6; 
            font-size: 15px; 
        }

        .product-detail__sub-info p { 
            margin: 0 0 15px; 
            color: #5f6368; 
            display: flex; 
            align-items: center; 
            gap: 17px; 
            flex-wrap: wrap; 
        }
        .product-detail__sub-info .stars-outer { 
            transform: translateY(1px); 
        }

        .product-category { margin-top: 15px; }
        .product-category p { margin: 0; color: #5f6368; }

        .product-status { margin-top: 15px; }
        .product-status p { margin: 0; }
        .product-status span { 
            display: inline-block; 
            padding: 4px 8px; 
            border-radius: 6px; 
            background: #f6f7f8; 
            font-weight: 600; 
        }
    </style>

    {{-- ========================================
         CSS CHO CHỌN MÀU SẮC
    ======================================== --}}
    <style>
        .list-color ul { gap: 12px !important; }
        .list-color li a span { 
            width: 32px !important; 
            height: 32px !important; 
            border-radius: 50%; 
            box-shadow: 0 1px 2px rgba(0,0,0,.06); 
            transition: transform .15s ease, box-shadow .15s ease, outline-color .15s ease; 
        }
        .list-color li a:hover span { 
            transform: translateY(-1px); 
            box-shadow: 0 3px 10px rgba(0,0,0,.12); 
        }
        .list-color li.checked a span { 
            outline: 2px solid #222; 
            outline-offset: 2px; 
        }
        @media (max-width: 576px) {
            .list-color li a span { 
                width: 28px !important; 
                height: 28px !important; 
            }
        }
    </style>

    {{-- ========================================
         CSS CHO CHỌN KÍCH THƯỚC
    ======================================== --}}
    <style>
        .product-size label { 
            display: inline-flex !important; 
            align-items: center; 
            gap: 6px; 
            margin: 6px 8px 0 0 !important; 
            padding: 8px 14px !important; 
            border: 1.5px solid #ddd !important; 
            border-radius: 8px !important; 
            background: #fff; 
            color: #333; 
            transition: all .15s ease; 
        }
        .product-size label:hover { 
            border-color: #222 !important; 
            transform: translateY(-1px); 
            box-shadow: 0 3px 10px rgba(0,0,0,.06); 
        }
        .product-size input[type="radio"] { display: none; }
        .product-size input[type="radio"] + span { 
            letter-spacing: .3px; 
            font-weight: 600; 
        }
        .product-size label:has(input[type="radio"]:checked) { 
            border-color: #222 !important; 
            box-shadow: 0 2px 8px rgba(0,0,0,.06); 
        }
        @media (max-width: 576px) {
            .product-size label { 
                padding: 7px 12px !important; 
            }
        }
    </style>

    {{-- ========================================
         CSS CHO ĐIỀU KHIỂN SỐ LƯỢNG
    ======================================== --}}
    <style>
        .product-quantity { margin: 12px 0 16px; }
        .product-quantity-row { 
            display: flex; 
            align-items: center; 
            gap: 16px; 
            flex-wrap: wrap;
        }
        .quantity-label { 
            font-weight: 600; 
            color: #333; 
        }
        .quantity-control { 
            background: #fff; 
            border: 1.5px solid #ddd; 
            border-radius: 10px; 
            padding: 6px; 
            gap: 6px; 
        }
        .qty-btn { 
            width: 38px; 
            height: 38px; 
            border: none; 
            background: #f3f4f6; 
            border-radius: 8px; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: 700; 
            cursor: pointer; 
        }
        .qty-btn:hover { 
            background: #e9ecef; 
        }
        .qty-input { 
            width: 60px; 
            height: 38px; 
            border: none; 
            text-align: center; 
            background: transparent; 
            outline: none; 
            font-weight: 600; 
        }
        .quantity-control:has(.qty-input:focus) { 
            box-shadow: inset 0 0 0 2px rgba(0,0,0,.06); 
        }
        
        /* Thông báo lỗi số lượng */
        .quantity-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 11px;
            color: #721c24;
            animation: fadeIn 0.3s ease-in;
            white-space: nowrap;
        }
        
        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(-5px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }
    </style>

    {{-- ========================================
         CSS CHO ĐÁNH GIÁ SAO
    ======================================== --}}
    <style>
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

    {{-- ========================================
         CSS CHO ĐÁNH GIÁ SẢN PHẨM
    ======================================== --}}
    <style>
        h1 {
            color: black;
            margin-bottom: 20px;
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
@endsection