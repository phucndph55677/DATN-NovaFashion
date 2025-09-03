@extends('admin.layouts.app')

@section('title', 'Tin Nhắn')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 chat-left-wrapper">
                <div class="chat-list">
                    <div class="card">
                        <div class="card-header border-bottom-0">
                            <div class="d-flex justify-content-between align-items-center mb-md-2 mb-3">
                                <h4 class="card-title mb-0 mb-md-3 fw-bold">Tin Nhắn</h4>
                                <button class="btn btn-primary btn-sm d-block d-lg-none" data-toggel-extra="side-nav-close" data-expand-extra=".chat-left-wrapper">
                                    <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="form-group mb-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text chat-icon rounded-end-0">
                                            <svg class="svg-icon text-primary chat-icon" id="search" width="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Nhập tên" aria-label="Username">
                                    <div class="input-group-append d-none">
                                        <span class="input-group-text">
                                            <div class="spinner-border spinner-border-sm" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <nav class="tab-bottom-bordered mb-3">
                            <ul class="nav nav-tabs justify-content-around mb-0" id="nav-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home" role="tab" aria-selected="true">Tin nhắn mới</a>
                                </li>
                                {{-- <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" href="#nav-profile" role="tab" aria-selected="false" tabindex="-1">Archive</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" href="#nav-contact" role="tab" aria-selected="false" tabindex="-1">New</a>
                                </li> --}}
                            </ul>
                        </nav>

                        <div class="card-body item-list">
                            <ul id="chat-list">
                                @foreach($chats as $chat)
                                    <li class="simple-item hover" onclick="window.location='{{ route('admin.chats.index', ['chat_id' => $chat->id]) }}'" data-toggle-extra="tab" data-target-extra="#user-content-{{ $chat->id }}">
                                        <div class="img-container">
                                            <div class="avatar avatar-60">
                                                <img class="img-fluid avatar-borderd avatar-rounded" 
                                                    src="{{ $chat->user->image 
                                                            ? asset('storage/' . $chat->user->image) 
                                                            : 'https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png?20200919003010' }}"
                                                    alt="user-avatar"
                                                    onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png?20200919003010'">

                                                {{-- Trạng thái hoạt động
                                                <span class="avatar-status">
                                                    <i class="ri-checkbox-blank-circle-fill text-success">
                                                        <small>
                                                            <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="18" viewBox="0 0 24 24" fill="none">
                                                                <circle cx="12" cy="12" r="8" fill="#3cb72c"></circle>
                                                            </svg>
                                                        </small>
                                                    </i>
                                                </span> --}}
                                            </div>
                                        </div>

                                        <div class="simple-item-body">
                                            <div class="simple-item-title">
                                                <h5 class="title-text">{{ $chat->user->name }}</h5>
                                                <div class="simple-item-time">
                                                    <span>
                                                        @php
                                                            $lastMessageTime = $chat->chatDetails->last()?->created_at;
                                                        @endphp

                                                        @if($lastMessageTime)
                                                            @if($lastMessageTime->gt(now()->subDay()))
                                                                {{-- Trong 24 giờ --}}
                                                                {{ $lastMessageTime->format('H:i') }}
                                                            @else
                                                                {{-- Quá 24 giờ --}}
                                                                {{ $lastMessageTime->format('d/m/Y') }}
                                                            @endif
                                                        @else
                                                            --
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="simple-item-content">
                                                <span class="simple-item-text short">
                                                    {{ $chat->chatDetails->last()?->message ?? 'Chưa có tin nhắn' }}
                                                </span>
                                                <span class="simple-item-text short">{{ optional($chat->last_message_at)->diffForHumans() }}</span>
                                                {{-- <div class="dropdown">
                                                    <button class="btn btn-link" type="button" id="chat-dropdown-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                        </svg>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="chat-dropdown-1">
                                                        <a class="dropdown-item custom-dropdown-item" href="#">
                                                            <svg class="icon line text-primary" width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path d="M21,13v7a1,1,0,0,1-1,1H4a1,1,0,0,1-1-1V13H8a4,4,0,0,0,8,0Zm0,0L18,4M3,13,6,4" style="fill: none; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                                                </path>
                                                                <path d="M12,11V3m3,3L12,3,9,6" style="fill: none; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                                                </path>
                                                            </svg>
                                                            Move Archive
                                                        </a>
                                                        <a class="dropdown-item custom-dropdown-item" href="#">
                                                            <svg class="icon line text-primary" width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path d="M19.57,5.44a4.91,4.91,0,0,1,0,6.93L12,20,4.43,12.37A4.91,4.91,0,0,1,7.87,4a4.9,4.9,0,0,1,3.44,1.44,4.46,4.46,0,0,1,.69.88,4.46,4.46,0,0,1,.69-.88,4.83,4.83,0,0,1,6.88,0Z" style="fill: none; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                                                </path>
                                                            </svg>
                                                            Favourite
                                                        </a>
                                                        <a class="dropdown-item custom-dropdown-item" href="#">
                                                            <svg class="svg-icon text-primary" width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Delete
                                                        </a>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 chat-right-wrapper">
                @if($chatDetails)
                    @php
                        $roles = [
                            1 => 'Admin',
                            2 => 'Seller',
                            3 => 'Khách hàng',
                        ];
                    @endphp
                    <div class="chat-content animate__animated animate__fadeIn active" data-toggle-extra="tab-content" id="user-content-{{ $chatDetails->id }}">
                        <div class="card">
                            <div class="right-sidenav p-2" id="first-sidenav-1">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-sm" data-extra-dismiss="right-sidenav">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="chat-profile mx-auto">
                                    <div class="avatar avatar-70 avatar-borderd avatar-rounded mx-auto" data-toggel-extra="right-sidenav" data-target="#first-sidenav-1">
                                        <img class="img-fluid" 
                                            src="{{ $chatDetails->user->image 
                                                    ? asset('storage/' . $chatDetails->user->image) 
                                                    : 'https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png?20200919003010' }}"
                                            alt="users"
                                            onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png?20200919003010'">
                                    </div>
                                    <h4 class="mb-2">{{ $chatDetails->user->name }}</h4>
                                    <h6 class="mb-2">{{ $roles[$chatDetails->user->role->id] }}</h6>

                                </div>
                                <div class="chat-detail">
                                    <div class="row">
                                        <div class="col-6 col-md-6 title">Số Điện Thoại:</div>
                                        <div class="col-6 col-md-6 text-end">{{ $chatDetails->user->phone }}</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6 col-md-6 title">Địa Chỉ:</div>
                                        <div class="col-6 col-md-6 text-end">{{ $chatDetails->user->address }}</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6 col-md-6 title">Email:</div>
                                        <div class="col-6 col-md-6 text-end">{{ $chatDetails->user->email }}</div>
                                    </div>
                                    <hr>
                                </div>
                            </div>

                            <div class="card-header chat-content-header align-items-center">
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-primary btn-sm d-block d-lg-none me-2" data-toggel-extra="side-nav" data-expand-extra=".chat-left-wrapper">
                                        <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19.75 11.7256L4.75 11.7256" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M13.7002 5.70124L19.7502 11.7252L13.7002 17.7502" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <div class="avatar-50 avatar-borderd avatar-rounded" data-toggel-extra="right-sidenav" data-target="#first-sidenav-1">
                                        <img class="img-fluid" 
                                            src="{{ $chatDetails->user->image 
                                                    ? asset('storage/' . $chatDetails->user->image) 
                                                    : 'https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png?20200919003010' }}"
                                            alt="users"
                                            onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png?20200919003010'">
                                    </div>
                                    <div class="chat-title">
                                        <h6>{{ $chatDetails->user->name }}</h6>
                                        {{-- Trạng thái hoạt động
                                        <small>Online</small> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="card-body msg-content" id="user-{{ $chatDetails->id }}-chat-content">
                                @foreach($chatDetails->chatDetails as $detail)
                                    <div class="msg-list">
                                        {{-- Nếu sender_id là user_id thì tin nhắn của client (bên trái) --}}
                                        @if($detail->sender_id == $chatDetails->user_id)
                                            <div class="single-msg">
                                                <div class="triangle-topleft single-msg-shap"></div>
                                                <div class="single-msg-content">
                                                    <div class="msg-detail">
                                                        <span>{{ $detail->message }}</span>
                                                    </div>
                                                    <div class="msg-action">
                                                        <span>{{ $detail->created_at?->format('H:i') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                        {{-- Nếu sender_id là admin_id thì tin nhắn của admin (bên phải) --}}
                                        @elseif($detail->sender_id == $chatDetails->admin_id)
                                            <div class="single-msg user">
                                                <div class="triangle-topright single-msg-shap"></div>
                                                <div class="single-msg-content user">
                                                    <div class="msg-detail">
                                                        <span>{{ $detail->message }}</span>
                                                    </div>
                                                    <div class="msg-action">
                                                        <span>{{ $detail->created_at?->format('H:i') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="card-footer px-3 py-3">
                                <form id="chat-form">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="chat-input" placeholder="Nhập vào đây..." aria-label="Recipient's username" aria-describedby="basic-addon2-1">
                                        
                                        <div class="input-group-append">
                                            <button type="submit" class="input-group-text chat-icon rounded-start-0" id="basic-addon2-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v8"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-5 text-center">
                        <em>Chọn một cuộc trò chuyện để bắt đầu.</em>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Load Pusher trước -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script>
    // Khởi tạo chat realtime

    // Khởi tạo Pusher
    let pusher = null;
    let channel = null;

    function initializePusher() {
        try {
            pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
                cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
                encrypted: true
                // Bỏ auth vì public channel không cần authentication
            });
            console.log('Pusher initialized successfully');

            // Subscribe vào channel sau khi khởi tạo thành công
            @if($chatDetails)
            try {
                channel = pusher.subscribe('public-chat.{{ $chatDetails->id }}');

                // Nhận tin nhắn mới
                channel.bind('message.sent', function(data) {
                    console.log('Received realtime message:', data);
                    // Hiển thị tất cả tin nhắn từ realtime (cả admin và client)
                    addNewMessage(data);
                });

            } catch (error) {
                console.error('Error subscribing to channel:', error);
            }
            @endif

            return true;
        } catch (error) {
            console.error('Error initializing Pusher:', error);
            return false;
        }
    }

    // Đợi Pusher load xong
    if (typeof Pusher !== 'undefined') {
        initializePusher();
    } else {
        // Đợi thêm một chút
        setTimeout(() => {
            if (typeof Pusher !== 'undefined') {
                initializePusher();
            } else {
                console.error('Pusher script not loaded');
            }
        }, 1000);
    }

    // Gửi tin nhắn
    function sendMessage(event) {
        event.preventDefault();
        event.stopPropagation();

        const input = document.getElementById('chat-input');
        const message = input.value.trim();

        if (!message) return;

        const chatId = {{ $chatDetails->id ?? 'null' }};
        if (!chatId) return;

        // Tránh gửi tin nhắn trùng lặp
        if (input.dataset.sending === 'true') {
            console.log('Message already being sent, skipping...');
            return false;
        }

        // Đánh dấu đang gửi
        input.dataset.sending = 'true';

        // Disable input và button
        input.disabled = true;
        const submitBtn = input.parentElement.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

                    // Lấy CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/admin/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
            body: JSON.stringify({
                chat_id: chatId,
                message: message
            })
        })
            .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);

                if (data.success) {
                    // KHÔNG thêm tin nhắn mới vào chat ở đây
                    // Tin nhắn sẽ được hiển thị từ realtime event
                    // addNewMessage(data.message); // Bỏ dòng này
                    
                    // Clear input
                    input.value = '';
                    // Hiển thị thông báo thành công
                    showToast('Tin nhắn đã được gửi!', 'success');
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra khi gửi tin nhắn');
                }
            } catch (e) {
                showToast('Lỗi: Response không phải JSON - ' + text.substring(0, 200), 'error');
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            showToast('Lỗi gửi tin nhắn: ' + error.message, 'error');
        })
        .finally(() => {
            // Enable lại input và button
            input.disabled = false;
            if (submitBtn) submitBtn.disabled = false;
            // Bỏ đánh dấu đang gửi
            input.dataset.sending = 'false';
        });

        return false;
    }

    // Thêm tin nhắn mới vào chat
    function addNewMessage(messageData) {
        const chatContent = document.querySelector('.msg-content');
        if (!chatContent) return;

        const isAdminMessage = messageData.sender && messageData.sender.role_id === 1;
        const messageClass = isAdminMessage ? 'user' : '';
        const triangleClass = isAdminMessage ? 'triangle-topright' : 'triangle-topleft';

        const messageHtml = `
            <div class="msg-list">
                <div class="single-msg ${messageClass}">
                    <div class="${triangleClass} single-msg-shap"></div>
                    <div class="single-msg-content ${messageClass}">
                        <div class="msg-detail">
                            <span>${messageData.message}</span>
                        </div>
                        <div class="msg-action">
                            <span>${new Date(messageData.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        chatContent.innerHTML += messageHtml;

        // Scroll xuống cuối
        chatContent.scrollTop = chatContent.scrollHeight;
    }

    // Hiển thị thông báo toast
    function showToast(message, type = 'info') {
        // Tạo toast element nếu chưa có
        let toast = document.getElementById('chat-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'chat-toast';
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 20px;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 9999;
                transform: translateX(100%);
                transition: transform 0.3s ease;
                max-width: 300px;
            `;
            document.body.appendChild(toast);
        }

        // Set màu theo type
        const colors = {
            success: '#28a745',
            error: '#dc3545',
            info: '#17a2b8',
            warning: '#ffc107'
        };
        toast.style.backgroundColor = colors[type] || colors.info;

        // Set nội dung
        toast.textContent = message;

        // Hiển thị toast
        toast.style.transform = 'translateX(0)';

        // Tự động ẩn sau 3 giây
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
        }, 3000);
    }

    // Search chat - sẽ được setup trong DOMContentLoaded

    // Setup event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Setup search
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const chatItems = document.querySelectorAll('#chat-list .simple-item');

                chatItems.forEach(item => {
                    const userName = item.querySelector('.title-text').textContent.toLowerCase();
                    if (userName.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        // Setup chat form
        const form = document.getElementById('chat-form');
        const input = document.getElementById('chat-input');

        if (form && input) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                sendMessage(e);
                return false;
            });

            // Thêm keypress listener cho Enter
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    e.stopPropagation();
                    sendMessage(e);
                    return false;
                }
            });
        }
    });
    </script>
@endsection

