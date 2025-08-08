@component('mail::message')
# Chào mừng bạn đến với {{ config('app.name') }}

Xin chào {{ $user->name }},

Chúc mừng bạn đã xác minh email thành công! Bây giờ bạn có thể đăng nhập và bắt đầu sử dụng dịch vụ của chúng tôi.

@component('mail::button', ['url' => route('login')])
Đăng nhập ngay
@endcomponent

Cảm ơn bạn đã chọn chúng tôi!  
Trân trọng,  
{{ config('app.name') }}
@endcomponent