@extends('client.layouts.app')

@section('title', 'Quản Lý Đơn Hàng')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div class="breadcrumb-products">
                <ol class="breadcrumb__list">
                    <li class="breadcrumb__item"><a class="breadcrumb__link" href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb__item"><a href="{{ route('account.orders.index') }}" class="breadcrumb__link" title="Quản Lý Đơn Hàng">Quản Lý Đơn Hàng</a></li>
                </ol>
            </div>

            <div class="order-wrapper mt-40 my-order">
                <div class="row">
                    <div class="col-lg-4 col-xl-auto">
                        @include('client.account.sidebar')
                    </div>

                    <div class="col-lg-8 col-xl col-account-content">
                        <div class="order-block__title">
                            <h2>QUẢN LÝ ĐƠN HÀNG</h2>
                            <div class="form-group">
                                <label>Trạng thái đơn hàng:</label>
                                <form method="GET" action="{{ route('account.orders.index') }}">
                                    <select name="status" class="form-control rounded" onchange="this.form.submit()">
                                        <option value="">Tất cả</option>
                                        <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Chờ xác nhận</option>
                                        <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Đã xác nhận</option>
                                        <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>Chuẩn bị hàng</option>
                                        <option value="4" {{ request('status') == 4 ? 'selected' : '' }}>Đang giao hàng</option>
                                        <option value="5" {{ request('status') == 5 ? 'selected' : '' }}>Đã giao hàng</option>
                                        <option value="6" {{ request('status') == 6 ? 'selected' : '' }}>Thành công</option>
                                        <option value="7" {{ request('status') == 7 ? 'selected' : '' }}>Hoàn hàng</option>
                                        <option value="8" {{ request('status') == 8 ? 'selected' : '' }}>Hủy đơn</option>
                                    </select>
                                </form>
                            </div>
                        </div>

                        <div class="order-block">
                            <table class="order-block__table">
                                <thead>
                                    <tr>
                                        <th>MÃ ĐƠN HÀNG</th>
                                        <th>NGÀY TẠO ĐƠN</th>
                                        <th>TRẠNG THÁI</th>
                                        <th>SẢN PHẨM</th>
                                        <th>TỔNG TIỀN</th>
                                        <th>TÌNH TRẠNG</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('account.orders.show', $order->id) }}">{{ $order->order_code }}</a>
                                            </td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i:s') }}</td>
                                            <td>{{ ($order->orderStatus->name) }}</td>
                                            <td>
                                                @foreach($order->orderDetails as $detail)
                                                    <p>
                                                        <strong>x{{ $detail->quantity }}</strong>
                                                        {{ $detail->productVariant->product->name ?? '' }}<br>
                                                        ({{ $detail->productVariant->color->name ?? '' }} - {{ $detail->productVariant->size->name ?? '' }})<br>
                                                    </p>            
                                                @endforeach
                                            </td>
                                            <td><b>{{ number_format($order->total_amount, 0, ',', '.') }} VND</b></td>
                                            <td> 
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
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <div class="product-rating__list-pagination">
                                <ul class="list-inline-pagination">
                                    {{ $orders->links() }}
                                </ul>
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