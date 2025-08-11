@extends('client.layouts.app')

@section('title', 'Thông Tin Tài Khoản')

@section('content')
    <main id="main" class="site-main">
        <div class="container">  
            <div class="breadcrumb-products">
                <ol class="breadcrumb__list">
                    <li class="breadcrumb__item"><a class="breadcrumb__link" href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb__item"><a href="{{ route('account.info') }}" class="breadcrumb__link" title="Tài khoản của tôi">Tài khoản của tôi</a></li>
                </ol>
            </div>

            <div class="order-wrapper mt-40 my-account">
                <div class="row">
                    <div class="col-lg-4 col-xl-auto">
                        @include('client.account.sidebar')
                    </div>

                    <div class="col-lg-8 col-xl">
                        <div class="order-block__title">
                            <h2>TÀI KHOẢN CỦA TÔI</h2>
                        </div>

                        <div class="order-block my-account-wrapper row">
                            <div class="col-md-7">
                                <form enctype="application/x-www-form-urlencoded" name="frm_register" method="post" action="">
                                    <div class="row form-group">
                                        <div class="col col-label">
                                            <label>Họ tên</label>
                                        </div>
                                        <div class="col col-input">
                                            <input class="form-control" type="text" value="{{ $user->name }}">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-label">
                                            <label>Số điện thoại</label>
                                        </div>
                                        <div class="col col-input has-button">
                                            <input class="form-control" type="text" value="{{ $user->phone }}">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-label">
                                            <label>Email</label>
                                        </div>
                                        <div class="col col-input has-button">
                                            <input class="form-control" type="text" value="{{ $user->email }}">
                                        </div>
                                    </div>

                                    <div class="row form-radio-checkbox form-group">
                                        <div class="col col-label"></div>
                                        <div class="col-12 col-input form-buttons">
                                            <button class="btn btn--large">Cập nhật</button>
                                            <a data-fancybox="" data-src="#fancybox-popup" class="btn btn--large btn--outline">Đổi mật khẩu</a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-5">
                                <div class="form-upload">
                                    <div class="form-upload__input">
                                        <div class="form-upload__img">
                                            <input id="user_avatar_input" type="file" name="user_avatar"/>
                                            <img id="user_avatar_preview" src="https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png?20200919003010'" alt=""/>
                                            <div class="form-upload__icon">
                                                <img src="data:image/png;base64,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" alt=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-upload__note">
                                        <p><span>Image</span></p>
                                    </div>
                                </div>                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="add-address add-change-pass" id="fancybox-popup">
                    <h3>ĐỔI MẬT KHẨU</h3>
                    <div class="col-md-12 form-group">
                        <label>Mật khẩu hiện tại</label>
                        <input class="form-control" type="password" name="customer_pass_old" id="customer_pass_old">
                    </div>

                    <div class="col-md-12 form-group">
                        <label>Mật khẩu mới</label>
                        <input class="form-control" type="password" name="customer_pass_new1" id="customer_pass_new1" value="">
                    </div>

                    <div class="col-md-12 form-group">
                        <label>Nhập lại Mật khẩu mới</label>
                        <input class="form-control" type="password" name="customer_pass_new2" id="customer_pass_new2" value="">
                    </div>

                    <div class="wallet-deposit-form-wrapper" style="width: 100%">
                        <div class="col-md-12 form-button">
                            <a style="max-width: 100%; font-weight: 600;" class="btn btn--large" id="change_pass">Cập nhật</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection