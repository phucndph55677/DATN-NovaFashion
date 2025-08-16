@extends('client.layouts.app')

@section('title', 'Sản phẩm yêu thích')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div class="breadcrumb-products">
                <ol class="breadcrumb__list">
                    <li class="breadcrumb__item"><a class="breadcrumb__link" href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb__item"><a href="{{ route('account.favorites.index') }}" class="breadcrumb__link" title="Sản phẩm yêu thích">Sản phẩm yêu thích</a></li>
                </ol>
            </div>

            <div class="order-wrapper mt-40 my-account">
                <div class="row">
                    <div class="col-lg-4 col-xl-auto">
                        @include('client.account.sidebar')
                    </div>

                    <div class="col-lg-8 col-xl">
                        <div class="order-block__title">
                            <h2>SẢN PHẨM YÊU THÍCH</h2>
                            <div class="form-group">
                                <label>Sắp xếp:</label>
                                <form method="GET">
                                    <select name="sort" class="form-control rounded" onchange="this.form.submit()">
                                        <option value="">Tất cả</option>
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                                    </select>
                                </form>
                            </div>
                        </div>

                        <div class="sub-main-prod">
                            <div class="list-products list-products-cat d-flex">
                                @forelse ($favorites as $favorite)
                                    @php
                                        $product = $favorite->product; // Quan hệ product
                                        $variant = $product->variants->first(); // Lấy biến thể mặc định nếu có

                                        $favorites = Auth::user() ? Auth::user()->favorites->pluck('product_id')->toArray() : [];
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
                                                            <li class="{{ $colorVariant->color_id == $variant->color_id ? 'checked' : '' }}">
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
                                                                document.getElementById('favorite-form-{{ $product->id }}').submit();
                                                                let icon = this;
                                                                if(icon.getAttribute('fill') === 'red'){
                                                                    icon.setAttribute('fill', 'none');
                                                                    icon.setAttribute('stroke', 'currentColor');
                                                                } else {
                                                                    icon.setAttribute('fill', 'red');
                                                                    icon.setAttribute('stroke', 'white');
                                                                }
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
                                        </div>
                                    </div>
                                @empty
                                    <div class="no-favorites" style="padding: 30px; width: 100%; text-align: center; font-size: 16px; color: #555;">
                                        Bạn chưa có sản phẩm yêu thích nào.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    {{-- JS xử lý khi click màu -> hiển thị ảnh + giá tương ứng --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.color-picker').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    e.preventDefault();

                    // tìm product wrapper gần nhất
                    const wrapper = this.closest('.product');
                    if (!wrapper) return;

                    const image = this.dataset.image;
                    const price = parseInt(this.dataset.price);
                    const sale = parseInt(this.dataset.sale);

                    // 1. Đổi ảnh sản phẩm
                    const imgEl = wrapper.querySelector('.product-img');
                    if (imgEl) imgEl.src = image;

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
@endsection
