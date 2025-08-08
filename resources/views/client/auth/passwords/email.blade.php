@extends('client.layouts.app')

@section('title', 'Trang Chủ')

@section('content')
<main id="main" class="site-main">
    <div class="container" bis_skin_checked="1">
        <div class="auth auth-forgotpass" bis_skin_checked="1">
            <div class="auth-container" bis_skin_checked="1">
                <div class="auth-forgotpass" bis_skin_checked="1">
                    <div class="auth__login auth__block" bis_skin_checked="1">
                        <h3 class="auth__title">Bạn muốn tìm lại mật khẩu?</h3>
                        <div class="auth__login__content" bis_skin_checked="1">
                            <p class="auth__description">
                                Vui lòng nhập lại email đã đăng ký, hệ thống của chúng tôi sẽ gửi cho bạn 1 đường dẫn để
                                thay đổi mật khẩu.
                            </p>
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-warning alert-dismissible" role="alert"
                                    style="background-color: #f3e8e9; color: red; font-size: 14px; line-height: 24px">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    @foreach ($errors->all() as $error)
                                        <strong>Lỗi! </strong>{{ $error }}<br>
                                    @endforeach
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-warning alert-dismissible" role="alert"
                                    style="background-color: #f3e8e9; color: red; font-size: 14px; line-height: 24px">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    <strong>Lỗi! </strong>{{ session('error') }}<br>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('password.email') }}" autocomplete="off"
                                class="auth__form login-form">
                                @csrf
                                <div class="form-group" bis_skin_checked="1">
                                    <label for="email" class="form-label">Email</label>
                                    <input class="form-control" name="email" type="email" placeholder="Email"
                                        value="{{ old('email') }}">
                                </div>
                                <div class="form-group" bis_skin_checked="1">
                                    <label for="captcha" class="form-label">Mã xác thực</label>
                                    <div style="display:flex; align-items:center;">
                                        <input class="form-control" name="captcha" type="text" placeholder="Nhập mã"
                                            value="{{ old('captcha') }}" style="max-width:130px; margin-right:10px;">
                                        <img id="captcha-img" src="{{ captcha_src('flat') }}"
                                            style="height:38px; border-radius:6px; border:1px solid #ddd;">
                                        <button type="button" id="reload-captcha" class="btn btn-light"
                                            style="margin-left:10px; height:38px; border-radius:6px; border:1px solid #ddd;">&#8635;</button>
                                    </div>
                                    @if ($errors->has('captcha'))
                                        <span style="color:red">{{ $errors->first('captcha') }}</span>
                                    @endif
                                </div>
                                <div class="auth__form__buttons" bis_skin_checked="1">
                                    <button type="submit" class="btn btn--large"
                                        style="width: 100%;margin-top: 15px">Gửi liên kết đặt lại mật khẩu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var reloadBtn = document.getElementById('reload-captcha');
        if(reloadBtn){
            reloadBtn.onclick = function() {
                // Đổi mã bằng cách đổi src ảnh (cache buster)
                document.getElementById('captcha-img').src = '{{ captcha_src('flat') }}' + '?' + Math.random();
            };
        }
    });
</script>
@endsection
