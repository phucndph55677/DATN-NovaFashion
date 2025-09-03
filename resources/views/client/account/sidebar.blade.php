<div class="col-lg-4 col-xl-auto">
    <div class="order-sidemenu block-border">
        <div class="order-sidemenu__user">
            <div class="order-sidemenu__user-avatar">
                @php
                    // Lấy user từ biến $user nếu đã truyền vào view, fallback về Auth::user()
                    $u = isset($user) ? $user : Auth::user();

                    $defaultAvatar =
                        'https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png?20200919003010';
                    $src = $defaultAvatar;

                    if ($u && !empty($u->image)) {
                        // Chỉ hiển thị ảnh nếu file tồn tại trong disk 'public'
                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($u->image)) {
                            $src = \Illuminate\Support\Facades\Storage::url($u->image);
                            // Thêm tham số version để tránh cache ảnh cũ sau khi cập nhật
                            if (!empty($u->updated_at)) {
                                $src .= '?v=' . $u->updated_at->timestamp;
                            }
                        }
                    }
                @endphp
                <img src="{{ $src }}" alt="{{ $u->name ?? 'User' }}">
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
                    <a href="{{ route('chats.index') }}" style="{{ request()->routeIs('chats.index') ? 'color:black;' : '' }}">
                        <span class="bi bi-chat-dots"></span>Chat với người bán
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
            </ul>
        </div>
    </div>
</div>
