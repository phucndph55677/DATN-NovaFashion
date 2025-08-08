@extends('client.layouts.app')

@section('title', 'Thay Đổi Mật Khẩu')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div class="auth auth-forgotpass">
                <div class="auth-container">
                    <div class="auth-forgotpass">
                        <div class="auth__login auth__block">
                            <h3 class="auth__title">Bạn muốn tìm lại mật khẩu?</h3>
                            <div class="auth__login__content">
                                <p class="auth__description">
                                    Vui lòng nhập lại email đã đăng ký, hệ thống của chúng tôi <br>
                                    sẽ gửi cho bạn 1 đường dẫn để thay đổi mật khẩu.
                                </p>         

                                <form class="auth__form" method="post" action="{{ route('password.email') }}">
                                    @csrf

                                    @if (session('success'))
                                        <div class="alert alert-success" style="background-color: #e8f5e9; color: green; font-size: 14px; line-height: 24px">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <strong>Thành công! </strong>{{ session('success') }}
                                        </div>
                                    @endif

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

                                    <div class="form-group">
                                        <input class="form-control" type="text" name="email" placeholder="Nhập Email..." value="{{ old('email') }}">
                                    </div>

                                    {{-- <div class="form-group">
                                        <input class="form-control" name="captcha" required="" type="text" placeholder="Nhập kí tự trong hình vào ô sau">
                                    </div>
                                    <p class="img_capcha"><img src="https://ivymoda.com/ajax/captcha" border="0" class="img-responsive"></p> --}}

                                    <div class="auth__form__buttons">
                                        <button type="submit" class="btn btn--large">Gửi đi</button>
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