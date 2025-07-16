@extends('client.layouts.app')

@section('title', 'Trang Chủ')

@section('content')
<main id="main" class="site-main">
    <div class="container" bis_skin_checked="1">
        <script src="https://www.google.com/recaptcha/api.js" async="" defer=""></script>
        <div class="auth" bis_skin_checked="1">
            <div class="auth-container" bis_skin_checked="1">
                <div class="auth-row" bis_skin_checked="1">
                    <div class="auth__login auth__block" bis_skin_checked="1">
                        <h3 class="auth__title">Bạn đã có tài khoản IVY</h3>
                        <div class="auth__login__content" bis_skin_checked="1">
                            <p class="auth__description">
                                Nếu bạn đã có tài khoản, hãy đăng nhập để tích lũy điểm thành viên và nhận được những ưu đãi tốt hơn!
                            </p>
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any() || session('error'))
                                <div class="alert alert-warning alert-dismissible" role="alert"
                                    style="background-color: #f3e8e9; color: red; font-size: 14px; line-height: 24px" bis_skin_checked="1">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    @if ($errors->has('email') && $errors->first('email') == 'Vui lòng nhập đầy đủ thông tin đăng nhập')
                                        <strong>Lỗi! </strong>Vui lòng nhập đầy đủ thông tin đăng nhập<br>
                                    @endif
                                    @if ($errors->has('email') && $errors->first('email') == 'Địa chỉ email không đúng định dạng.')
                                        <strong>Lỗi! </strong>Địa chỉ email không đúng định dạng<br>
                                    @endif
                                    @if (session('error') == 'Tên đăng nhập hoặc mật khẩu không hợp lệ.' || $errors->has('error'))
                                        <strong>Lỗi! </strong>Tên đăng nhập hoặc mật khẩu không hợp lệ<br>
                                    @endif
                                </div>
                            @endif
                            <form id="login-form" class="auth__form login-form" method="POST" action="{{ route('login.post') }}" autocomplete="off">
                                @csrf
                                <div class="form-group" bis_skin_checked="1">
                                    <label for="email" class="form-label">Email</label>
                                    <input class="form-control" name="email" type="text" placeholder="Email" value="{{ old('email') }}" >
                                </div>
                                <div class="form-group" bis_skin_checked="1">
                                    <label for="password" class="form-label">Mật khẩu</label>
                                    <input class="form-control" name="password" type="password" placeholder="Mật khẩu" >
                                </div>
                                <div class="auth__form__options" bis_skin_checked="1">
                                    <div class="form-checkbox" bis_skin_checked="1">
                                        <label>
                                            <input class="checkboxs" value="1" name="customer_remember" type="checkbox">
                                            <span style="margin-left: 5px">Ghi nhớ đăng nhập</span>
                                        </label>
                                    </div>
                                    <a class="auth__form__link" href="">Quên mật khẩu?</a>
                                </div>
                                <div class="auth__form__options" bis_skin_checked="1">
                                    <a class="auth__form__link login-with-qr" href="javascript:void(0)">Đăng nhập bằng mã QR</a>
                                </div>
                                <div class="auth__form__buttons" bis_skin_checked="1">
                                    <div bis_skin_checked="1">
                                        <div class="grecaptcha-badge" data-style="bottomright" bis_skin_checked="1" style="width: 256px; height: 60px; display: block; transition: right 0.3s; position: fixed; bottom: 14px; right: -186px; box-shadow: gray 0px 0px 5px; border-radius: 2px; overflow: hidden;">
                                            <div class="grecaptcha-logo" bis_skin_checked="1">
                                                <iframe title="reCAPTCHA" width="256" height="60" role="presentation" name="a-53b00y2vdeih" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox allow-storage-access-by-user-activation" src="https://www.google.com/recaptcha/api2/anchor?ar=1&k=6Lcy5uEmAAAAADhosFdXQK6Em8axmw6Um7m4mnU5&co=aHR0cHM6Ly9pdnltb2RhLmNvbTo0NDM.&hl=vi&v=_cn5mBoBXIA0_T7xBjxkUqUA&size=invisible&cb=sdp1paplmjz" bis_size="{'x':1450,'y':621,'w':256,'h':60,'abs_x':1450,'abs_y':621}"></iframe>
                                            </div>
                                            <div class="grecaptcha-error" bis_skin_checked="1"></div>
                                            <textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea>
                                        </div>
                                        <iframe style="display: none;"></iframe>
                                    </div>
                                    <button type="submit" class="btn btn--large" fdprocessedid="2fj6v9">Đăng nhập</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="auth__register auth__block" bis_skin_checked="1">
                        <h3 class="auth__title">Khách hàng mới của IVY moda</h3>
                        <div class="auth__login__content" bis_skin_checked="1">
                            <p class="auth__description">
                                Nếu bạn chưa có tài khoản trên ivymoda.com, hãy sử dụng tùy chọn này để truy cập biểu mẫu đăng ký.
                            </p>
                            <p class="auth__description">
                                Bằng cách cung cấp cho IVY moda thông tin chi tiết của bạn, quá trình mua hàng trên ivymoda.com sẽ là một trải nghiệm thú vị và nhanh chóng hơn!
                            </p>
                            <div class="auth__form__buttons" bis_skin_checked="1">
                                <a href="">
                                    <button class="btn btn--large" fdprocessedid="8r3mcc">Đăng ký</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId: '1311336238962080',
            xfbml: true,
            version: 'v2.8'
        });
        FB.AppEvents.logPageView();
    };

    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<script src="https://apis.google.com/js/platform.js" async="" defer="" gapi_processed="true"></script>
<script src="https://pubcdn.ivymoda.com/ivy2/js/qrcode.js"></script>
<script src="https://pubcdn.ivymoda.com/ivy2/js/login.js" type="text/javascript"></script>