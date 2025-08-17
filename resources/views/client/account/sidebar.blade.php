<div class="col-lg-4 col-xl-auto">
    <div class="order-sidemenu block-border">
        <div class="order-sidemenu__user">
            <div class="order-sidemenu__user-avatar">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png?20200919003010'" alt="">
            </div>
            <div class="order-sidemenu__user-name">
                <p>{{ Auth::check() ? Auth::user()->name : 'Khách Hàng' }}</p>
            </div>
        </div>

        <div class="order-sidemenu__menu">
            <ul>
                <li>
                    <a href="{{ route('account.info') }}" style="{{ request()->routeIs('account.info') ? 'color:black;' : '' }}">
                        <span class="icon-ic_avatar-1"></span>Thông tin tài khoản
                    </a>
                </li>
                <li>
                    <a href="{{ route('account.orders.index') }}" style="{{ request()->routeIs('account.orders.index') ? 'color:black;' : '' }}">
                        <span class="icon-ic_reload"></span>Quản lý đơn hàng
                    </a>
                </li>
                <li>
                    <a href="{{ route('account.reviews.index') }}" style="{{ request()->routeIs('account.reviews.index') ? 'color:black;' : '' }}">
                        <span class="icon-ic_glasses"></span>Đánh giá sản phẩm
                    </a>
                </li>
                <li>
                    <a href="{{ route('account.favorites.index') }}" style="{{ request()->routeIs('account.favorites.index') ? 'color:black;' : '' }}">
                        <span class="icon-ic_heart"></span>Sản phẩm yêu thích
                    </a>
                </li>
                <li class="">
                    <a href="https://ivymoda.com/customer/login_log"><span class="icon-ic_padlock"></span>Lịch sử đăng nhập</a>
                </li>
                <li class="">
                    <a href="https://ivymoda.com/customer/question"><span class="icon-ic_headphones"></span>Hỏi đáp sản phẩm</a>
                </li>
                <li class="">
                    <a href="https://ivymoda.com/ivy-support/danh-sach"><span class="icon-ic_hand"></span>Hỗ trợ - IVY</a>
                </li>
            </ul>
        </div>
    </div>
</div>
