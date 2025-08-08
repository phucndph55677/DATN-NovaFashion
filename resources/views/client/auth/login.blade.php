@extends('client.layouts.app')

@section('title', 'Đăng Nhập')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div class="auth">
                <div class="auth-container">
                    <div class="auth-row">
                        <div class="auth__login auth__block">
                            <h3 class="auth__title">Bạn đã có tài khoản Nova Fashion</h3>
                            <div class="auth__login__content">
                                <p class="auth__description">Nếu bạn đã có tài khoản, hãy đăng nhập để nhận được những ưu đãi tốt hơn!</p>

                                <!-- Validation Errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger" style="background-color: #f3e8e9; color: red; font-size: 14px; line-height: 24px">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
               
                                        @foreach ($errors->all() as $error)
                                            <strong>Lỗi! </strong>{{ $error }}<br>
                                        @endforeach
                                    </div>
                                @endif

                                <form id="login-form" class="auth__form login-form" action="{{ route('login') }}" method="post">
                                    @csrf

                                    <div class="form-group">
                                        <input class="form-control" name="email" type="text" placeholder="Email" value="{{ old('email') }}">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" name="password" type="password" placeholder="Mật khẩu">
                                    </div>
                                    <div class="auth__form__options">
                                        <div class="form-checkbox">
                                            <label>
                                                <input class="checkboxs" value="1" name="customer_remember" type="checkbox">
                                                <span style="margin-left: 5px"> Ghi nhớ đăng nhập</span>
                                            </label>
                                        </div>
                                        <a class="auth__form__link" href="{{ route('password.show') }}">Quên mật khẩu?</a>
                                    </div>
                                    <div class="auth__form__buttons">
                                        <div><div class="grecaptcha-badge" data-style="bottomright" style="width: 256px; height: 60px; display: block; transition: right 0.3s; position: fixed; bottom: 14px; right: -186px; box-shadow: gray 0px 0px 5px; border-radius: 2px; overflow: hidden;"><div class="grecaptcha-logo"><iframe title="reCAPTCHA" width="256" height="60" role="presentation" name="a-ohjffnxib42s" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox allow-storage-access-by-user-activation" src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6Lcy5uEmAAAAADhosFdXQK6Em8axmw6Um7m4mnU5&amp;co=aHR0cHM6Ly9pdnltb2RhLmNvbTo0NDM.&amp;hl=vi&amp;v=07cvpCr3Xe3g2ttJNUkC6W0J&amp;size=invisible&amp;anchor-ms=20000&amp;execute-ms=15000&amp;cb=lwk1euomryf2"></iframe></div><div class="grecaptcha-error"></div><textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea></div><iframe style="display: none;"></iframe></div><button id="but_login_email" name="but_login_email" class="btn btn--large g-recaptcha" data-sitekey="6Lcy5uEmAAAAADhosFdXQK6Em8axmw6Um7m4mnU5" data-callback="onSubmitLogin">Đăng nhập</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="auth__register auth__block">
                            <h3 class="auth__title">Khách hàng mới của Nova Fashion</h3>
                            <div class="auth__login__content">
                                <p class="auth__description">
                                    Nếu bạn chưa có tài khoản trên Nova Fashion, hãy sử dụng tùy chọn này để truy cập biểu mẫu đăng ký.
                                </p>
                                <p class="auth__description">
                                    Bằng cách cung cấp cho Nova Fashion thông tin chi tiết của bạn, quá trình mua hàng trên Nova Fashion sẽ là một trải nghiệm thú vị và nhanh chóng hơn!
                                </p>
                                <div class="auth__form__buttons">
                                    <a href="{{ route('register.show') }}"> <button class="btn btn--large">Đăng ký</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection