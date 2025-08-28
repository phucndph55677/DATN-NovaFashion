<!DOCTYPE html>
<html lang="en" class="theme-fs-md">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NovaFashion</title>

    <link rel="shortcut icon" href="https://templates.iqonic.design/datum-dist/laravel/public/images/favicon.ico" />
    <link rel="stylesheet" href="https://templates.iqonic.design/datum-dist/laravel/public/vendor/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://templates.iqonic.design/datum-dist/laravel/public/css/datum.css">
    <link rel="stylesheet" href="https://templates.iqonic.design/datum-dist/laravel/public/css/custom.css">
    <link rel="stylesheet" href="https://templates.iqonic.design/datum-dist/laravel/public/css/customizer.css">
</head>
<body>
<div class="wrapper">
    <section class="login-content">
        <div class="container h-100">
            <div class="row align-items-center justify-content-center h-100">
                <div class="col-md-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <a href="https://templates.iqonic.design/datum-dist/laravel/public" class="auth-logo">
                                <img src="https://templates.iqonic.design/datum-dist/laravel/public/images/logo-dark.png"
                                     class="img-fluid rounded-normal" alt="logo">
                            </a>
                            <h3 class="mb-3 font-weight-bold text-center">Đăng nhập</h3>
                            <div class="mb-5">
                                <p class="line-around text-secondary mb-0">
                                    <span class="line-around-1">Đăng nhập vào tài khoản của bạn để tiếp tục</span>
                                </p>
                            </div>

                            {{-- ✅ Thông báo sau khi ĐĂNG KÝ THÀNH CÔNG --}}
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            {{-- ❌ Thông báo lỗi chung --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0 mt-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" class="mt-5" action="{{ route('admin.login') }}" data-toggle="validator">
                                @csrf

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="text-secondary form-label text-dark">Email</label>
                                            <input id="email" type="email" name="email" class="form-control mb-0"
                                                   placeholder="admin@example.com" autofocus value="{{ old('email') }}">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-2">
                                        <div class="form-group position-relative">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <label class="text-secondary form-label text-dark">Mật Khẩu</label>
                                                <label class="form-label">
                                                    <a href="{{ route('admin.request.show') }}" class="text-primary">Quên mật khẩu?</a>
                                                </label>
                                            </div>

                                            <div class="input-group">
                                                <input id="password" class="form-control mb-0" type="password" name="password"
                                                       placeholder="********" autocomplete="current-password">
                                                <span onmouseover="document.getElementById('password').type='text';"
                                                      onmouseout="document.getElementById('password').type='password';"
                                                      style="cursor: pointer; display: flex; align-items: center; padding: 0 10px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            @error('password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 d-block mt-2">Đăng Nhập</button>
                                <div class="col-lg-12 mt-3">
                                    <p class="mb-0 text-center text-dark">
                                        Bạn chưa có tài khoản?
                                        <a href="{{ route('admin.register.show') }}" class="text-primary">Đăng ký</a>
                                    </p>
                                </div>
                            </form>

                        </div> {{-- card-body --}}
                    </div> {{-- card --}}
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://templates.iqonic.design/datum-dist/laravel/public/js/libs.min.js"></script>
<script src="https://templates.iqonic.design/datum-dist/laravel/public/js/core/external.min.js"></script>
<script src="https://templates.iqonic.design/datum-dist/laravel/public/js/app.js"></script>
</body>
</html>
