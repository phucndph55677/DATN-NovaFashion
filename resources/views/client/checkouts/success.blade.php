@extends('client.layouts.app')

@section('title', 'Thành Công')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div class="thank-you pd-40">
                <div class="container">
                    <div class="thank-you__icon">
                        <svg width="160" height="160" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M56.7833 20.1167C62.9408 13.9592 71.2921 10.5 80 10.5C84.3117 10.5 88.5812 11.3493 92.5648 12.9993C96.5483 14.6493 100.168 17.0678 103.217 20.1167C106.266 23.1655 108.684 26.785 110.334 30.7686C111.984 34.7521 112.833 39.0216 112.833 43.3333V62.8333H47.1667V43.3333C47.1667 34.6254 50.6259 26.2741 56.7833 20.1167ZM46.1667 62.8333V43.3333C46.1667 34.3602 49.7312 25.7545 56.0762 19.4096C62.4212 13.0646 71.0268 9.5 80 9.5C84.4431 9.5 88.8426 10.3751 92.9474 12.0754C97.0523 13.7757 100.782 16.2678 103.924 19.4096C107.065 22.5513 109.558 26.281 111.258 30.3859C112.958 34.4907 113.833 38.8903 113.833 43.3333V62.8333H133.333C133.582 62.8333 133.793 63.0163 133.828 63.2626L147.162 156.596C147.182 156.739 147.139 156.885 147.044 156.994C146.949 157.104 146.812 157.167 146.667 157.167H13.3333C13.1884 157.167 13.0506 157.104 12.9556 156.994C12.8606 156.885 12.8179 156.739 12.8384 156.596L26.1717 63.2626C26.2069 63.0163 26.4178 62.8333 26.6667 62.8333H46.1667ZM113.333 63.8333H46.6667H27.1003L13.9098 156.167H146.09L132.9 63.8333H113.333Z" fill="#212121"></path>
                            <path d="M107.205 91.3663L80.4451 121.251L64.5853 106.174L62 108.893L80.6618 126.634L110 93.8694L107.205 91.3663Z" fill="black"></path>
                        </svg>
                    </div>

                    <h2>Cảm ơn đã mua hàng</h2>
                    <p>Chào {{ $order->name ?? 'Quý khách' }}, đơn hàng của bạn với mã <a id="view-detail-order" href="{{ route('account.orders.show', $order->id) }}" style="text-decoration: underline; color: green">{{ $order->order_code }}</a> đã được đặt thành công.<br>
                        Hệ thống sẽ tự động gửi Email và SMS xác nhận đơn hàng đến số điện thoại và hòm thư mà bạn đã cung cấp.<br>
                        Cảm ơn {{ $order->name ?? 'Quý khách' }} đã tin dùng sản phẩm của IVY moda!<br>
                    </p>

                    <div class="thank-you__button">
                        <a class="btn btn--large" href="http://127.0.0.1:8000/"> Tiếp tục mua sắm</a>
                        <a class="btn btn--large btn--outline" href=""> Theo dõi đơn hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection