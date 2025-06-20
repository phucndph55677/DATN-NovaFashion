<!DOCTYPE html>
<html lang="en" class="theme-fs-md">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Đặt Lại Mật Khẩu</title>

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
                                <h3 class="mb-3 font-weight-bold text-center">Đặt Lại Mật Khẩu</h3>
                                <p class="text-center text-secondary mb-4">Vui lòng nhập mật khẩu mới để thay đổi mật khẩu của bạn.</p>

                                <!-- Validation Errors -->
                                {{-- @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif --}}
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
                                <form method="POST" action="{{ route('admin.password.update') }}" class="needs-validation" novalidate>
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <!-- Mật khẩu mới -->
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="text-secondary form-label text-dark">Mật khẩu mới</label>
                                            <input type="password" id="password" name="password" class="form-control mb-0 @error('password') is-invalid @enderror" placeholder="Mật khẩu mới" required>
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Xác nhận mật khẩu -->
                                    <div class="col-lg-12 mt-2">
                                        <div class="form-group">
                                            <label class="text-secondary form-label text-dark">Xác nhận mật khẩu</label>
                                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control mb-0 @error('password_confirmation') is-invalid @enderror" placeholder="Xác nhận mật khẩu" required>
                                            @error('password_confirmation')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 d-block mt-2">Đặt lại mật khẩu</button>
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
