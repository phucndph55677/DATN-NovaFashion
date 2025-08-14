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
@endsection