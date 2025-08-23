@extends('client.layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div class="cart pt-40 cart-page">
                <div class="row">
                    <div class="col-lg-9">
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
                                                   <p style="white-space: nowrap;">
                                                        {{ number_format($cartDetail->price, 0, ',', '.') }} VND
                                                    </p>
                                                </td>

                                                <td class="product-quantity">
                                                    <div class="quantity-control">
                                                        <button type="button" class="quantity-decrease qty-btn" aria-label="Giảm">-</button>
                                                        <input 
                                                            type="number" 
                                                            name="quantity" 
                                                            class="quantity-input qty-input"
                                                            value="{{ $cartDetail->quantity }}" 
                                                            data-price="{{ $cartDetail->price }}"
                                                            data-max="{{ $cartDetail->productVariant->quantity ?? 999 }}" />
                                                        <button type="button" class="quantity-increase qty-btn" aria-label="Tăng">+</button>
                                                    </div>
                                                </td>

                                                <td class="product-total-amount">
                                                    <p style="white-space: nowrap; font-weight: bold;">
                                                        {{ number_format($cartDetail->total_amount, 0, ',', '.') }} VND
                                                    </p> 
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
                    
                    <div class="col-lg-3">
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
                const cartDetailId = wrapper.closest('tr').querySelector('input[type="checkbox"]').dataset.id;

                decreaseBtn?.addEventListener("click", () => {
                    let val = parseInt(quantityInput.value);
                    if (val > 1) {
                        quantityInput.value = val - 1;
                        updateQuantityInDatabase(cartDetailId, val - 1);
                    }

                    const checkbox = wrapper.closest('tr').querySelector('input[type="checkbox"]');
                    if (checkbox && !checkbox.checked) checkbox.checked = true;

                    updateTotals();
                });

                increaseBtn?.addEventListener("click", () => {
                    let val = parseInt(quantityInput.value);
                    const maxQty = parseInt(quantityInput.getAttribute('data-max')) || 999;
                    
                    if (val < maxQty) {
                        quantityInput.value = val + 1;
                        updateQuantityInDatabase(cartDetailId, val + 1);

                        const checkbox = wrapper.closest('tr').querySelector('input[type="checkbox"]');
                        if (checkbox && !checkbox.checked) checkbox.checked = true;

                        updateTotals();
                    } else {
                        // Hiển thị thông báo khi vượt quá số lượng
                        showToast(`Số lượng tối đa có thể chọn: ${maxQty}`, 'warning');
                    }
                });

                quantityInput?.addEventListener("input", () => {
                    let val = parseInt(quantityInput.value);
                    const maxQty = parseInt(quantityInput.getAttribute('data-max')) || 999;
                    
                    if (isNaN(val) || val < 1) {
                        quantityInput.value = 1;
                        val = 1;
                    } else if (val > maxQty) {
                        quantityInput.value = maxQty;
                        val = maxQty;
                        // Hiển thị thông báo khi vượt quá số lượng
                        showToast(`Số lượng tối đa có thể chọn: ${maxQty}`, 'warning');
                    }

                    const checkbox = wrapper.closest('tr').querySelector('input[type="checkbox"]');
                    if (checkbox && !checkbox.checked) checkbox.checked = true;

                    updateQuantityInDatabase(cartDetailId, val);
                    updateTotals();
                });
            });

            // Hàm cập nhật số lượng trong database
            function updateQuantityInDatabase(cartDetailId, quantity) {
                // Hiển thị loading nếu cần
                const row = document.querySelector(`input[data-id="${cartDetailId}"]`).closest('tr');
                const quantityInput = row.querySelector('.quantity-input');
                const originalValue = quantityInput.value;

                quantityInput.disabled = true; // Disable input trong khi chờ server

                fetch('{{ route("carts.updateQuantity") }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        cart_detail_id: cartDetailId,
                        quantity: quantity
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // ✅ Cập nhật tổng tiền của dòng này từ server
                        const lineTotalCell = row.querySelector(".product-total-amount p");
                        if (lineTotalCell) {
                            lineTotalCell.textContent = data.data.total_amount.toLocaleString('vi-VN') + " VND";
                        }

                        // ✅ Cập nhật data-max nếu server trả về thông tin mới về tồn kho
                        if (data.data.available_quantity !== undefined) {
                            quantityInput.setAttribute('data-max', data.data.available_quantity);
                        }

                        // ✅ Gọi lại tính tổng theo checkbox đã chọn
                        updateTotals();

                        showToast('Cập nhật số lượng thành công!', 'success');
                    } else {
                        console.error('Lỗi:', data.message);
                        
                        // Kiểm tra nếu lỗi liên quan đến số lượng tồn kho
                        if (data.message && data.message.includes('tồn kho') || data.message.includes('quantity')) {
                            showToast('Số lượng vượt quá tồn kho hiện có!', 'warning');
                        } else {
                            showToast('Có lỗi xảy ra: ' + data.message, 'error');
                        }
                        
                        quantityInput.value = originalValue; // Khôi phục giá trị cũ
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    showToast('Có lỗi xảy ra khi cập nhật số lượng', 'error');
                    quantityInput.value = originalValue; // Khôi phục giá trị cũ
                })
                .finally(() => {
                    quantityInput.disabled = false; // Enable lại input
                });
            }

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

    {{-- ========================================
         CSS CHO ĐIỀU KHIỂN SỐ LƯỢNG
    ======================================== --}}
    <style>
        .product-quantity { 
            margin: 8px 0; 
        }
        
        .quantity-control { 
            background: #fff; 
            border: 1.5px solid #ddd; 
            border-radius: 10px; 
            padding: 6px; 
            gap: 6px; 
            display: flex;
            align-items: center;
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
            transition: background-color 0.2s ease;
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
        
        /* Responsive */
        @media (max-width: 768px) {
            .qty-btn {
                width: 32px;
                height: 32px;
            }
            
            .qty-input {
                width: 50px;
                height: 32px;
            }
        }
    </style>
@endsection
