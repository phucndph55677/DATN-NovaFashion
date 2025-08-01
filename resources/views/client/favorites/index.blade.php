@extends('client.layouts.app')

@section('title', 'Sản Phẩm Yêu Thích')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div id="product_detail" class="row">
                <div class="container">
                    <div class="breadcrumb-products">
                        <ol class="breadcrumb__list">
                            <li class="breadcrumb__item"><a href="{{ route('home') }}" class="breadcrumb__link">Trang
                                    chủ</a>
                            </li>
                            <li class="breadcrumb__item"><a href="#" class="breadcrumb__link">Sản phẩm yêu thích</a>
                            </li>
                        </ol>
                    </div>
                    <div>
                        <form method="GET" class="mb-4">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <label for="sort" class="col-form-label fw-bold">Sắp xếp:</label>
                                </div>
                                <div class="col-auto">
                                    <select name="sort" id="sort" onchange="this.form.submit()" class="form-select">
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất
                                        </option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá
                                            tăng dần</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                            Giá giảm dần</option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên
                                            A-Z</option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên
                                            Z-A</option>
                                    </select>
                                </div>

                            </div>
                        </form>

                    </div>


                    @if ($products->isEmpty())
                        <div class="text-center my-5 py-5">Bạn chưa có sản phẩm yêu thích nào.</div>
                    @else
                        <div class="list-products d-flex flex-wrap my-5" style="column-gap: 24px; row-gap: 100px;">

                            @foreach ($products as $product)
                                @php
                                    $variant = $product->variants->first();
                                    $favorites = session('favorites', []);
                                @endphp
                                <div class="item-new-prod" style="flex: 0 0 calc(20% - 24px);">

                                    <div class="product" data-product-id="{{ $product->id }}">
                                        <div class="thumb-product">
                                            <a href="{{ route('products.show', $product->id) }}">
                                                <img class="product-img"
                                                    src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}">
                                            </a>
                                        </div>
                                        <div class="info-product">
                                            <div class="list-color">z
                                                <ul>
                                                    @foreach ($product->variants->unique('color_id') as $colorVariant)
                                                        <li class="{{ $loop->first ? 'checked' : '' }}">
                                                            <a href="#" class="color-picker"
                                                                data-image="{{ asset('storage/' . $colorVariant->image) }}"
                                                                data-price="{{ $colorVariant->price }}"
                                                                data-sale="{{ $colorVariant->sale }}"
                                                                data-product="{{ $product->id }}">
                                                                <span
                                                                    style="display:inline-block;width:20px;height:20px;border-radius:50%;background-color:{{ $colorVariant->color->color_code }};border:1px solid {{ strtolower($colorVariant->color->color_code) === '#ffffff' ? '#ccc' : 'transparent' }};"></span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <div class="my-favorite {{ in_array($product->id, $favorites) ? 'active' : '' }}"
                                                    data-id="{{ $product->id }}">
                                                    <svg width="24" height="24" fill="none" stroke="currentColor"
                                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-heart">
                                                        <path
                                                            d="M20.8 4.6c-1.5-1.4-3.9-1.4-5.4 0l-.9.9-.9-.9c-1.5-1.4-3.9-1.4-5.4 0-1.6 1.5-1.6 4 0 5.5l6.3 6.2 6.3-6.2c1.6-1.5 1.6-4 0-5.5z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <h3 class="title-product">
                                                <a href="#">{{ $product->name }}</a>
                                            </h3>
                                            <div class="price-product">
                                                @if ($variant && $variant->sale > 0 && $variant->sale < $variant->price)
                                                    <ins><span>{{ number_format($variant->sale, 0, ',', '.') }}
                                                            VND</span></ins>
                                                    <del><span>{{ number_format($variant->price, 0, ',', '.') }}
                                                            VND</span></del>
                                                @elseif ($variant)
                                                    <ins><span>{{ number_format($variant->price, 0, ',', '.') }}
                                                            VND</span></ins>
                                                @else
                                                    <ins><span>Liên hệ</span></ins>
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
                    @endif


                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        const swiper = new Swiper(".mySwiper", {
            slidesPerView: 1.2,
            spaceBetween: 16,
            breakpoints: {
                576: {
                    slidesPerView: 2.2
                },
                768: {
                    slidesPerView: 3.2
                },
                992: {
                    slidesPerView: 4.2
                }
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    </script>

    {{-- JS xử lý khi click màu -> hiển thị ảnh + giá tương ứng --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.color-picker').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();

                    const productId = this.dataset.product;
                    const image = this.dataset.image;
                    const price = parseInt(this.dataset.price);
                    const sale = parseInt(this.dataset.sale);

                    const wrapper = document.querySelector(
                        `.product[data-product-id="${productId}"]`);
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
                    wrapper.querySelectorAll('.list-color li').forEach(li => li.classList.remove(
                        'checked'));
                    this.closest('li').classList.add('checked');
                });
            });
        });
    </script>

    <script>
        // Thêm CSS động
        const style = document.createElement('style');
        style.innerHTML = `
            .my-favorite { cursor: pointer; }
            .my-favorite.active svg { fill: red; stroke: red; transition: 0.2s; }
        `;
        document.head.appendChild(style);

        // Hàm cập nhật localStorage
        function updateLocalFavorites(productId, action) {
            let favorites = JSON.parse(localStorage.getItem('favorite_ids') || '[]');
            productId = parseInt(productId);
            if (action === 'add') {
                if (!favorites.includes(productId)) favorites.push(productId);
            } else {
                favorites = favorites.filter(id => id !== productId);
            }
            localStorage.setItem('favorite_ids', JSON.stringify(favorites));
        }

        // Khi load trang, đồng bộ trạng thái icon trái tim
        document.addEventListener('DOMContentLoaded', function() {
            let favorites = JSON.parse(localStorage.getItem('favorite_ids') || '[]');
            document.querySelectorAll('.my-favorite').forEach(function(el) {
                var productId = parseInt(el.getAttribute('data-id'));
                if (favorites.includes(productId)) {
                    el.classList.add('active');
                } else {
                    el.classList.remove('active');
                }
            });
            // Gắn sự kiện click
            document.querySelectorAll('.my-favorite').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (window.isLoggedIn === false || window.isLoggedIn === 'false') {
                        toastr.warning('Bạn cần đăng nhập để thực hiện chức năng này !');
                        return;
                    }
                    var heart = this;
                    var productId = parseInt(heart.getAttribute('data-id'));
                    fetch("{{ route('favorites.toggle') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                product_id: productId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'added') {
                                heart.classList.add('active');
                                updateLocalFavorites(productId, 'add');
                            } else {
                                heart.classList.remove('active');
                                updateLocalFavorites(productId, 'remove');
                                // Xóa sản phẩm khỏi giao diện
                                const productCard = heart.closest('.item-new-prod');
                                if (productCard) {
                                    productCard.remove();
                                }
                                // Nếu không còn sản phẩm nào, hiển thị thông báo
                                if (document.querySelectorAll('.item-new-prod').length === 0) {
                                    const listContainer = document.querySelector(
                                        '.list-products');
                                    if (listContainer) {
                                        listContainer.insertAdjacentHTML('afterend',
                                            '<div class="text-center my-5 py-5">Bạn chưa có sản phẩm yêu thích nào.</div>'
                                        );
                                    }
                                }
                            }
                        });
                });
            });
        });
    </script>
@endsection
@section('styles')
    <style>
        .item-new-prod {
            width: 220px;
            height: 400px;
            /* ✅ tăng chiều cao khung thẻ sản phẩm */
        }

        .thumb-product {
            height: 300px;
            /* ✅ giới hạn ảnh hiển thị trong vùng hợp lý */
            overflow: hidden;
        }

        .thumb-product img {
            width: 100%;
            height: 100%;
            /* ✅ ảnh sẽ fill toàn bộ khung .thumb-product */
            object-fit: cover;
        }
    </style>
@endsection
