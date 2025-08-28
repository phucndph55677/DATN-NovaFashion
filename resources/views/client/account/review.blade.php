@extends('client.layouts.app')

@section('title', 'Đánh Giá Sản Phẩm')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div class="breadcrumb-products">
                <ol class="breadcrumb__list">
                    <li class="breadcrumb__item"><a class="breadcrumb__link" href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb__item"><a href="{{ route('account.orders.index') }}" class="breadcrumb__link" title="Đánh Giá Sản Phẩm">Đánh Giá Sản Phẩm</a></li>
                </ol>
            </div>

            <div class="order-wrapper mt-40 my-order">
                <div class="row">
                    <div class="col-lg-4 col-xl-auto">
                        @include('client.account.sidebar')
                    </div>

                    <div class="col-lg-8 col-xl col-account-content">
                        <div class="order-block__title">
                            <h2>ĐÁNH GIÁ SẢN PHẨM</h2>
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
                                        <th>ĐÁNH GIÁ</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @php
                                        // Lọc ra những đơn hàng có sản phẩm chưa đánh giá
                                        $pendingOrders = collect();
                                        foreach($reviews as $order) {
                                            $pendingDetails = $order->orderDetails->filter(function($detail) use ($reviewedProducts, $order) {
                                                return !isset($reviewedProducts[$order->id.'-'.$detail->productVariant->product->id]);
                                            });
                                            if($pendingDetails->count() > 0) {
                                                $pendingOrders->push($order);
                                            }
                                        }
                                    @endphp

                                    @forelse ($pendingOrders as $order)
                                        @php
                                            // Lọc ra các sản phẩm chưa được đánh giá
                                            $pendingDetails = $order->orderDetails->filter(function($detail) use ($reviewedProducts, $order) {
                                                return !isset($reviewedProducts[$order->id.'-'.$detail->productVariant->product->id]);
                                            });
                                        @endphp

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
                                                <!-- Nút mở modal -->
                                                <button type="button" style="border: none; background: none; color: green; font-weight: bold; text-decoration: underline;" 
                                                    data-bs-toggle="modal" data-bs-target="#reviewModal{{ $order->id }}">
                                                    Đánh giá
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="reviewModal{{ $order->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $order->id }}" aria-hidden="true" data-bs-scrollbar="false">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content" style="text-align: left">
                                                            <form action="" method="POST">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Đánh giá đơn hàng - {{ $order->order_code }}</h4>
                                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @foreach($pendingDetails as $detail)
                                                                        <div class="mb-3">
                                                                            <p>
                                                                                <strong>x{{ $detail->quantity }}</strong>
                                                                                {{ $detail->productVariant->product->name ?? '' }}
                                                                                ({{ $detail->productVariant->color->name ?? '' }} - {{ $detail->productVariant->size->name ?? '' }})<br>
                                                                            </p>
                                                                            
                                                                            <!-- Ẩn order_detail_id -->
                                                                            <input type="hidden" name="order_detail_id[]" value="{{ $detail->id }}">

                                                                            <!-- Chọn số sao -->
                                                                            <div class="mb-2">
                                                                                <select name="rating[]" class="form-select">
                                                                                    <option value="">Chọn số sao</option>
                                                                                    @for($rating=1; $rating<=5; $rating++)
                                                                                        <option value="{{ $rating }}">{{ $rating }} sao</option>
                                                                                    @endfor
                                                                                </select>
                                                                            </div>

                                                                            <!-- Nội dung review -->
                                                                            <textarea name="content[]" class="form-control" rows="2" placeholder="Viết đánh giá..."></textarea>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-success">Gửi đánh giá</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                             </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center" style="text-decoration: none; border-bottom: none; color: #6c757d; font-size: 18px; padding: 40px 0;">Bạn chưa có đơn hàng nào để đánh giá.</td>
                                        </tr>
                                    @endforelse                                        
                                </tbody>
                            </table>
                            
                            <div class="product-rating__list-pagination">
                                <ul class="list-inline-pagination">
                                    {{ $reviews->links() }}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
