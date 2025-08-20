@extends('client.layouts.app')

@section('title', 'Trang Chủ')

@section('content')
    <body>
        <main id="main" class="site-main">
            <div class="container">
                <!-- Promotion -->
                <div class="nav-info d-flex">
                    <div class="left-nav-info item-nav-info">
                        <a href="https://ivymoda.com/danh-muc/deal-t5-sale-70-060525">
                            <span>SALE OFF 70%</span>
                        </a>
                    </div>
                    <div class="center-nav-info item-nav-info">
                        <a href="https://ivymoda.com/danh-muc/deal-t5-50-060525">
                            <span>SALE OFF 50%</span>
                        </a>
                    </div>
                    <div class="right-nav-info item-nav-info">
                        <a href="https://ivymoda.com/danh-muc/deal-t5-sale-30-060525">
                            <span>SALE OFF 30% </span>
                        </a>
                    </div>
                </div>
                <!-- End Promotion -->

                <!--Slider Trang chủ - Đầu trang-->
                @if ($banners_top_home->count() > 0)
                    <section class="home-banner bg-before">
                        <div id="banner-top-home" class="slider-banner owl-carousel">
                            @foreach ($banners_top_home as $banner)
                                <div class="item-banner">
                                    <a href="{{ $banner->product_link ?? '#' }}">
                                        <img src="{{ asset('storage/' . $banner->image) }}" style="height: 529px;"  alt="{{ $banner->name }}">
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
                                            <a href="#" class="btn btn-dark btn-sm">Dùng mã</a>
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

                <!-- New Arrival -->
                <section class="home-new-prod">
                    <div class="title-section">NEW ARRIVAL</div>
                    <div class="exclusive-tabs">
                        <div class="exclusive-head">
                            <ul>
                                <li class="exclusive-tab active arrival-tab" data-cate-slug="hang-nu-moi-ve"
                                    data-tab="tab-women">
                                    IVY moda
                                </li>
                                <li class="exclusive-tab arrival-tab" data-cate-slug="hang-nam-moi-ve" data-tab="tab-men">
                                    Metagent
                                </li>
                            </ul>
                        </div>
                        <div class="exclusive-content">
                            <div class="exclusive-inner active" id="tab-women">
                                <div class="list-products new-prod-slider owl-carousel">
                                    @foreach ($products as $product)
                                        @php
                                            $variant = $product->variants->first(); // hoặc chọn variant theo logic khác

                                            $favorites = Auth::check() ? Auth::user()->favorites->pluck('product_id')->toArray() : [];
                                            $isFavorite = in_array($product->id, $favorites);
                                        @endphp

                                        <div class="item-new-prod">
                                            {{-- <div class="product"> --}}
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
                                <div class="link-product">
                                    <a href="https://ivymoda.com/danh-muc/hang-nu-moi-ve" class="all-product">Xem tất cả1</a>
                                </div>
                            </div>
                            <div class="exclusive-inner" id="tab-men">
                                <div class="list-products new-prod-slider owl-carousel">
                                    <div class="item-new-prod">
                                        <div class="product">
                                            <div class="thumb-product">
                                                <a
                                                    href="https://ivymoda.com/sanpham/ao-thun-regular-supima-classic-ms-57e4248-42405">
                                                    <img data-src="https://cotton4u.vn/files/product/thumab/400/2024/08/15/a69fbf767ff2ee9d6aa2198406eec63a.jpg"
                                                        alt="Áo thun Regular Supima Classic" class=" owl-lazy" />
                                                    <img data-src="https://cotton4u.vn/files/product/thumab/400/2024/08/15/33b97e132ef4d4e5380bf84ea0f3b0a7.jpg"
                                                        alt="Áo thun Regular Supima Classic" class="hover-img owl-lazy" />
                                                </a>
                                            </div>
                                            <div class="info-product">
                                                <div class="list-color">
                                                    <ul>
                                                        <li class="checked bg-light">
                                                            <a href="javascript:void(0)" class="color-picker"
                                                                data-id="42405">
                                                                <img data-src="https://cotton4u.vn/ivy2/images/color/001.png"
                                                                    alt="001" class="owl-lazy" />
                                                            </a>
                                                        </li>
                                                        <li class="">
                                                            <a href="javascript:void(0)" class="color-picker"
                                                                data-id="42406">
                                                                <img data-src="https://cotton4u.vn/ivy2/images/color/049.png"
                                                                    alt="049" class="owl-lazy" />
                                                            </a>
                                                        </li>
                                                        <li class="">
                                                            <a href="javascript:void(0)" class="color-picker"
                                                                data-id="42407">
                                                                <img data-src="https://cotton4u.vn/ivy2/images/color/052.png"
                                                                    alt="052" class="owl-lazy" />
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="favourite" data-id="42405">
                                                        <i class="icon-ic_heart"></i>
                                                    </div>
                                                </div>
                                                <h3 class="title-product">
                                                    <a href="https://ivymoda.com/sanpham/ao-thun-regular-supima-classic-ms-57e4248-42405">Áo thun Regular Supima Classic</a>
                                                </h3>
                                                <div class="price-product">
                                                    <ins>
                                                        <span>699.000đ</span>
                                                    </ins>
                                                </div>
                                            </div>
                                            <div class="add-to-cart">
                                                <a href="javascript:void(0)"><i class="icon-ic_shopping-bag"></i></a>
                                            </div>
                                            <div class="list-size">
                                                <ul>
                                                    <li data-product-sub-id="204050"><button
                                                            class="btn bt-large">s</button></li>
                                                    <li data-product-sub-id="204051"><button
                                                            class="btn bt-large">m</button></li>
                                                    <li data-product-sub-id="204052"><button
                                                            class="btn bt-large">l</button></li>
                                                    <li data-product-sub-id="204053"><button
                                                            class="btn bt-large">xl</button></li>
                                                    <li data-product-sub-id="204054"><button
                                                            class="btn bt-large">xxl</button></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="link-product">
                                    <a href="https://ivymoda.com/danh-muc/hang-nam-moi-ve" class="all-product">Xem tất cả2</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- End New Arrival -->

                <!-- Best Seller -->
                <section class="home-new-prod">
                    <div class="title-section">Online Exclusive | ƯU ĐÃI CHÀO HÈ GIẢM 50%</div>
                    <div class="exclusive-tabs">
                        <div class="exclusive-head">
                            <ul>
                                <li class="exclusive-tab arrival-tab active" data-cate-slug="hang-nu-moi-ve"
                                    data-tab="best-seller-tab-women">
                                    IVY moda
                                </li>
                                <li class="exclusive-tab arrival-tab" data-cate-slug="hang-nam-moi-ve"
                                    data-tab="best-seller-tab-men">
                                    Metagent
                                </li>
                            </ul>
                        </div>
                        <div class="exclusive-content">
                            <div class="exclusive-inner active" id="best-seller-tab-women">
                                <div class="list-products new-prod-slider owl-carousel">
                                    <div class="item-new-prod">
                                        <div class="product">
                                            <div class="info-ticket seller">Best Seller</div>
                                            <span class="badget badget_02">-50<span>%</span></span>
                                            <div class="thumb-product">
                                                <a
                                                    href="https://ivymoda.com/sanpham/lace-high-blouse-ao-ren-co-v-ms-16t0253-43044">
                                                    <img data-src="https://cotton4u.vn/files/product/thumab/400/2025/05/22/aaa984226702752415a19ae4a950b921.webp"
                                                        alt="Lace High Blouse - Áo ren cổ V" class=" owl-lazy" />
                                                    <img data-src="https://cotton4u.vn/files/product/thumab/400/2025/05/22/95f57f8c78df88446928e4d357c5fd01.webp"
                                                        alt="Lace High Blouse - Áo ren cổ V" class="hover-img owl-lazy" />
                                                </a>
                                            </div>
                                            <div class="info-product">
                                                <div class="list-color">
                                                    <ul>
                                                        <li class="checked ">
                                                            <a href="javascript:void(0)" class="color-picker"
                                                                data-id="43044">
                                                                <img data-src="https://cotton4u.vn/ivy2/images/color/002.png"
                                                                    alt="002" class="owl-lazy" />
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="favourite" data-id="43044">
                                                        <i class="icon-ic_heart"></i>
                                                    </div>
                                                </div>
                                                <h3 class="title-product">
                                                    <a href="https://ivymoda.com/sanpham/lace-high-blouse-ao-ren-co-v-ms-16t0253-43044">Lace High Blouse - Áo ren cổ V</a>
                                                </h3>
                                                <div class="price-product">
                                                    <ins>
                                                        <span>445.000đ</span>
                                                    </ins>
                                                    <del>
                                                        <span>890.000đ</span>
                                                    </del>
                                                </div>
                                            </div>
                                            <div class="add-to-cart">
                                                <a href="javascript:void(0)"><i class="icon-ic_shopping-bag"></i></a>
                                            </div>
                                            <div class="list-size">
                                                <ul>
                                                    <li data-product-sub-id="205808"><button
                                                            class="btn bt-large">s</button></li>
                                                    <li data-product-sub-id="205813"><button
                                                            class="btn bt-large">m</button></li>
                                                    <li data-product-sub-id="205818"><button
                                                            class="btn bt-large">l</button></li>
                                                    <li class="unactive"><button class="btn bt-large">xl</button></li>
                                                    <li class="unactive"><button class="btn bt-large">xxl</button></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="link-product">
                                    <a href="https://ivymoda.com/danh-muc/nu?hid_best_seller=1" class="all-product">Xem tất cả3</a>
                                </div>
                            </div>
                            <div class="exclusive-inner" id="best-seller-tab-men">
                                <div class="list-products new-prod-slider owl-carousel">
                                    <div class="item-new-prod">
                                        <div class="product">
                                            <div class="info-tag tag-sale">
                                                <img src="https://ivymoda.com/assets/images/bg_sale.webp"
                                                    alt="Giá cuối">
                                            </div>
                                            <div class="thumb-product">
                                                <a
                                                    href="https://ivymoda.com/sanpham/ao-so-mi-bamboo-regular-tay-ngan-ms-16e4339-41359">
                                                    <img data-src="https://cotton4u.vn/files/product/thumab/400/2024/08/15/273922bf9628ef2911a659d3ca85cad1.jpg"
                                                        alt="Áo sơ mi Bamboo Regular tay ngắn" class=" owl-lazy" />
                                                    <img data-src="https://cotton4u.vn/files/product/thumab/400/2024/08/15/536fccc484dded57851396e57f219df1.jpg"
                                                        alt="Áo sơ mi Bamboo Regular tay ngắn"
                                                        class="hover-img owl-lazy" />
                                                </a>
                                            </div>
                                            <div class="info-product">
                                                <div class="list-color">
                                                    <ul>
                                                        <li class="">
                                                            <a href="javascript:void(0)" class="color-picker"
                                                                data-id="41357">
                                                                <img data-src="https://cotton4u.vn/ivy2/images/color/016.png"
                                                                    alt="016" class="owl-lazy" />
                                                            </a>
                                                        </li>
                                                        <li class="">
                                                            <a href="javascript:void(0)" class="color-picker"
                                                                data-id="41358">
                                                                <img data-src="https://cotton4u.vn/ivy2/images/color/h41.png"
                                                                    alt="h41" class="owl-lazy" />
                                                            </a>
                                                        </li>
                                                        <li class="checked ">
                                                            <a href="javascript:void(0)" class="color-picker"
                                                                data-id="41359">
                                                                <img data-src="https://cotton4u.vn/ivy2/images/color/k16.png"
                                                                    alt="k16" class="owl-lazy" />
                                                            </a>
                                                        </li>
                                                        <li class="">
                                                            <a href="javascript:void(0)" class="color-picker"
                                                                data-id="41360">
                                                                <img data-src="https://cotton4u.vn/ivy2/images/color/k17.png"
                                                                    alt="k17" class="owl-lazy" />
                                                            </a>
                                                        </li>
                                                        <li class="">
                                                            <a href="javascript:void(0)" class="color-picker"
                                                                data-id="41362">
                                                                <img data-src="https://cotton4u.vn/ivy2/images/color/k49.png"
                                                                    alt="k49" class="owl-lazy" />
                                                            </a>
                                                        </li>
                                                        <li class="">
                                                            <a href="javascript:void(0)" class="color-picker"
                                                                data-id="41364">
                                                                <img data-src="https://cotton4u.vn/ivy2/images/color/k60.png"
                                                                    alt="k60" class="owl-lazy" />
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="favourite" data-id="41359">
                                                        <i class="icon-ic_heart"></i>
                                                    </div>
                                                </div>
                                                <h3 class="title-product">
                                                    <a
                                                        href="https://ivymoda.com/sanpham/ao-so-mi-bamboo-regular-tay-ngan-ms-16e4339-41359">Áo sơ mi Bamboo Regular tay ngắn</a>
                                                </h3>
                                                <div class="price-product">
                                                    <ins>
                                                        <span>350.000đ</span>
                                                    </ins>
                                                    <del>
                                                        <span>990.000đ</span>
                                                    </del>
                                                </div>
                                            </div>
                                            <div class="add-to-cart">
                                                <a href="javascript:void(0)"><i class="icon-ic_shopping-bag"></i></a>
                                            </div>
                                            <div class="list-size">
                                                <ul>
                                                    <li data-product-sub-id="200657"><button
                                                            class="btn bt-large">s</button></li>
                                                    <li data-product-sub-id="200658"><button
                                                            class="btn bt-large">m</button></li>
                                                    <li data-product-sub-id="200659"><button
                                                            class="btn bt-large">l</button></li>
                                                    <li data-product-sub-id="200660"><button
                                                            class="btn bt-large">xl</button></li>
                                                    <li data-product-sub-id="200661"><button
                                                            class="btn bt-large">xxl</button></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="link-product">
                                    <a href="https://ivymoda.com/danh-muc/nam?hid_best_seller=1" class="all-product">Xem tất cả4</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- End Best Seller -->

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

        {{-- <div class="modal-gift" id="modal-gift-ctsales">
            <a href="https://ivymoda.com/danh-muc/sale-up-to-50" class="d-block">
                <img src="https://ivymoda.com/assets/images/popup/popup_25_08.jpg" alt="gift">
            </a>
        </div>
        <script type="text/javascript">
            $('#modal-gift-ctsales a').on('click', function() {
                window.location.href = $(this).attr('href');
            });
        </script> --}}
    </body>
@endsection

@section('scripts')
    {{-- JS xử lý Banner, Slider --}}
    <script>
        $(document).ready(function() {
            // Đợi DOM load xong
            setTimeout(function() {
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
    </script>

    {{-- JS xử lý khi click màu -> hiển thị ảnh + giá tương ứng --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.color-picker').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    e.preventDefault();

                    const productId = this.dataset.product;
                    const image = this.dataset.image;
                    const price = parseInt(this.dataset.price);
                    const sale = parseInt(this.dataset.sale);

                    const wrapper = document.querySelector(`.product[data-product-id="${productId}"]`);
                    if (!wrapper) return;

                    // 1. Đổi ảnh sản phẩm
                    wrapper.querySelector('.product-img').src = image;

                    // 2. Đổi giá
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
@endsection
