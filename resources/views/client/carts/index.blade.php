@extends('client.layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div class="cart pt-40 cart-page">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="checkout-process-bar block-border">
                            <ul>
                                <li class="active"><span>Giỏ hàng </span></li>
                                <li class=""><span>Đặt hàng</span></li>
                                <li class=""><span>Thanh toán</span></li>
                                <li><span>Hoàn thành đơn</span></li>
                            </ul>
                        </div>
                        <div id="box_product_total_cart">
                            <div class="cart__list">
                                <h2 class="cart-title">Giỏ hàng của bạn <b><span class="cart-total">{{ $cartDetails->sum('quantity') }}</span> Sản phẩm</b></h2>

                                <table class="cart__table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="check-all" class="form-check-input" style="width: 16px; height: 16px;">
                                            </th>
                                            <th>Tên Sản phẩm</th>
                                            <th>Giá</th>
                                            <th>Số lượng</th>
                                            <th>Tổng tiền</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cartDetails as $cartDetail)
                                            @php
                                                $productVariant = $cartDetail->productVariant;
                                                $product = $productVariant?->product;

                                                $checked = session('buy_now_variant_id') == $cartDetail->product_variant_id ? 'checked' : '';
                                            @endphp

                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="form-check-input" style="width: 16px; height: 16px;" data-id="{{ $cartDetail->id }}" {{ $checked }}>
                                                </td>
                                                <td>
                                                    <div class="cart__product-item">
                                                        <div class="cart__product-item__img">
                                                            <a href="{{ route('products.show', $product->id ?? '#') }}">
                                                                <img src="{{ asset('storage/' . ($productVariant?->image ?? 'default.png')) }}">
                                                            </a>
                                                        </div>
                                                        <div class="cart__product-item__content">
                                                            <a href="{{ route('products.show', $product->id ?? '#') }}">
                                                                {{ $product->name ?? 'Sản phẩm' }}
                                                            </a>
                                                            <div class="cart__product-item__properties">
                                                                <p>Màu sắc: <span>{{ $productVariant->color->name ?? 'Không xác định' }}</span></p>
                                                                <p>Size: <span style="text-transform: uppercase">{{ $productVariant->size->name ?? '' }}</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="product-price">
                                                   <p>{{ number_format($cartDetail->price, 0, ',', '.') }} VND</p>
                                                </td>

                                                <td class="product-quantity">
                                                    <div>
                                                        <div style="display: flex; align-items: center; gap: 10px;">
                                                            <button type="button" class="quantity-decrease" style="width: 32px;">-</button>
                                                            <input 
                                                                type="number" 
                                                                name="quantity" 
                                                                class="quantity-input"
                                                                value="{{ $cartDetail->quantity }}" 
                                                                style="width: 60px; text-align: center;"
                                                                data-price="{{ $cartDetail->price }}" />
                                                            <button type="button" class="quantity-increase" style="width: 32px;">+</button>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="product-total-amount">
                                                    <p>{{ number_format($cartDetail->total_amount, 0, ',', '.') }} VND</p> 
                                                </td>

                                                <td>
                                                    <form action="{{ route('carts.delete', $cartDetail->id) }}" method="POST">
                                                        @csrf
                                                        
                                                        <button type="submit" style="border: none; background: none; padding: 0; cursor: pointer;"
                                                            class=" btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?')">
                                                            <span class="icon-ic_garbage"></span>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <a href="javascript: window.history.back();" class="btn btn--large btn--outline btn-cart-continue mb-3">
                            <span class="icon-ic_left-arrow"></span>
                            Tiếp tục mua hàng
                        </a>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="cart-summary" id="cart-page-summary">
                            <div class="cart-summary__overview">
                                <h3>Tổng tiền giỏ hàng</h3>
                                <div class="cart-summary__overview__item">
                                    <p>Tổng sản phẩm</p>
                                    <p class="produc-total">{{ $cartDetails->sum('quantity') }}</p>
                                </div>
                                <div class="cart-summary__overview__item">
                                    <p>Tổng tiền hàng</p>
                                    <p><b class="product-total-amount">{{ number_format($cart->total_amount, 0, ',', '.') }} VND</b></p> 
                                </div>
                            </div>

                            <div class="cart-summary__note">
                                <div class="inner-note d-flex">
                                    <div class="left-inner-note">
                                        <span class="icon-ic_alert"></span>
                                    </div>
                                    <div class="content-inner-note">
                                        Sản phẩm nằm trong chương trình KM giảm giá trên 50% không hỗ trợ đổi trả
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="cart-summary__button">
                            <a href="#" class="btn btn--large" id="btn-checkout">Đặt hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const updateTotals = () => {
                let totalQuantity = 0;
                let totalAmount = 0;

                // Lặp qua từng dòng sản phẩm
                document.querySelectorAll("tbody tr").forEach((row) => {
                    const checkbox = row.querySelector('input[type="checkbox"]');
                    if (!checkbox?.checked) return; // Bỏ qua nếu chưa được chọn

                    const quantityInput = row.querySelector(".quantity-input");
                    const price = parseInt(quantityInput.dataset.price);
                    const quantity = parseInt(quantityInput.value);
                    const lineTotal = price * quantity;

                    totalQuantity += quantity;
                    totalAmount += lineTotal;

                    // Cập nhật tổng tiền từng dòng (có thể giữ lại hoặc không)
                    const lineTotalCell = row.querySelector(".product-total-amount p");
                    if (lineTotalCell) {
                        lineTotalCell.textContent = lineTotal.toLocaleString('vi-VN') + " VND";
                    }
                });

                // Cập nhật tổng tiền và tổng sản phẩm
                document.querySelector(".cart-summary .product-total-amount").textContent = totalAmount.toLocaleString('vi-VN') + " VND";
                document.querySelector(".cart-summary .produc-total").textContent = totalQuantity;

                const totalQuantityTitle = document.querySelector(".cart-title .cart-total");
                if (totalQuantityTitle) {
                    totalQuantityTitle.textContent = totalQuantity;
                }
            };

            // Chọn tất cả
            const checkAll = document.getElementById('check-all');
            const itemCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');

            checkAll?.addEventListener('change', function () {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                updateTotals();
            });

            // Mỗi checkbox thay đổi
            itemCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateTotals);
            });

            // Gắn sự kiện cho nút + -
            document.querySelectorAll(".product-quantity").forEach(wrapper => {
                const decreaseBtn = wrapper.querySelector(".quantity-decrease");
                const increaseBtn = wrapper.querySelector(".quantity-increase");
                const quantityInput = wrapper.querySelector(".quantity-input");

                decreaseBtn?.addEventListener("click", () => {
                    let val = parseInt(quantityInput.value);
                    if (val > 1) quantityInput.value = val - 1;

                    const checkbox = wrapper.closest('tr').querySelector('input[type="checkbox"]');
                    if (checkbox && !checkbox.checked) checkbox.checked = true;

                    updateTotals();
                });

                increaseBtn?.addEventListener("click", () => {
                    let val = parseInt(quantityInput.value);
                    quantityInput.value = val + 1;

                    const checkbox = wrapper.closest('tr').querySelector('input[type="checkbox"]');
                    if (checkbox && !checkbox.checked) checkbox.checked = true;

                    updateTotals();
                });

                quantityInput?.addEventListener("input", () => {
                    let val = parseInt(quantityInput.value);
                    if (isNaN(val) || val < 1) quantityInput.value = 1;

                    const checkbox = wrapper.closest('tr').querySelector('input[type="checkbox"]');
                    if (checkbox && !checkbox.checked) checkbox.checked = true;

                    updateTotals();
                });
            });

            // Gọi lần đầu để đảm bảo đồng bộ
            updateTotals();
        });
    </script>

    {{-- JS xử lý sự kiện click nút "Đặt hàng" --}}
    <script>
        document.getElementById('btn-checkout')?.addEventListener('click', function (e) {
            e.preventDefault();

            const selectedIds = Array.from(document.querySelectorAll('tbody input[type="checkbox"]:checked'))
                .map(cb => cb.dataset.id);

            if (selectedIds.length === 0) {
                alert('Vui lòng chọn ít nhất một sản phẩm để đặt hàng.');
                return;
            }

            // Điều hướng sang trang checkouts với query: ?ids=1,2,3
            const url = `{{ route('checkouts.index') }}?ids=${selectedIds.join(',')}`;
            window.location.href = url;
        });
    </script>
@endsection
