@extends('admin.layouts.app')

@section('title', 'Đơn Hàng')

@section('content')
    <div class="container-fluid">
        @if(session('error'))
            <script>
                alert("{{ session('error') }}");
            </script>
        @endif
        
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                   <div class="d-flex align-items-center justify-content-between">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn Hàng</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Chi Tiết Đơn Hàng</li>
                            </ol>
                        </nav>
                    </div>                                   
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold">Chi Tiết Đơn Hàng</h4>
                </div>
            </div>  
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <ul class="list-group list-group-flush rounded">
                        <li class="list-group-item p-3">
                            <h5 class="fw-bold">Trạng Thái Thanh Toán</h5>
                            <form action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}" method="POST" class="d-flex align-items-center">
                                @csrf
                                @method('PUT')

                                <select name="payment_status_id" class="form-select form-select-sm me-2" style="min-width: 160px;">
                                    @foreach($payment_statuses  as $payment_status)
                                        @php
                                            $isDisabled = false;

                                            // Không cho chọn trạng thái trước đó
                                            if ($payment_status->id < $order->payment_status_id) {
                                                $isDisabled = true;
                                            }

                                            // 2. Chỉ cho phép chọn trạng thái hiện tại hoặc kế tiếp
                                            if ($payment_status->id > $order->payment_status_id + 1) {
                                                $isDisabled = true;
                                            }
                                        @endphp

                                        <option 
                                            value="{{ $payment_status->id }}" 
                                            {{ $order->payment_status_id == $payment_status->id ? 'selected' : '' }}
                                            {{ $isDisabled ? 'disabled' : '' }} >
                                            {{ $payment_status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <button type="submit" class="btn btn-outline-primary btn-sm" 
                                    onclick="return confirm('Bạn có chắc chắn muốn cập nhật Trạng thái thanh toán không?')">Cập Nhật
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <ul class="list-group list-group-flush rounded">
                        <li class="list-group-item p-3">
                            <h5 class="fw-bold">Trạng Thái Đơn Hàng</h5>
                            <form action="{{ route('admin.orders.updateOrderStatus', $order->id) }}" method="POST" class="d-flex align-items-center">
                                @csrf
                                @method('PUT')

                                <select name="order_status_id" class="form-select form-select-sm me-2" style="min-width: 160px;">
                                    @foreach($order_statuses as $order_status)
                                        @php
                                            $isDisabled = false;

                                            // Xử lý riêng với "Hủy đơn", không cho HỦY nếu không phải "Chờ xác nhận", 'Đã xác nhận'
                                            if ($order_status->name === 'Hủy đơn') {
                                                if (
                                                    $order->orderStatus?->name !== 'Chờ xác nhận' &&
                                                    $order->orderStatus?->name !== 'Đã xác nhận'
                                                ) {
                                                    $isDisabled = true;
                                                }
                                            }

                                            // Xử lý riêng với "Hoàn hàng", không cho "Hoàn hàng" nếu chưa "Thành công"
                                            elseif ($order_status->name === 'Hoàn hàng') {
                                                if ($order->orderStatus?->name !== 'Thành công') {
                                                    $isDisabled = true;
                                                }
                                            }

                                            // Các trạng thái bình thường khác
                                            else {
                                                // Không cho chọn trạng thái trước đó
                                                if ($order_status->id < $order->order_status_id) {
                                                    $isDisabled = true;
                                                }

                                                // Chỉ cho trạng thái hiện tại hoặc kế tiếp (trừ 'Hủy đơn' và 'Hoàn hàng')
                                                if ($order_status->id > $order->order_status_id + 1) {
                                                    $isDisabled = true;
                                                }
                                            }
                                        @endphp

                                        <option 
                                            value="{{ $order_status->id }}" 
                                            {{ $order->order_status_id == $order_status->id ? 'selected' : '' }} 
                                            {{ $isDisabled ? 'disabled' : '' }}
                                        >
                                            {{ $order_status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <button type="submit" class="btn btn-outline-primary btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn cập nhật Trạng thái đơn hàng không?')">Cập Nhật
                                </button>
                            </form>
                        </li>         
                    </ul>   
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <ul class="list-group list-group-flush rounded">
                        <li class="list-group-item p-3">
                            <h5 class="fw-bold pb-2">Thông Tin Đơn Hàng</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr class="white-space-no-wrap">
                                            <td class="text-muted pl-0">Mã Đơn Hàng</td>
                                            <td class="text-primary">{{ $order->order_code }}</td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="text-muted pl-0">Ngày & Giờ</td>
                                            <td>{{ $order->created_at->format('d/m/Y h:i A') }}</td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="text-muted pl-0">PT Thanh Toán</td>
                                            <td>{{ $order->payment->paymentMethod->name ?? 'Không xác định' }}</td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="text-muted pl-0">TT Thanh Toán</td>
                                            <td>
                                                <span class="badge bg-{{ $order->payment_badge_color }}">
                                                    {{ $order->paymentStatus?->name ?? 'Chưa xác định' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="text-muted pl-0">TT Đơn Hàng</td>
                                            <td>
                                                <span class="badge bg-{{ $order->order_badge_color }}">
                                                    {{ $order->orderStatus?->name ?? 'Chưa xác định' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        
                        <li class="list-group-item p-3">
                            <h5 class="fw-bold pb-2">Thông Tin Khách Hàng</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr class="white-space-no-wrap">
                                            <td class="text-muted pl-0">Tên</td>
                                            <td>{{ $order->name }}</td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="text-muted pl-0">Số Điện Thoại</td>
                                            <td>{{ $order->phone }}</td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="text-muted pl-0">Địa Chỉ</td>
                                            {{-- <td>{{ $order->address }}</td> --}}
                                            <td>{!! nl2br(e($order->address)) !!}</td>
                                        </tr>
                                        <tr class="white-space-no-wrap">
                                            <td class="text-muted pl-0">Ghi chú</td>
                                            {{-- <td>{{ $order->note }}</td> --}}
                                            <td>{!! nl2br(e($order->note)) !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <ul class="list-group list-group-flush rounded">
                        <li class="list-group-item p-3">
                            <h5 class="fw-bold">Thông Tin Sản Phẩm</h5>
                        </li>
                        <li class="list-group-item p-0">
                           <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="text-muted">
                                        <th scope="col">Sản Phẩm</th>
                                        <th scope="col" class="text-center">Số Lượng</th>
                                        <th scope="col" class="text-right">Giá</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orderDetails as $orderDetail)
                                            @php
                                                $variant = $orderDetail->productVariant;
                                                $product = $variant?->product;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                        <div class="h-avatar is-medium">
                                                            <img class="avatar rounded" alt="user-icon" src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}">
                                                        </div>
                                                        <div class="data-content">
                                                            <div>
                                                                <span class="fw-bold">
                                                                    {{ $product?->name ?? 'Không rõ tên sản phẩm' }}
                                                                </span>                           
                                                            </div>
                                                            <p class="m-0 mt-1">
                                                                {{ $variant?->color?->name ?? 'Không rõ màu' }} | 
                                                                {{ $variant?->size?->name ?? 'Không rõ size' }} | 
                                                                {{ number_format($variant?->price ?? 0, 0, ',', '.') }} VND
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    {{ $orderDetail->quantity }}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format($orderDetail->price * $orderDetail->quantity, 0, ',', '.') }} VND
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- Thêm phần tổng tiền ở cuối bảng --}}
                                        @php
                                            $shipping = 30000;
                                        @endphp

                                        <tr>
                                            <td colspan="2" class="text-end border-0">Tổng tiền hàng:</td>
                                            <td class="text-right border-0">{{ number_format($order->subtotal, 0, ',', '.') }} VND</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-end border-0">Phí vận chuyển:</td>
                                            <td class="text-right border-0">{{ number_format($shipping, 0, ',', '.') }} VND</td>
                                        </tr>
                                        <tr class="border-bottom border-dark">
                                            <td colspan="2" class="text-end">Giảm Giá:</td>
                                            <td class="text-right text-danger">-{{ number_format($order->discount, 0, ',', '.') }} VND</td>
                                        </tr>
                                        <tr class="border-top">
                                            <td colspan="2" class="text-end fw-bold text-muted">Tổng Số Tiền Phải Trả:</td>
                                            <td class="text-right fw-bold text-success">{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> 
                        </li>              
                    </ul>   
                </div>
            </div>
        </div>
    </div>
@endsection