@extends('admin.layouts.app')

@section('title', 'Hóa Đơn')

@section('content')
        <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <h4 class="fw-bold">Xem Hóa Đơn</h4>
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-between">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ms-2">Quay lại</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row pb-4 mx-0 p-0 card-header">
                            <div class="col-lg-12 mb-3">
                                <img class="avatar avatar-80 is-squared" alt="user-icon" src="{{ asset('storage/logo/logo_nf_hcn.png') }}">
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="text-start">
                                    <h5 class="fw-bold mb-2">Mã Hóa Đơn</h5>
                                    <p class="mb-md-0">{{ $invoice->invoice_code }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="float-md-center">
                                    <h5 class="fw-bold mb-2">Ngày Tạo Hóa Đơn</h5>
                                    <p class="mb-0">{{ $invoice->created_at->format('d/m/Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-4 pb-5 mx-0">
                            <div class="col-lg-6 col-md-6">
                                <div class="text-start">
                                    <h5 class="fw-bold mb-3">Thông Tin Người Nhận</h5>
                                    <p class="mb-0 mb-1">Tên: {{ !empty($invoice->order->name) ? $invoice->order->name : 'Chưa có tên' }}</p>
                                    <p class="mb-0 mb-1">Số Điện Thoại: {{ !empty($invoice->order->phone) ? $invoice->order->phone : 'Chưa có số điện thoại' }}</p>
                                    <p class="mb-0 mb-1">Địa Chỉ: {{ !empty($invoice->order->address) ? $invoice->order->address : 'Chưa có địa chỉ' }}</p>
                                    <p class="mb-0 mb-1">Ghi chú: {{ !empty($invoice->order->note) ? $invoice->order->note : 'Chưa có ghi chú' }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="float-md-center">
                                    <h5 class="fw-bold mb-3">Thông Tin Người Đặt</h5>
                                    <p class="mb-0 mb-1">Tên: {{ !empty($invoice->order->user->name) ? $invoice->order->user->name : 'Chưa có tên' }}</p>
                                    <p class="mb-0 mb-1">Số Điện Thoại: {{ !empty($invoice->order->user->phone) ? $invoice->order->user->phone : 'Chưa có tên' }}</p>
                                    <p class="mb-0 mb-1">Địa Chỉ: {{ !empty($invoice->order->user->address) ? $invoice->order->user->address : 'Chưa có tên' }}</p>
                                    <p class="mb-0 mb-1">Email: {{ !empty($invoice->order->user->email) ? $invoice->order->user->email : 'Chưa có tên' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item p-0">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                    <tr class="text-muted">
                                                        <th scope="col" class="text-center">STT</th>
                                                        <th scope="col" class="text-center">Hình Ảnh</th>
                                                        <th scope="col" class="text-center">Sản Phẩm</th>
                                                        <th scope="col" class="text-center">Màu Sắc</th>
                                                        <th scope="col" class="text-center">Kích thước</th>
                                                        <th scope="col" class="text-center">Số Lượng</th>
                                                        <th scope="col" class="text-center">Giá</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($invoice->order->orderDetails as $index => $detail)
                                                        @php
                                                            $variant = $detail->productVariant;
                                                            $product = $variant?->product;
                                                        @endphp
                                                        <tr>
                                                            <td class="text-center">{{ $index +1 }}</td>
                                                            <td class="text-center">
                                                                <div class="h-avatar is-medium">
                                                                    <img class="avatar rounded" alt="user-icon" style="width: 65px; height: 75px"  src="{{ asset('storage/' . ($variant?->image ?? 'default.png')) }}">
                                                                </div>
                                                            </td>
                                                            <td class="text-center">{{ $product->name ?? '' }}</td>
                                                            <td class="text-center">{{ $variant?->color->name ?? '' }}</td>
                                                            <td class="text-center">{{ $variant?->size->name ?? '' }}</td>
                                                            <td class="text-center">{{ $detail->quantity }}</td>
                                                            <td class="text-center">{{ number_format($detail->price, 0, ',', '.') }} VND</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </li>                

                                    <li class="list-group-item" style="margin-left:800px;">
                                        @php
                                            $shippingFee = 30000;
                                        @endphp

                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Tổng tiền hàng:</span>
                                            <span>{{ number_format($invoice->order->subtotal, 0, ',', '.') }} VND</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Phí vận chuyển:</span>
                                            <span>{{ number_format($shippingFee, 0, ',', '.') }} VND</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Giảm giá:</span>
                                            <span class="text-danger">-{{ number_format($invoice->order->discount, 0, ',', '.') }} VND</span>
                                        </div>
                                        <div class="d-flex justify-content-between fw-bold border-top pt-2 mt-2">
                                            <span class="text-muted">Tổng số tiền phải trả:</span>
                                            <span class="text-success">{{ number_format($invoice->order->total_amount, 0, ',', '.') }} VND</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-lg-12">
                                <div class="d-flex justify-content-end">
                                    <div>
                                        <a href="{{ route('admin.invoices.print', $invoice->id) }}" target="_blank" class="btn btn-primary px-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-1" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                            In Hóa Đơn
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection