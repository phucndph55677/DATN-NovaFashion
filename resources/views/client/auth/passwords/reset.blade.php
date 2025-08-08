@extends('client.layouts.app')

@section('title', 'Cập Nhật Mật Khẩu')

@section('content')
    <main id="main" class="site-main">
        <div class="container">
            <div id="register" class="row title_h3">
                <div class="container">
                    <div class="auth__login auth__block py-3">
                        <h3 class="auth__title">Cập nhật mật khẩu mới</h3>
                        <div class="auth__login__content">
                            <form class="auth__form" method="post" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="hidden" name="email" value="{{ request()->email }}">

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
                                    <input class="form-control" type="password" name="password" placeholder="Mật khẩu mới">
                                </div>

                                <div class="form-group">
                                    <input class="form-control" type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu mới">
                                </div>
                                
                                <div class="auth__form__buttons">
                                    <button type="submit" class="btn btn--large">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection