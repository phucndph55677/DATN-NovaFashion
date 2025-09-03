<header id="header" class="site-header">
    <div class="container d-flex">
        <nav class="main-menu" role="navigation">
            <ul class="menu">
                @foreach ($menuCategories as $parent)
                    <li>
                        <a href="{{ route('categories.index', $parent->slug) }}">{{ $parent->name }}</a>

                        @if ($parent->children->count())
                            <ul class="sub-menu">
                                <div class="list-submenu d-flex">
                                    @foreach ($parent->children as $child)
                                        <div class="item-list-submenu">
                                            <h3>
                                                <a href="{{ route('categories.index', $child->slug) }}">{{ $child->name }}</a>
                                            </h3>

                                            @if ($child->children->count())
                                                <ul>
                                                    @foreach ($child->children as $subchild)
                                                        <li>
                                                            <a href="{{ route('categories.index', $subchild->slug) }}">{{ $subchild->name }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </ul>
                        @endif
                    </li>
                @endforeach

                <li class="menu-custom">
                    <a>Về Chúng Tôi</a>
                    <ul class="sub-menu sub-menu-collection sub-menu-about">
                        <li><a href="https://www.facebook.com/novafashion.fanpage"><i class="bi bi-facebook"></i> Facebook</a></li>
                        <li><a href="https://m.me/novafashion.fanpage"><i class="bi bi-messenger"></i> Messenger</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- LOGOO -->
        <div class="site-brand">
            <a href="http://127.0.0.1:8000/"><img src="{{ asset('storage/logo/logo_nf_hcn.png') }}" style="width: 140px; height: 80px;" /></a>
        </div>

        <div class="right-header">
            <form class="search-form" method="get" action="{{ route('account.products.search') }}" name="">
                <button class="submit" name=""><i class="icon-ic_search"></i></button>
                <input id="search-quick" type="text" name="q" value="{{ request('q') }}" placeholder="TÌM KIẾM SẢN PHẨM" autocomplete="off" minlength="1">
                <div class="quick-search">
                    <div class="item-searchs">
                        <h4>Tìm kiếm nhiều nhất</h4>
                        <div class="item-side-size">
                            <label class="item-sub-list po-relative mb-2">
                                <a href="{{ route('categories.index', 'nam') }}" class="item-sub-title">Nam</a>
                            </label>
                            <label class="item-sub-list po-relative mb-2">
                                <a href="{{ route('categories.index', 'nữ') }}" class="item-sub-title">Nữ</a>
                            </label>
                        </div>
                    </div>
                </div>
            </form>

            <div class="header-actions">
                <div class="item wallet">
                    <div class="avatar-wrapper" onclick="toggleSubAction(event)">
                        <a class="icon">
                            <i class="icon-ic_headphones"></i>
                        </a>
                    </div>
                    <div class="sub-action" style="display: none;">
                        <div class="top-action">
                            <h3>Trợ giúp</h3>
                        </div>
                        <ul>
                            <li><a href="https://www.facebook.com/novafashion.fanpage"><i class="bi bi-facebook"></i>Facebook</a></li>
                            <li><a href="https://m.me/novafashion.fanpage"><i class="bi bi-messenger"></i>Messenger</a></li>
                            <li><a href="tel:0899505715"><i class="icon-ic_phone-call"></i>0899505715</a></li>
                            <li><a href="#"><i class="icon-ic_envelope"></i>novafashion.contact.us</a></li>
                        </ul>
                    </div>
                </div>

                <div class="item wallet">
                    @if(Auth::check())
                        <div class="avatar-wrapper" onclick="toggleSubAction(event)">
                            <a class="icon">
                                <i class="icon-ic_avatar"></i>
                            </a>
                        </div>
                        <div class="sub-action" style="display: none;">
                            <div class="top-action">
                                @if(Auth::user()->role_id == 1)
                                    <a class="icon" href="{{ route('admin.dashboards.index') }}">
                                        <h3>Trang quản trị - NovaFashion</h3>
                                    </a>
                                @else
                                    <a class="icon" href="{{ route('account.info') }}">
                                        <h3>Tài khoản của tôi</h3>
                                    </a>
                                @endif
                            </div>
                            <ul>
                                <li><a href="{{ route('account.info') }}"><i class="icon-ic_avatar-1"></i>Thông tin tài khoản</a></li>
                                <li><a href="{{ route('chats.index') }}"><i class="bi bi-chat-dots"></i>Chat với người bán</a></li>
                                <li><a href="{{ route('account.orders.index') }}"><i class="icon-ic_reload"></i>Quản lý đơn hàng</a></li>
                                <li><a href="{{ route('account.reviews.index') }}"><i class="icon-ic_glasses"></i>Đánh giá sản phẩm</a></li>
                                <li><a href="{{ route('account.favorites.index') }}"><i class="icon-ic_heart"></i>Sản phẩm yêu thích</a></li>
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="icon-logout"></i> Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="avatar-wrapper">
                            <a class="icon" href="{{ route('login') }}">
                                <i class="icon-ic_avatar"></i>
                            </a>
                        </div>
                    @endif
                </div>

                 <div class="item item-cart">
                    <a class="icon" href="#">
                        <i class="icon-ic_shopping-bag"></i>
                        @if(Auth::check() && $miniCart)
                            <span class="number-cart">{{ $miniCart->cartDetails->sum('quantity') }}</span>
                        @endif
                    </a>

                    <div class="sub-action sub-action-cart">
                        <div class="top-action">
                            <h3>Giỏ hàng 
                                @auth
                                    <span class="number-cart">{{ $miniCart ? $miniCart->cartDetails->sum('quantity') : 0 }}</span>
                                @endauth
                            </h3>
                        </div>

                        <div class="main-action">
                            @guest
                                <p class="text-center p-3 text-muted">Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để xem giỏ hàng.</p>
                            @else
                                @if($miniCart && $miniCart->cartDetails->count() > 0) 
                                    @foreach($miniCart->cartDetails as $item)
                                        <div class="item-product-cart d-flex">
                                            <div class="thumb-product-cart">
                                                <img src="{{ asset('storage/' . ($item->productVariant->image ?? 'default.png')) }}">
                                            </div>
                                            <div class="info-product-cart">
                                                <h3><a href="">{{ $item->productVariant->product->name ?? 'Sản phẩm' }}</a></h3>
                                                <div class="info-properties d-flex">
                                                    <p class="properties-color">Màu sắc: <strong>{{ $item->productVariant->color->name ?? 'Màu sắc' }}</strong></p>
                                                    <p>Size: <strong style="text-transform: uppercase">{{ $item->productVariant->size->name ?? 'Size' }}</strong></p>
                                                </div>
                                                <div class="info-price-mini d-flex">
                                                    <div>
                                                        <span>
                                                            {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }} =
                                                            {{ number_format($item->price * $item->quantity, 0, ',', '.') }} VND
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-center p-3">Giỏ hàng trống.</p>
                                @endif
                            @endguest
                        </div>

                        <div class="bottom-action">
                            @auth
                                <div class="total-price">
                                    Tổng cộng: 
                                    <strong>{{ $miniCart ? number_format($miniCart->cartDetails->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') : '0' }} VND</strong>
                                </div>
                                <div class="box-action">
                                    <a href="{{ route('carts.index') }}" class="action-view-cart" style="display: block;">Xem giỏ hàng</a>
                                </div>
                            @else
                                <div class="box-action">
                                    <a href="{{ route('login') }}" class="action-login">Đăng nhập để mua sắm</a>
                                </div>
                            @endauth
                        </div>

                        <div class="action-close"><i class="icon-ic_close"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- .right-header -->
    </div>
    <!-- .container -->
</header>

<script>
    function toggleSubAction(event) {
        event.stopPropagation(); // Ngăn chặn lan ra ngoài
        const wallet = event.currentTarget.closest('.wallet');
        const subAction = wallet.querySelector('.sub-action');

        // Toggle
        if (subAction.style.display === 'block') {
            subAction.style.display = 'none';
        } else {
            // Ẩn tất cả dropdown khác (nếu có nhiều tài khoản)
            document.querySelectorAll('.wallet .sub-action').forEach(el => el.style.display = 'none');
            subAction.style.display = 'block';
        }
    }

    // Ẩn dropdown khi click ra ngoài
    document.addEventListener('click', function (e) {
        document.querySelectorAll('.wallet .sub-action').forEach(el => {
            if (!el.contains(e.target)) {
                el.style.display = 'none';
            }
        });
    });
</script>
