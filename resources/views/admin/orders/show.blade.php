@extends('layouts.app')

@section('title', 'Orders')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Order Details</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold">Order Details</h4>
                    <div class="d-flex align-items-center gap-2">
                        {{-- Form cập nhật trạng thái --}}
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="input-group input-group-sm">
                                <select name="order_status_id" class="form-select form-select-sm">
                                    @foreach($order_statuses as $status)
                                        @if ($order->orderStatus->name === 'Thành công' || $order->orderStatus->name === 'Hủy đơn')
                                            {{-- Nếu trạng thái là Thành công hoặc Đã hủy, không cho phép chọn lại --}}
                                            <option value="{{ $status->id }}"
                                                {{ $order->order_status_id == $status->id ? 'selected' : '' }}
                                                {{ $order->order_status_id != $status->id ? 'disabled class=bg-light text-muted' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @else
                                            <option value="{{ $status->id }}"
                                                {{ $order->order_status_id == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>

                                <button type="submit" class="btn btn-sm btn-primary"
                                    onclick="return confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng không?')">
                                    Cập nhật
                                </button>
                            </div>
                        </form>

                        <a class="btn btn-primary btn-sm"
                            href="https://templates.iqonic.design/datum-dist/laravel/public/orderNew">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="20" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Generate Invoice
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <ul class="list-group list-group-flush rounded">
                            <li class="list-group-item p-3">
                                <h5 class="fw-bold pb-2">Order Info</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr class="white-space-no-wrap">
                                                <td class="text-muted pl-0">ID</td>
                                                <td>{{ $order->id }}</td>
                                            </tr>
                                            <tr class="white-space-no-wrap">
                                                <td class="text-muted pl-0">Date & Time</td>
                                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                            <tr class="white-space-no-wrap">
                                                <td class="text-muted pl-0">Payment Method</td>
                                                <td>{{ $order->payment }}</td>
                                            </tr>

                                            <tr class="white-space-no-wrap">
                                                <td class="text-muted pl-0">Status</td>
                                                <td><span class="badge bg-primary">{{ $order->orderStatus->name }}</span>
                                                </td>
                                            </tr>



                                        </tbody>
                                    </table>
                                </div>
                            </li>
                            <li class="list-group-item p-3">
                                <h5 class="fw-bold pb-2">Customer Details</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr class="white-space-no-wrap">
                                                <td class="text-muted pl-0">
                                                    Name
                                                </td>
                                                <td>
                                                    {{ $order->name }}
                                                </td>
                                            </tr>
                                            <tr class="white-space-no-wrap">
                                                <td class="text-muted pl-0">
                                                    Email
                                                </td>
                                                <td>
                                                    {{ $order->email }}
                                                </td>
                                            </tr>
                                            <tr class="white-space-no-wrap">
                                                <td class="text-muted pl-0">
                                                    Phone
                                                </td>
                                                <td>
                                                    {{ $order->phone }}
                                                </td>
                                            </tr>
                                            <tr class="white-space-no-wrap">
                                                <td class="text-muted pl-0">
                                                    Address
                                                </td>
                                                <td>
                                                    {{ $order->address }}
                                                </td>
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
                                <h5 class="fw-bold">Order Items</h5>
                            </li>
                            <li class="list-group-item p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr class="text-muted">
                                                <th scope="col">Product</th>
                                                <th scope="col" class="text-right">Quantity</th>
                                                <th scope="col" class="text-right">Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->orderDetails as $detail)
                                                <tr>
                                                    <td>
                                                        <div class="active-project-1 d-flex align-items-center mt-0">
                                                            <div class="h-avatar is-medium">
                                                                <img class="avatar rounded" alt="image"
                                                                    src="{{ asset('storage/' . ($detail->productVariant->product->image ?? 'default.jpg')) }}">
                                                            </div>
                                                            <div class="data-content">
                                                                <div>
                                                                    <span
                                                                        class="fw-bold">{{ $detail->productVariant->product->name ?? 'N/A' }}</span>
                                                                </div>
                                                                <p class="m-0 mt-1">
                                                                    {{ $detail->productVariant->product->description ?? '' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-right">{{ $detail->quantity }}</td>
                                                    <td class="text-right">${{ number_format($detail->price, 2) }}</td>
                                                </tr>
                                            @endforeach




                                        </tbody>
                                    </table>
                                </div>
                            </li>
                            <li class="list-group-item p-3">
                                <div class="d-flex justify-content-end">
                                    Total:
                                    <p class="ms-2 mb-0 fw-bold">
                                        ${{ number_format($total, 2) }}
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
@endsection