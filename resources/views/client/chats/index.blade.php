@extends('client.layouts.app')

@section('title', 'Trò chuyện')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div class="breadcrumb-products">
                <ol class="breadcrumb__list">
                    <li class="breadcrumb__item"><a class="breadcrumb__link" href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb__item"><a href="{{ route('chats.index') }}" class="breadcrumb__link" title="Trò chuyện">Trò chuyện</a></li>
                </ol>
            </div>

            <div class="order-wrapper mt-40 my-account">
                <div class="row">
                    <div class="col-lg-4 col-xl-auto">
                        @include('client.account.sidebar')
                    </div>

                    <div class="col-lg-8 col-xl">
                        <div class="order-block__title">
                            <h2>CHAT VỚI ADMIN</h2>
                        </div>

                        <div class="sub-main-prod">
                            <div class="list-products list-products-cat d-flex">
                                <div class="chat-runtime">
                                    <div class="chat-card chat-main">
                                        <div class="chat-header">
                                            <div class="chat-partner">
                                                <span class="chat-avatar"></span>
                                                <div>
                                                    <div class="chat-title">Admin</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="chat-toast" class="chat-toast" style="display:none;"></div>
                                        <div class="chat-messages">
                                            @foreach($chatDetails->chatDetails as $detail)
                                                {{-- <div class="message message-in">
                                                    Chào bạn, mình là Admin NovaFashion. Bạn cần hỗ trợ gì không?
                                                    <span class="message-time">10:21</span>
                                                </div> --}}
                                                @if($detail->sender_id == $chatDetails->user_id)
                                                    <div class="message message-out">
                                                        {{ $detail->message }}
                                                        <span class="message-time">{{ $detail->created_at?->format('H:i') }}</span>
                                                    </div>

                                                @elseif($detail->sender_id == $chatDetails->admin_id)
                                                    <div class="message message-in">
                                                        {{ $detail->message }}
                                                        <span class="message-time">{{ $detail->created_at?->format('H:i') }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="chat-input">
                                            <form id="chat-form">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="chat-input" placeholder="Nhập tin nhắn..." aria-label="Recipient's username" aria-describedby="basic-addon2-1">
                                                    
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <style>
        .chat-runtime { width: 100%; }
        .chat-card { background: #fff; border: 1px solid #eee; border-radius: 8px; overflow: hidden; }
        .chat-main { display: grid; grid-template-rows: auto 1fr auto; height: 427px; }
        .chat-header { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between; }
        .chat-partner { display: flex; align-items: center; gap: 12px; }
        .chat-avatar { width: 40px; height: 40px; border-radius: 50%; background: #f3f3f3; display: inline-block; }
        .chat-title { font-weight: 600; font-size: 16px; color: #111; }
        .chat-status { font-size: 13px; color: #666; }
        .chat-messages { padding: 16px; overflow: auto; background: #fafafa; display: flex; flex-direction: column; }
        .message { display: inline-block; max-width: 70%; margin-bottom: 10px; padding: 8px 10px; border-radius: 14px; font-size: 14px; line-height: 1.35; align-self: flex-start; }
        .message-in { background: #fff; border: 1px solid #eee; color: #111; border-top-left-radius: 4px; }
        .message-out { background: #111; color: #fff; border-top-right-radius: 4px; align-self: flex-end; }
        .message-time { display: inline-block; margin-top: 0; margin-left: 6px; font-size: 12px; opacity: 0.85; vertical-align: baseline; }
        .message-in .message-time { color: #666; }
        .message-out .message-time { color: rgba(255,255,255,0.9); }
        .chat-input { border-top: 1px solid #f0f0f0; padding: 12px; display: grid; grid-template-columns: 1fr auto; gap: 8px; background: #fff; }
        .chat-input .input { border: 1px solid #e5e5e5; border-radius: 8px; padding: 10px 12px; font-size: 14px; }
        .chat-actions { display: flex; align-items: center; gap: 8px; }
        .btn-icon { height: 40px; padding: 0 14px; border-radius: 8px; border: 1px solid #e5e5e5; background: #fff; color: #111; }
        .btn-primary { background: #111; color: #fff; border-color: #111; }
        .chat-actions .chat-icon { cursor: pointer; transition: transform .08s ease, background-color .15s ease; }
        .chat-actions .chat-icon:hover { background: #f7f7f7; }
        .chat-actions .chat-icon:active { transform: scale(0.96); }
        .chat-toast { position: absolute; top: 8px; right: 8px; z-index: 10; padding: 8px 12px; border-radius: 8px; font-size: 13px; box-shadow: 0 4px 14px rgba(0,0,0,0.08); }
        .chat-toast.success { background: #e6ffed; color: #065f46; border: 1px solid #a7f3d0; }
        .chat-toast.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        @media (max-width: 991.98px) {
            .chat-main { height: 133px; }
        }
    </style>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        (function() {
            const chatId = {{ $chatDetails->id ?? 'null' }};
            const messagesEl = document.querySelector('.chat-messages');
            const inputEl = document.getElementById('chat-input');
            const formEl = document.getElementById('chat-form');
            const sentIds = new Set();
            const toastEl = document.getElementById('chat-toast');

            function formatTime(d) {
                return new Date(d).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }

            function appendMessage(text, time, isOut) {
                if (!messagesEl) return;
                const div = document.createElement('div');
                div.className = 'message ' + (isOut ? 'message-out' : 'message-in');
                div.innerHTML = `${text} <span class=\"message-time\">${time}</span>`;
                messagesEl.appendChild(div);
                messagesEl.scrollTop = messagesEl.scrollHeight;
            }

            function scrollToBottom() {
                if (!messagesEl) return;
                messagesEl.scrollTop = messagesEl.scrollHeight;
            }

            let toastTimer = null;
            function showToast(msg, type = 'success') {
                if (!toastEl) return;
                toastEl.textContent = msg;
                toastEl.className = 'chat-toast ' + type;
                toastEl.style.display = 'block';
                clearTimeout(toastTimer);
                toastTimer = setTimeout(() => {
                    toastEl.style.display = 'none';
                }, 2000);
            }

            async function sendMessage(e) {
                if (e) e.preventDefault();
                const text = (inputEl?.value || '').trim();
                if (!text || !chatId) return false;
                try {
                    const res = await fetch(`{{ route('chats.store') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': `{{ csrf_token() }}`,
                        },
                        body: JSON.stringify({ chat_id: chatId, message: text })
                    });
                    const data = await res.json();
                    if (res.ok && data && data.success) {
                        if (data.message && data.message.id) {
                            sentIds.add(String(data.message.id));
                        }
                        appendMessage(text, formatTime(new Date()), true);
                        inputEl.value = '';
                        showToast('Đã gửi tin nhắn', 'success');
                    } else {
                        showToast(data?.error || 'Gửi tin nhắn thất bại', 'error');
                    }
                } catch (err) {
                    showToast('Lỗi mạng, vui lòng thử lại', 'error');
                }
                return false;
            }

            if (formEl) {
                formEl.addEventListener('submit', sendMessage);
            }
            if (inputEl) {
                inputEl.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        sendMessage(e);
                    }
                });
            }

            // Realtime subscribe giống admin (public channel)
            if (typeof Pusher !== 'undefined' && chatId) {
                const pusher = new Pusher(`{{ config('broadcasting.connections.pusher.key') }}`, {
                    cluster: `{{ config('broadcasting.connections.pusher.options.cluster') }}`,
                    encrypted: true
                });
                const channel = pusher.subscribe('public-chat.' + chatId);
                channel.bind('message.sent', function(data) {
                    const idStr = data && data.id ? String(data.id) : '';
                    if (idStr && sentIds.has(idStr)) {
                        sentIds.delete(idStr);
                        return;
                    }
                    if (Number(`{{ auth()->id() }}`) === Number(data?.sender_id)) {
                        return;
                    }
                    appendMessage(data?.message || '', formatTime(data?.created_at || new Date()), false);
                });
            }

            // Auto scroll to latest on initial load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => setTimeout(scrollToBottom, 0));
            } else {
                setTimeout(scrollToBottom, 0);
            }
        })();
    </script>
@endsection
