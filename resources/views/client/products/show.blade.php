@extends('client.layouts.app')

@section('title', 'Sản Phẩm')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div id="product_detail" class="row">
                <div class="container">
                    <div class="breadcrumb-products">
                        <ol class="breadcrumb__list">
                            <li class="breadcrumb__item"><a href="{{ route('home') }}" class="breadcrumb__link">Trang chủ</a></li>
                            {{-- <li class="breadcrumb__item"><a href="#" class="breadcrumb__link" title="NỮ">NỮ</a></li> --}}
                            <li class="breadcrumb__item"><a href="#">{{ $product->name }}</a></li>
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
                                                {{-- <div class="swiper-nav-prev">
                                                    <span class="icon-ic_down"></span>
                                                </div>
                                                <div class="swiper-nav-next">
                                                    <span class="icon-ic_down"></span>
                                                </div> --}}
                                                {{-- <div class="swiper-container swiper">
                                                    <div class="swiper-wrapper">
                                                        <div class="swiper-slide">
                                                            <div class="item-zoom" data-image="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}">
                                                                <img class="product-img" src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="thumb-product">
                                                    <img class="product-img" src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}" style="width: 550px; height: 550px">
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
                                                        <div class="swiper-slide">
                                                            <img class="product-photo-album" src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="product-detail__information">
                                        <h1 style="text-transform: uppercase;">{{ $product->name }}</h1>

                                        <div class="product-detail__sub-info">
                                            <p>
                                                SKU: <span>{{ $product->product_code }}</span>
                                            </p>
                                            <div class="product-detail__rating">
                                                <div class="product-detail__rating-wrapper" data-percentage="100">
                                                    <div class="product-detail__rating__background"></div>
                                                    <div class="product-detail__rating__bar"></div>
                                                </div>
                                                <span>(0 đánh giá)</span>
                                            </div>
                                        </div>
                                        
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

                                        <div class="list-color">
                                            <ul style="display: flex; gap: 10px;">
                                                @foreach ($product->variants->unique('color_id') as $colorVariant)
                                                    <li class="{{ $loop->first ? 'checked' : '' }}" data-color-id="{{ $colorVariant->color_id }}">
                                                        <a href="" 
                                                            class="color-picker" 
                                                            data-image="{{ asset('storage/' . $colorVariant->image) }}"
                                                            data-size=""
                                                            data-price="{{ $colorVariant->price }}"
                                                            data-sale="{{ $colorVariant->sale }}"
                                                            data-product="{{ $product->id }}">
                                                            <span style="display:inline-block; width: 25px; height: 25px; border-radius: 50%;
                                                                background-color: {{ $colorVariant->color->color_code }};
                                                                border: 1px solid {{ strtolower($colorVariant->color->color_code) === '#ffffff' ? '#ccc' : 'transparent' }};">
                                                            </span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>                 
                                        </div>

                                        <div class="product-size">
                                            @foreach ($product->variants->groupBy('color_id') as $colorId => $variantsByColor)
                                                <div class="size-group" data-color-id="{{ $colorId }}" style="{{ $loop->first ? '' : 'display: none;' }}">
                                                    @foreach ($variantsByColor as $colorVariant)
                                                        <label style="display: inline-block; margin: 5px; padding: 5px 9px; border: 1px solid #ccc; border-radius: 4px;">
                                                            {{-- <input type="radio" name="size" value="{{ $colorVariant->size->id }}"> --}}
                                                            <input type="radio" name="size" value="{{ $colorVariant->size->id }}"
                                                                data-size-id="{{ $colorVariant->size->id }}"
                                                                data-price="{{ $colorVariant->price }}"
                                                                data-sale="{{ $colorVariant->sale }}">
                                                            <span class="text-uppercase">{{ $colorVariant->size->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="product-quantity">
                                            <p style="font-weight: 500;">Số lượng</p>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <button type="button" class="quantity-decrease" style="width: 32px;">-</button>
                                                <input type="number" name="quantity" id="quantity-input" value="1" min="1" style="width: 60px; text-align: center;" />
                                                <button type="button" class="quantity-increase" style="width: 32px;">+</button>
                                            </div>
                                        </div>

                                        <div class="product-cart" style="display: flex; gap: 10px;">
                                            <button class="btn btn--large add-to-cart-detail">
                                                Thêm vào giỏ
                                            </button>
                                            <a href="" id="purchase">
                                                <button class="btn btn--large btn--outline">Mua hàng</button>
                                            </a>
                                            <button class="btn btn--large btn--outline btn--wishlist" data-id="42167">
                                                <i class="icon-ic_heart"></i>
                                            </button>
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

<div class="add-card-size" id="fancybox-add-to-cart">
    <div class="thank-you__icon"><svg width="160" height="160" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M56.7833 20.1167C62.9408 13.9592 71.2921 10.5 80 10.5C84.3117 10.5 88.5812 11.3493 92.5648 12.9993C96.5483 14.6493 100.168 17.0678 103.217 20.1167C106.266 23.1655 108.684 26.785 110.334 30.7686C111.984 34.7521 112.833 39.0216 112.833 43.3333V62.8333H47.1667V43.3333C47.1667 34.6254 50.6259 26.2741 56.7833 20.1167ZM46.1667 62.8333V43.3333C46.1667 34.3602 49.7312 25.7545 56.0762 19.4096C62.4212 13.0646 71.0268 9.5 80 9.5C84.4431 9.5 88.8426 10.3751 92.9474 12.0754C97.0523 13.7757 100.782 16.2678 103.924 19.4096C107.065 22.5513 109.558 26.281 111.258 30.3859C112.958 34.4907 113.833 38.8903 113.833 43.3333V62.8333H133.333C133.582 62.8333 133.793 63.0163 133.828 63.2626L147.162 156.596C147.182 156.739 147.139 156.885 147.044 156.994C146.949 157.104 146.812 157.167 146.667 157.167H13.3333C13.1884 157.167 13.0506 157.104 12.9556 156.994C12.8606 156.885 12.8179 156.739 12.8384 156.596L26.1717 63.2626C26.2069 63.0163 26.4178 62.8333 26.6667 62.8333H46.1667ZM113.333 63.8333H46.6667H27.1003L13.9098 156.167H146.09L132.9 63.8333H113.333Z" fill="#212121"></path>
        <path d="M107.205 91.3663L80.4451 121.251L64.5853 106.174L62 108.893L80.6618 126.634L110 93.8694L107.205 91.3663Z" fill="black"></path>
    </svg>
    </div>
    <p class="notify__add-to-cart--success text-uppercase">Thêm vào giỏ hàng thành công !</p>
</div>
<div id="overlay"></div>

@endsection

@section('scripts')
    {{-- <script>
        $(function() {
            var zoom = function(elm) {
                elm.on('mouseover', function() {
                    $(this).children('.img').css('transform', 'scale(2)');
                }).on('mouseout', function() {
                    $(this).children('.img').css('transform', 'scale(1)');
                }).on('mousemove', function(e) {
                    $(this).children('.img').css('transform-origin', ((e.pageX - $(this).offset().left) / $(this).width()) * 100 + '% ' + ((e.pageY - $(this).offset().top) / $(this).height()) * 100 +'%');
                })
            }

            let isMobile = false;

            if (typeof navigator.userAgent != "undefined" && /Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i.test(navigator.userAgent))
                isMobile = true;

            $('.item-zoom').each(function() {
                $(this).append('<div class="img"></div>').children('.img').css('background-image', 'url('+ $(this).data('image') +')');
                
                if (!isMobile) zoom($(this));
            })

        });
    </script> --}}

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

                    const wrapper = document.querySelector(`.product-detail[data-product-id="${productId}"]`);
                    if (!wrapper) return;

                    // Đổi ảnh
                    wrapper.querySelector('.product-img').src = image;

                    // Đổi giá ban đầu (khi chưa chọn size)
                    const priceEl = wrapper.querySelector('.price-product ins span');
                    const saleEl = wrapper.querySelector('.price-product del span');
                    if (sale > 0 && sale < price) {
                        priceEl.textContent = sale.toLocaleString('vi-VN') + ' VND';
                        saleEl.textContent = price.toLocaleString('vi-VN') + ' VND';
                        saleEl.parentElement.style.display = 'inline';
                    } else {
                        priceEl.textContent = price.toLocaleString('vi-VN') + ' VND';
                        if (saleEl) {
                            saleEl.textContent = '';
                            saleEl.parentElement.style.display = 'none';
                        }
                    }

                    // Đánh dấu màu
                    wrapper.querySelectorAll('.list-color li').forEach(li => li.classList.remove('checked'));
                    this.closest('li').classList.add('checked');

                    // Hiển thị size theo màu
                    wrapper.querySelectorAll('.product-size .size-group').forEach(group => {
                        group.style.display = 'none';
                    });
                    wrapper.querySelectorAll(`.product-size .size-group`).forEach(group => {
                        if (group.dataset.colorId == colorId) {
                            group.style.display = 'block';
                        }
                    });
                });
            });

            // Click size
            document.querySelectorAll('.product-size input[type="radio"][name="size"]').forEach(function (input) {
                input.addEventListener('change', function () {
                    const price = parseInt(this.dataset.price);
                    const sale = parseInt(this.dataset.sale);
                    const wrapper = this.closest('.product-detail');

                    const priceEl = wrapper.querySelector('.price-product ins span');
                    const saleEl = wrapper.querySelector('.price-product del span');

                    if (sale > 0 && sale < price) {
                        priceEl.textContent = sale.toLocaleString('vi-VN') + ' VND';
                        saleEl.textContent = price.toLocaleString('vi-VN') + ' VND';
                        saleEl.parentElement.style.display = 'inline';
                    } else {
                        priceEl.textContent = price.toLocaleString('vi-VN') + ' VND';
                        if (saleEl) {
                            saleEl.textContent = '';
                            saleEl.parentElement.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const decreaseBtn = document.querySelector('.quantity-decrease');
            const increaseBtn = document.querySelector('.quantity-increase');
            const quantityInput = document.getElementById('quantity-input');

            // Khi bấm nút -
            decreaseBtn.addEventListener('click', () => {
                let current = parseInt(quantityInput.value);
                if (current > 1) {
                    quantityInput.value = current - 1;
                }
            });

            // Khi bấm nút +
            increaseBtn.addEventListener('click', () => {
                let current = parseInt(quantityInput.value);
                quantityInput.value = current + 1;
            });

            // Chặn nhập số nhỏ hơn 1 hoặc ký tự lạ
            quantityInput.addEventListener('input', () => {
                let val = parseInt(quantityInput.value);
                if (isNaN(val) || val < 1) {
                    quantityInput.value = 1;
                }
            });
        });
    </script>
@endsection