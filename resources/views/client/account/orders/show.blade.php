@extends('client.layouts.app')

@section('title', 'Chi Tiết Đơn Hàng')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div class="breadcrumb-products">
                <ol class="breadcrumb__list">
                    <li class="breadcrumb__item"><a class="breadcrumb__link" href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb__item"><a href="{{ route('account.orders.index') }}" class="breadcrumb__link" title="Quản Lý Đơn Hàng">Quản Lý Đơn Hàng</a></li>
                    <li class="breadcrumb__item"><a href="{{ route('account.orders.show', $order->id) }}" class="breadcrumb__link" title="'Chi Tiết Đơn Hàng">Chi Tiết Đơn Hàng</a></li>
                </ol>
            </div>

            <div class="order-wrapper mt-40 order-detail">
                <div class="container">
                    <div class="row">
                        <div class="col-lg col-account-content">
                            <div class="order-block__title">
                                <h2>
                                    <span class="icon-ic_back"></span>CHI TIẾT ĐƠN HÀNG<b>{{ $order->order_code }}</b>
                                </h2>
                                <div class="order__status order--{{ Str::slug($order->orderStatus->name, '-') }}">
                                    <div style="margin-right: 15px">
                                        {{-- Trạng thái Chưa xác nhận, Đã xác nhận có thể hủy --}}
                                        @if(in_array($order->order_status_id, [1, 2, 3]))
                                            <form method="POST" action="{{ route('account.orders.cancel', $order->id) }}">
                                                @csrf
                                                <button type="submit"
                                                    onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này không?')"
                                                    style="border: none; background: none; color: red; text-decoration: underline;">
                                                    Hủy đơn
                                                </button>
                                            </form>

                                        {{-- Trạng thái đã hủy --}}
                                        @elseif($order->order_status_id == 9)
                                            <span style="color: red; font-weight: bold;">Đơn hàng đã hủy</span>

                                        {{-- Trạng thái Tthành công - cho phép Hoàn hàng --}}
                                        @elseif($order->order_status_id == 6)
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#returnOrderModal-{{ $order->id }}"
                                                style="border: none; background: none; color: blue; text-decoration: underline;">
                                                Hoàn hàng
                                            </a>
                                        @endif
                                    </div>

                                    <span style="margin-right: 5px" class="icon-ic_cube-1"></span>
                                    <span>{{ $order->orderStatus->name }}</span>
                                </div>
                            </div>

                            <div class="order-block row">
                                <div class="col-xl">
                                    <div class="order-block__products checkout-my-cart">
                                        <table class="cart__tables">
                                            <tbody>
                                                @foreach ($orderDetail as $orderDetails)
                                                    @php
                                                        $productVariant = $orderDetails->productVariant;
                                                        $product = $productVariant->product ?? null;
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
                                                                    <h3 class="cart__product-item__title">
                                                                        {{ $product->name ?? 'Sản phẩm' }}
                                                                    </h3>
                                                                    <div class="cart__product-item__properties">
                                                                        <p>Màu sắc: <span>{{ $productVariant->color->name ?? '' }}</span></p>
                                                                    </div>
                                                                    <div class="cart__product-item__properties">
                                                                        <p>Size: <span style="text-transform: uppercase">{{ $productVariant->size->name ?? '' }}</span></p>
                                                                    </div>
                                                                    <div class="cart__product-item__properties">
                                                                        <p>Số lượng: <span>{{ $orderDetails->quantity }}</span></p>
                                                                    </div>

                                                                    <!-- Form mua lại -->
                                                                    <div class="cart__product-item__btn--save">
                                                                        <form action="{{ route('carts.add') }}" method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="product_variant_id" value="{{ $productVariant->id }}">
                                                                            <input type="hidden" name="quantity" value="1">
                                                                            <button type="submit" class="btn btn--outline btn--large">Mua lại</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <div class="cart__product-item__price">
                                                                    <p><span>{{ number_format($orderDetails->price, 0, ',', '.') }} VND</span></p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="cart-summary">
                                        <div class="cart-summary__overview">
                                            <h3>Tóm tắt đơn hàng</h3>
                                            <div class="cart-summary__overview__item">
                                                <p>Ngày tạo đơn</p>
                                                <p><span>{{ $order->created_at->format('d/m/Y H:i A') }}</span></p>
                                            </div>
                                            <div class="cart-summary__overview__item">
                                                <p>Tổng sản phẩm</p>
                                                <p>{{ $order->total_quantity }}</p>
                                            </div>
                                            <div class="cart-summary__overview__item">
                                                <p>Tổng tiền hàng</p>
                                                <p>{{ number_format($order->subtotal, 0, ',', '.') }} VND</p>
                                            </div>
                                            <div class="cart-summary__overview__item">
                                                <p>Giảm giá</p>
                                                <p>{{ number_format($order->discount, 0, ',', '.') }} VND</p>
                                            </div>
                                            <div class="cart-summary__overview__item">
                                                <p>Phí vận chuyển</p>
                                                <p>{{ number_format($order->shipping_fee, 0, ',', '.') }} VND</p>
                                            </div>
                                            <div class="cart-summary__overview__item">
                                                <p>Tiền thanh toán</p>
                                                <p><b>{{ number_format($order->total_amount, 0, ',', '.') }} VND</b></p>
                                            </div>
                                        </div>

                                        <div class="cart-summary__payment">
                                            <h4>Hình thức thanh toán</h4>
                                            <div class="cart-summary__overview__item">
                                                <p>{{ $order->payment->paymentMethod->name ?? 'Không xác định' }}</p>
                                            </div>
                                        </div>

                                        <div class="cart-summary__delivery">
                                            <h4>Đơn vị vận chuyển</h4>
                                            <div class="cart-summary__overview__item">
                                                <p>NovaFashion</p>
                                            </div>
                                        </div>
                                        
                                        <div class="cart-summary__address">
                                            <h4>Thông tin người nhận</h4>
                                            <div class="cart-summary__overview__item">
                                                <p>Họ tên: {{ $order->name }}</p>
                                            </div>
                                            <div class="cart-summary__overview__item">
                                                <p>Số điện thoại: {{ $order->phone }}</p>
                                            </div>
                                            <div class="cart-summary__overview__item">
                                                <p>Địa chỉ: {{ $order->address }}</p>
                                            </div>
                                            <div class="cart-summary__overview__item">
                                                <p>Ghi chú: {{ $order->note }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="order-buttons">
                                <a class="btn btn--large btn--view-order-detail" href="{{ route('account.orders.track', $order->id) }}">Theo dõi đơn hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Hoàn hàng -->
    <div class="modal fade" id="returnOrderModal-{{ $order->id }}" tabindex="-1" aria-labelledby="returnOrderLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Hiển thị lỗi validate -->
                @if ($errors->any())
                    <div class="alert alert-danger m-3">
                        <ul class="mb-0">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- Nếu có lỗi thì tự động mở modal -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            var myModal = new bootstrap.Modal(document.getElementById("returnOrderModal-{{ $order->id }}"));
                            myModal.show();
                        });
                    </script>
                @endif

                <!-- Tiêu đề modal -->
                <div class="modal-header">
                    <h4 class="modal-title fw-bold text-dark" id="returnOrderLabel">Yêu cầu hoàn hàng - {{ $order->order_code }}</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <!-- Form gửi yêu cầu hoàn hàng -->
                <form action="{{ route('account.orders.return', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <!-- Lý do hoàn hàng -->
                        <div class="mb-3">
                            <label for="reason-{{ $order->id }}" class="form-label">
                                Lý do hoàn hàng <span style="color: red">*</span>
                            </label>
                            <select name="return_reason" id="reason-{{ $order->id }}" class="form-select">
                                <option value="" disabled {{ old('return_reason') == '' ? 'selected' : '' }}>-- Chọn lý do --</option>
                                <option value="Sản phẩm nhận được không đúng mô tả" {{ old('return_reason') == 'Sản phẩm nhận được không đúng mô tả' ? 'selected' : '' }}>
                                    Sản phẩm nhận được không đúng mô tả
                                </option>
                                <option value="Sản phẩm bị hư hỏng: Hàng bị vỡ, trầy xước, nứt" {{ old('return_reason') == 'Sản phẩm bị hư hỏng: Hàng bị vỡ, trầy xước, nứt' ? 'selected' : '' }}>
                                    Sản phẩm bị hư hỏng: Hàng bị vỡ, trầy xước, nứt
                                </option>
                                <option value="Hàng bị lỗi kỹ thuật" {{ old('return_reason') == 'Hàng bị lỗi kỹ thuật' ? 'selected' : '' }}>
                                    Hàng bị lỗi kỹ thuật
                                </option>
                                <option value="Thùng hàng không nguyên vẹn" {{ old('return_reason') == 'Thùng hàng không nguyên vẹn' ? 'selected' : '' }}>
                                    Thùng hàng không nguyên vẹn
                                </option>
                                <option value="Nhận sai sản phẩm" {{ old('return_reason') == 'Nhận sai sản phẩm' ? 'selected' : '' }}>
                                    Nhận sai sản phẩm
                                </option>
                                <option value="Chưa nhận được hàng" {{ old('return_reason') == 'Chưa nhận được hàng' ? 'selected' : '' }}>
                                    Chưa nhận được hàng
                                </option>
                                <option value="Sản phẩm giả/nhái" {{ old('return_reason') == 'Sản phẩm giả/nhái' ? 'selected' : '' }}>
                                    Sản phẩm giả/nhái
                                </option>
                                <option value="other" {{ old('return_reason') == 'other' ? 'selected' : '' }}>
                                    Khác...
                                </option>
                            </select>
                        </div>

                        <!-- Nếu chọn "Khác..." thì hiện ô nhập -->
                        <div class="mb-3" id="other-reason-wrapper-{{ $order->id }}" style="{{ old('return_reason') == 'other' ? '' : 'display:none;' }}">
                            <label for="other-reason-{{ $order->id }}" class="form-label">Lý do khác <span style="color: red">*</span></label>
                            <textarea name="other_reason" id="other-reason-{{ $order->id }}" class="form-control" rows="3" placeholder="Nhập lý do cụ thể...">{{ old('other_reason') }}</textarea>
                        </div>

                        <!-- Ngân hàng -->
                        <div class="mb-3">
                            <label for="bank-{{ $order->id }}" class="form-label">Ngân hàng <span style="color: red">*</span></label>
                            <input id="bank-{{ $order->id }}" name="return_bank" class="form-control" placeholder="Nhập ngân hàng..." value="{{ old('return_bank') }}">
                        </div>

                        <!-- STK -->
                        <div class="mb-3">
                            <label for="stk-{{ $order->id }}" class="form-label">Số tài khoản <span style="color: red">*</span></label>
                            <input id="stk-{{ $order->id }}" name="return_stk" class="form-control" placeholder="Nhập số tài khoản..." value="{{ old('return_stk') }}">
                        </div>

                        <!-- Upload ảnh minh chứng -->
                        <div class="mb-3">
                            <label for="image-{{ $order->id }}" class="form-label">Ảnh minh chứng <span style="color: red">*</span></label>
                            <input type="file" name="return_images[]" id="image-{{ $order->id }}" class="form-control"
                                accept="image/*" multiple onchange="previewReturnImages(this, '{{ $order->id }}')">

                            <!-- Khung preview ảnh -->
                            <div id="preview-images-{{ $order->id }}" class="mt-3 d-flex flex-wrap gap-3"></div>
                        </div>

                    </div>

                    <!-- Nút submit / đóng -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Gửi yêu cầu</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        /**
         * Preview ảnh khi người dùng chọn file
         * Có thể xóa ảnh đã chọn trước khi gửi
         */
        function previewReturnImages(input, orderId) {
            const previewContainer = document.getElementById('preview-images-' + orderId);
            previewContainer.innerHTML = "";

            let dt = new DataTransfer(); // Quản lý danh sách file

            Array.from(input.files).forEach((file, index) => {
                let reader = new FileReader();

                reader.onload = function (e) {
                    let wrapper = document.createElement("div");
                    wrapper.style.position = "relative";
                    wrapper.style.width = "120px";
                    wrapper.style.height = "120px";

                    // Ảnh preview
                    let img = document.createElement("img");
                    img.src = e.target.result;
                    img.classList.add("img-thumbnail");
                    img.style.width = "100%";
                    img.style.height = "100%";
                    img.style.objectFit = "cover";
                    img.style.borderRadius = "6px";

                    // Nút xóa ảnh
                    let removeBtn = document.createElement("span");
                    removeBtn.innerHTML = "&times;";
                    removeBtn.style.position = "absolute";
                    removeBtn.style.top = "4px";
                    removeBtn.style.right = "8px";
                    removeBtn.style.cursor = "pointer";
                    removeBtn.style.color = "white";
                    removeBtn.style.background = "rgba(0,0,0,0.6)";
                    removeBtn.style.borderRadius = "50%";
                    removeBtn.style.padding = "0px 6px";
                    removeBtn.style.fontSize = "16px";

                    removeBtn.onclick = function () {
                        wrapper.remove();
                        dt.items.remove(index);
                        input.files = dt.files; // Cập nhật lại input
                    };

                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    previewContainer.appendChild(wrapper);
                };

                reader.readAsDataURL(file);
                dt.items.add(file);
            });

            input.files = dt.files;
        }

        /**
         * Nếu chọn "Khác..." thì hiển thị textarea nhập lý do cụ thể
         */
        document.addEventListener("DOMContentLoaded", function () {
            const selectReason = document.getElementById("reason-{{ $order->id }}");
            const otherReasonWrapper = document.getElementById("other-reason-wrapper-{{ $order->id }}");

            selectReason.addEventListener("change", function () {
                otherReasonWrapper.style.display = (this.value === "other") ? "block" : "none";
            });
        });
    </script>
@endsection