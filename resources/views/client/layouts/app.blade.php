<!DOCTYPE html>
<html lang="en" class="theme-fs-md">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'NovaFashion')</title>

    @include('client.partials.header')
</head>
<body class="">

    @include('client.partials.navbar')

    <div class="content-page ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    </div>

    @include('client.partials.footer')

    {{-- Modal / Overlay toàn trang --}}
    <div id="overlay"></div>
    <div class="modal_loading"><!-- Place at bottom of page --></div>

    @include('client.partials.script')

    @yield('scripts')

    {{-- Toast hiển thị thông báo chung --}}
    <div id="toast" style="
        position: fixed;
        bottom: 20px;
        right: 20px;
        min-width: 240px;
        max-width: 500px;
        background-color: #333;
        color: #fff;
        padding: 16px 20px;
        border-radius: 12px;
        display: none;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 500;
        opacity: 0;
        pointer-events: none;
        z-index: 9999;
        box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        transition: opacity 0.4s ease, transform 0.4s ease;
    ">
        <span id="toast-message"></span>
    </div>
</body>
</html>

<script>
    function showToast(message, type = 'info') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        if (toast && toastMessage) {
            toastMessage.textContent = message;
            toast.style.display = 'flex';

            let bgColor = '#333'; // default
            let duration = 3000; // default 3s

            switch (type) {
                case 'success':
                    bgColor = '#4caf50';
                    duration = 2000;
                    break;
                case 'error':
                    bgColor = '#f44336';
                    duration = 4000;
                    break;
                case 'warning':
                    bgColor = '#ff9800';
                    duration = 4000;
                    break;
            }
            toast.style.backgroundColor = bgColor;

            // Show
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            }, 10);

            // Auto hide
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 400);
            }, duration);
        }
    }
</script>
