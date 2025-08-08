@extends('client.layouts.app')

@section('title', 'Đổi mật khẩu')

@section('content')
    <main id="main" class="site-main">
        <div class="container pt-5 pb-5">
            <div class="auth auth-forgotpass">
                <div class="auth-container">
                    <div class="auth__login auth__block">
                        <h3 class="auth__title">Đổi mật khẩu mới</h3>
                        <div class="auth__login__content">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
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
                            <form method="POST" action="{{ route('password.update') }}" class="auth__form login-form">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="hidden" name="email" value="{{ $email }}">
                                <div class="form-group">
                                    <label for="password">Mật khẩu mới</label>
                                    <input type="password" class="form-control" name="password"
                                        placeholder="Mật khẩu mới..." required>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Nhập lại mật khẩu mới</label>
                                    <input type="password" class="form-control" name="password_confirmation"
                                        placeholder="Nhập lại mật khẩu..." required>
                                </div>
                                <div class="auth__form__buttons">
                                    <button type="submit" class="btn btn--large" style="width: 100%;margin-top: 15px">
                                        Đổi mật khẩu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
