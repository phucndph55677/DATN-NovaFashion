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
                                        @if(in_array($order->order_status_id, [1, 2]))
                                            <form method="POST" action="{{ route('account.orders.cancel', $order->id) }}">
                                                @csrf
                                                <button type="submit"
                                                    onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này không?')"
                                                    style="border: none; background: none; color: red; text-decoration: underline;">
                                                    Hủy đơn
                                                </button>
                                            </form>

                                        {{-- Trạng thái đã hủy --}}
                                        @elseif($order->order_status_id == 8)
                                            <span style="color: red; font-weight: bold;">Đơn hàng đã hủy</span>

                                        {{-- Trạng tháiTthành công - cho phép Hoàn hàng --}}
                                        @elseif($order->order_status_id == 6)
                                            <form method="POST" action="{{ route('account.orders.return', $order->id) }}">
                                                @csrf
                                                <button type="submit"
                                                    onclick="return confirm('Bạn có chắc muốn hoàn hàng không?')"
                                                    style="border: none; background: none; color: blue; text-decoration: underline;">
                                                    Hoàn hàng
                                                </button>
                                            </form>
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
                                <a class="btn btn--large btn--view-order-detail" href="">Theo dõi đơn hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection