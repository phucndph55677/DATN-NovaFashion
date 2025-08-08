@extends('client.layouts.app')

@section('title', 'Đăng Ký')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div class="order-block__title justify-content-center pt-4 pb-2"><h3 class="text-uppercase">Đăng ký</h3></div>
            <div class="auth auth-forgotpass">
                <div class="row" style="display: block">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

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
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="register-summary__overview">
                                <h4>Thông Tin Khách Hàng</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Họ tên: <span style="color: red">*</span></label>
                                        <input class="form-control" type="text" name="name" style="width: 100%" placeholder="Nhập họ tên..." value="{{ old('name') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Email: <span style="color: red">*</span></label>
                                        <input class="form-control" type="email" name="email" style="width: 100%" placeholder="Nhập Email..." value="{{ old('email') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Số điện thoại: <span style="color: red">*</span></label>
                                        <input class="form-control" type="text" name="phone" style="width: 100%" placeholder="Nhập số điện thoại..." value="{{ old('phone') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="register-summary__overview">
                                <h4>Thông Tin Mật Khẩu</h4>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Mật khẩu: <span style="color: red">*</span></label>
                                        <input class="form-control" type="password" name="password" placeholder="Nhập mật khẩu...">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Nhập lại mật khẩu: <span style="color: red">*</span></label>
                                        <input class="form-control" type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu...">
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Mời nhập các ký tự trong hình vào ô sau:<span style="color: red">*</span></label>
                                        <input class="form-control" type="text" name="captcha">
                                    </div>
                                    <p class="img_capcha"><img src="https://ivymoda.com/ajax/captcha" border="0" class="img-responsive"></p>
                                </div>
                            </div> --}}

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-check">
                                        <input class="form-check-input checkboxs" type="checkbox" name="agree" value="1" {{ old('agree') ? 'checked' : '' }}>
                                        <label style="margin-top: 0px;margin-left: 3px;" class="form-check-label">
                                            <span> Đồng ý với các <a style="color: #f31f1f" href="#">điều khoản</a> của Nova Fashion</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <button class="btn btn--large" type="submit" style="width: 100%;margin-top: 15px">Đăng ký</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
