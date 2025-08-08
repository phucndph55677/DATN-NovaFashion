@component('mail::message')
# Xác minh địa chỉ email

Xin chào {{ $user->name }},

Cảm ơn bạn đã đăng ký! Vui lòng nhấn vào nút dưới đây để xác minh địa chỉ email của bạn:

@component('mail::button', ['url' => route('verify.email', $user->verification_token)])
Xác minh Email
@endcomponent

Nếu nút không hoạt động, bạn có thể sao chép và dán liên kết sau vào trình duyệt:
{{ route('verify.email', $user->verification_token) }}

Trân trọng,  
{{ config('app.name') }}
@endcomponent