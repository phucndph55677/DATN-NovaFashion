<!DOCTYPE html>
<html lang="en" class="theme-fs-md">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin login</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Favicon -->
    <link rel="shortcut icon" href="https://templates.iqonic.design/datum-dist/laravel/public/images/favicon.ico" />
    <link rel="stylesheet" href="https://templates.iqonic.design/datum-dist/laravel/public/vendor/@fortawesome/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="https://templates.iqonic.design/datum-dist/laravel/public/css/datum.css">
    <link rel="stylesheet" href="https://templates.iqonic.design/datum-dist/laravel/public/css/custom.css">
    <link rel="stylesheet" href="https://templates.iqonic.design/datum-dist/laravel/public/css/customizer.css">
</head>

<body class="">

    <!-- loader Start -->
    <div id="loading">
        <div id="loading-center"></div>
    </div>
    <!-- loader END -->

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
                                <h3 class="mb-3 font-weight-bold text-center">Đăng Nhập</h3>
                                <p class="text-center text-secondary mb-4">Đăng nhập vào tài khoản của bạn để tiếp tục</p>

                                <!-- Validation Errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <!-- Form đăng nhập -->
                                <form method="POST" action="{{ route('admin.login') }}" class="needs-validation" novalidate>
 <form method="POST" action="{{ route('admin.login') }}" class="needs-validation" novalidate>
    @csrf
    <div class="row">
        <!-- Email -->
        <div class="col-lg-12">
            <div class="form-group">
                <label class="text-secondary form-label text-dark">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control mb-0 @error('email') is-invalid @enderror" placeholder="admin@example.com" required>
                
                <!-- Hiển thị lỗi cho trường email -->
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Password -->
        <div class="col-lg-12 mt-2">
            <div class="form-group">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="text-secondary form-label text-dark">Mật Khẩu</label>
                    <a href="{{ route('admin.password.request') }}" class="text-primary">Quên mật khẩu?</a>
                </div>
                <input class="form-control mb-0 @error('password') is-invalid @enderror" type="password" placeholder="********" name="password" required autocomplete="current-password">
                
                <!-- Hiển thị lỗi cho trường password -->
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-100 d-block mt-2">Đăng nhập</button>
    <div class="col-lg-12 mt-3">
        <p class="mb-0 text-center text-dark">Đã có tài khoản? <a href="{{ route('admin.register.show') }}" class="text-primary">Đăng ký</a></p>
    </div>
</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@if (session('admin_user'))
    <!-- Nếu đã có admin trong session, chuyển hướng tới dashboard -->
    <script>window.location = "{{ route('admin.dashboard') }}";</script>
@endif
    <!-- Backend Bundle JavaScript -->
    <script src="https://templates.iqonic.design/datum-dist/laravel/public/js/libs.min.js"></script>
    <script src="https://templates.iqonic.design/datum-dist/laravel/public/js/core/external.min.js"></script>

    <!-- app JavaScript -->
    <script src="https://templates.iqonic.design/datum-dist/laravel/public/js/app.js"></script>

</body>

</html>
