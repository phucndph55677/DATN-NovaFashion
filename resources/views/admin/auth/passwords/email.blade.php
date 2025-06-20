<!DOCTYPE html>
<html lang="en" class="theme-fs-md">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Yêu Cầu Đặt Lại Mật Khẩu</title>

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
                                <h3 class="mb-3 font-weight-bold text-center">Chào bạn,</h3>
                                <p class="text-center text-secondary mb-4">Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình. Vui lòng nhập Email của bạn để nhận liên kết đổi mật khẩu.</p>

                                <!-- Validation Errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Success Message -->
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <!-- Form yêu cầu đặt lại mật khẩu -->
                                <form method="POST" action="{{ route('admin.password.email') }}" class="needs-validation" novalidate>
                                    @csrf
                                    <div class="row">
                                        <!-- Email -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="text-secondary form-label text-dark">Email</label>
                                                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control mb-0 @error('email') is-invalid @enderror" placeholder="admin@example.com" required>
                                                @error('email')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 d-block mt-2">Gửi Link Đặt Lại Mật Khẩu</button>
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

    <!-- Backend Bundle JavaScript -->
    <script src="https://templates.iqonic.design/datum-dist/laravel/public/js/libs.min.js"></script>
    <script src="https://templates.iqonic.design/datum-dist/laravel/public/js/core/external.min.js"></script>

    <!-- app JavaScript -->
    <script src="https://templates.iqonic.design/datum-dist/laravel/public/js/app.js"></script>

</body>

</html>
