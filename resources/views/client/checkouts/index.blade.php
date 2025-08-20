@extends('client.layouts.app')

@section('title', 'Đặt Hàng')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div class="cart pt-40 checkout">
                <form action="{{ route('checkouts.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-lg-8 col-2xl-9">
                            <div class="checkout-process-bar block-border">
                                <ul>
                                    <li class="active"><span>Giỏ hàng </span></li>
                                    <li class="active"><span>Đặt hàng</span></li>
                                    <li class=""><span>Thanh toán</span></li>
                                    <li><span>Hoàn thành đơn</span></li>
                                </ul>
                                <p class="checkout-process-bar__title">Giỏ hàng</p>
                            </div>

                            <div class="checkout-address-delivery">
                                <div class="row">
                                    <div class="col-12 col-2xl-7 pb-3">
                                        <h3 class="checkout-title">Địa chỉ giao hàng</h3>
                                        
                                        <label class="block-border">
                                            @php
                                                $filteredErrors = collect($errors->all())->filter(function ($error) {
                                                    return $error !== 'Vui lòng chọn Phương thức thanh toán.';
                                                });
                                            @endphp

                                            @if ($filteredErrors->isNotEmpty())
                                                <div class="alert alert-danger">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <ul class="mb-0 mt-1">
                                                        @foreach ($filteredErrors as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <input class="ds__item__input" type="radio" name="" checked="">
                                            <div class="ds__item__contact-info">
                                                <div class="row">
                                                    <div class="col-6 form-group">
                                                        <input type="text" class="form-control" name="name" placeholder="Họ tên" value="{{ old('name') }}">
                                                    </div>
                                                    <div class="col-6 form-group">
                                                        <input type="text" class="form-control" name="phone" placeholder="Số điện thoại" value="{{ old('phone') }}">
                                                    </div>
                                                    <div class="col-6 form-group">
                                                        <select class="form-control" name="province" id="province">
                                                            <option selected disabled>Tỉnh/Thành phố</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6 form-group">
                                                        <select class="form-control" name="district" id="district">
                                                            <option selected disabled>Quận/Huyện</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6 form-group">
                                                        <select class="form-control" name="ward" id="ward">
                                                            <option selected disabled>Phường/Xã</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6 form-group">
                                                        <input type="text" class="form-control" name="address" placeholder="Địa chỉ" value="{{ old('address') }}">
                                                    </div>
                                                    <div class="col-12 form-group">
                                                        <input type="text" class="form-control" name="note" placeholder="Ghi chú" value="{{ old('note') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div class="col-12 col-2xl-5">
                                        <h3 class="checkout-title">Phương thức thanh toán</h3>
                                        <div class="block-border">
                                            {{-- Hiển thị lỗi riêng cho payment_method_id --}}
                                            @if ($errors->has('payment_method_id'))
                                                <div class="alert alert-danger">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <ul class="mb-0 mt-1">
                                                        <li>{{ $errors->first('payment_method_id') }}</li>
                                                    </ul>
                                                </div>
                                            @endif
                                            
                                            <p>Mọi giao dịch đều được bảo mật.</p><hr>
                                            <div class="">
                                                @foreach($paymentMethods as $method)
                                                    <label class="ds__item">
                                                        <input class="ds__item__input"
                                                            type="radio"
                                                            name="payment_method_id"
                                                            value="{{ $method->id }}"
                                                            {{ old('payment_method_id') == $method->id ? 'checked' : '' }}>
                                                        <span class="ds__item__label">
                                                            {{ $method->name }}
                                                            @if($method->code === 'vnpay')
                                                                <span class="text-muted small">Hỗ trợ thanh toán online các ngân hàng phổ biến Việt Nam</span>
                                                            @endif
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>                    
                                    </div>
                                </div>
                            </div>

                            <div class="view-more-product">
                                <span class="btn btn--large">Hiển thị sản phẩm</span>
                            </div>
                            <div class="checkout-my-cart">
                                <div class="cart__list">
                                    <h2 class="cart-title">Giỏ hàng của bạn</h2>
                                    <table class="cart__table">
                                        <thead>
                                            <tr>
                                                <th>Tên Sản phẩm</th>
                                                <th>Giá</th>
                                                <th>Số lượng</th>
                                                <th>Tổng tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($cartDetails as $cartDetail)
                                                @php
                                                    $productVariant = $cartDetail->productVariant;
                                                    $product = $productVariant?->product;
                                                @endphp
                                                
                                                <tr>
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
                                                        <div class="product-detail__quantity-input">
                                                            <input type="number" name="quantity" class="quantity-input" value="{{ $cartDetail->quantity }}" readonly="">                                          
                                                        </div>
                                                    </td>

                                                    <td class="product-total-amount">
                                                        <p>{{ number_format($cartDetail->total_amount, 0, ',', '.') }} VND</p> 
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- TÍNH TOÁN TỔNG GIÁ TRỊ --}}
                        @php
                            $totalQuantity = $cartDetails->sum('quantity');
                            $totalAmount = $cartDetails->sum(fn($item) => $item->quantity * $item->price);
                            $shippingFee = 30000;
                            $appliedVoucher = session('applied_voucher');
                            $discountAmount = $appliedVoucher['discount'] ?? 0;
                            $finalAmount = $totalAmount - $discountAmount + $shippingFee;
                        @endphp

                        <div class="col-lg-4 col-2xl-3 cart-page__col-summary">  
                            <div class="cart-summary">
                                <div id="box_product_total">
                                    <div class="cart-summary__overview">
                                        <h3>Tóm tắt đơn hàng</h3>
                                        <div class="cart-summary__overview__item">
                                            <p>Tổng sản phẩm</p>
                                            <p id="total_quantity">{{ $totalQuantity }}</p>
                                        </div>
                                        <div class="cart-summary__overview__item">
                                            <p>Tổng tiền hàng</p>
                                            <p id="total_amount">{{ number_format($totalAmount, 0, ',', '.') }} VND</p>
                                        </div>
                                        <div class="cart-summary__overview__item">
                                            <p>Giảm giá</p>
                                            <p id="discount_amount">{{ number_format($discountAmount, 0, ',', '.') }} VND</p>   
                                        </div>
                                        <div class="cart-summary__overview__item">
                                            <p>Phí vận chuyển</p>
                                            <p>{{ number_format($shippingFee, 0, ',', '.') }} VND</p>
                                        </div>
                                        <div class="cart-summary__overview__item">
                                            <p>Tiền thanh toán</p>
                                            <p><b id="final_amount">{{ number_format($finalAmount, 0, ',', '.') }} VND</b></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="cart-summary__voucher-form">
                                    <div class="cart-summary__voucher-form__title">
                                        <h4 class="active">Mã phiếu giảm giá</h4>
                                        {{-- <span> </span>
                                        <h4 data-toggle="modal" data-target="#myVoucherWallet">Mã của tôi</h4>
                                        <div class="modal fade voucher-wallet" id="myVoucherWallet" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="exampleModalLabel">Danh sách mã Voucher</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body box-voucher-wallet">
                                                        <p>Rất tiếc, bạn không còn mã giảm giá nào !</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                    <p class="" id="p_coupon" style="padding-top: 5px; display: none; text-align: center"></p>

                                    <!-- Các trường ẩn để gửi thông tin phiếu giảm giá đã áp dụng -->
                                    <input type="hidden" name="voucher_id" id="voucher_id" value="{{ $appliedVoucher['id'] ?? '' }}">
                                    <input type="hidden" name="voucher_discount" id="voucher_discount" value="{{ $appliedVoucher['discount'] ?? '' }}">

                                    <div class="form-group">
                                        <input class="form-control" type="text" placeholder="Mã giảm giá" name="voucher_code" id="voucher_code_text">
                                        <button type="button" class="btn btn--large btn--outline" id="voucher_apply" style="{{ $appliedVoucher ? 'display:none;' : '' }}">Áp dụng</button>
                                        <button type="button" class="btn btn--large" id="but_coupon_delete" style="{{ $appliedVoucher ? '' : 'display:none;' }}">Bỏ Mã</button>
                                    </div>
                                </div>
                            </div>

                            @foreach ($cartDetails as $cartDetail)
                                <input type="hidden" name="cart_detail_ids[]" value="{{ $cartDetail->id }}">
                            @endforeach

                            <div class="cart-summary__button">
                                <button type="submit" class="btn btn--large">Hoàn thành</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    {{-- JS xử lý load danh sách địa chỉ --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const provinceSelect = document.getElementById("province");
            const districtSelect = document.getElementById("district");
            const wardSelect = document.getElementById("ward");

            const oldProvince = "{{ old('province') }}";
            const oldDistrict = "{{ old('district') }}";
            const oldWard = "{{ old('ward') }}";

            // 1. Load danh sách Tỉnh
            fetch("https://provinces.open-api.vn/api/p/")
                .then(res => res.json())
                .then(data => {
                    data.forEach(province => {
                        const option = new Option(province.name, province.code);
                        if (province.code == oldProvince) option.selected = true;
                        provinceSelect.add(option);
                    });

                    if (oldProvince) loadDistricts(oldProvince); // Nếu có old thì load tiếp quận
                });

            provinceSelect.addEventListener("change", function () {
                loadDistricts(this.value);
            });

            // 2. Load Huyện theo tỉnh
            function loadDistricts(provinceCode) {
                districtSelect.innerHTML = '<option selected disabled>Quận/Huyện</option>';
                wardSelect.innerHTML = '<option selected disabled>Phường/Xã</option>';

                fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                    .then(res => res.json())
                    .then(data => {
                        data.districts.forEach(district => {
                            const option = new Option(district.name, district.code);
                            if (district.code == oldDistrict) option.selected = true;
                            districtSelect.add(option);
                        });

                        if (oldDistrict) loadWards(oldDistrict); // Nếu có old thì load tiếp xã
                    });
            }

            districtSelect.addEventListener("change", function () {
                loadWards(this.value);
            });

            // 3. Load Phường theo huyện
            function loadWards(districtCode) {
                wardSelect.innerHTML = '<option selected disabled>Phường/Xã</option>';

                fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                    .then(res => res.json())
                    .then(data => {
                        data.wards.forEach(ward => {
                            const option = new Option(ward.name, ward.code);
                            if (ward.code == oldWard) option.selected = true;
                            wardSelect.add(option);
                        });
                    });
            }
        });
    </script>

    {{-- JS xử lý Voucher --}}
    <script>
        function formatVND(amount) {
            return amount.toLocaleString('vi-VN') + " VND";
        }

        const totalAmount = Number({{ $totalAmount }});
        const shipping    = Number({{ $shippingFee }});

        function updateCouponUI({ message = '', color = '', discount = 0, final = null, voucherId = '' }) {
            
            $('#p_coupon').text(message).css("color", color).toggle(!!message);

            $('#discount_amount').text((discount > 0 ? "-" : "") + formatVND(discount));

            // final là subtotal sau giảm (chưa bao gồm ship) => cộng ship khi hiển thị
            const payable = (final ?? totalAmount) + shipping;
            $('#final_amount').text(formatVND(payable));

            $('#voucher_id').val(voucherId);
            $('#voucher_discount').val(discount);

            $('#voucher_apply').toggle(!voucherId);
            $('#but_coupon_delete').toggle(!!voucherId);
        }

        // Áp dụng voucher
        $(document).on('click', '#voucher_apply', function () {
            const code = $('#voucher_code_text').val();

            $.ajax({
                url: "{{ route('vouchers.apply') }}",
                method: "POST",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    voucher_code: code,
                    total_amount: totalAmount
                },
                success: function (res) {
                    updateCouponUI({
                        message: res.message,
                        color: res.success ? "green" : "red",
                        discount: res.discount ?? 0,
                        final: res.final_amount ?? null,
                        voucherId: res.voucher?.id ?? ''
                    });
                },
                error: function (xhr) {
                    const err = xhr.responseJSON;
                    const msg = err?.errors?.voucher_code?.[0] || err?.error || 'Có lỗi xảy ra, vui lòng thử lại!';
                    updateCouponUI({ message: msg, color: "red" });
                }
            });
        });

        // Bỏ voucher
        $(document).on('click', '#but_coupon_delete', function () {
            $.ajax({
                url: "{{ route('vouchers.clear') }}",
                method: "POST",
                data: { _token: "{{ csrf_token() }}" },
                success: function(res) {
                    // reset UI
                    updateCouponUI({});
                    $('#voucher_code_text').val('');
                },
                error: function() {
                    alert('Không thể xóa voucher, vui lòng thử lại!');
                }
            });
        });
    </script>
@endsection