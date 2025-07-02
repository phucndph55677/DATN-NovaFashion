
<!DOCTYPE html>
<html lang="en" class="theme-fs-md">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="XfMiL9W2sUpDRQs9l4IgvCPkCBcXhGHp7W4AILmj">

    <title>NovaFashion</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Favicon -->
<link rel="shortcut icon" href="https://templates.iqonic.design/datum-dist/laravel/public/images/favicon.ico" />
<link rel="stylesheet" href="https://templates.iqonic.design/datum-dist/laravel/public/vendor/@fortawesome/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="https://templates.iqonic.design/datum-dist/laravel/public/css/datum.css">
<link rel="stylesheet" href="https://templates.iqonic.design/datum-dist/laravel/public/css/custom.css">
<link rel="stylesheet" href="https://templates.iqonic.design/datum-dist/laravel/public/css/customizer.css">

</head>
    <body class="  " >      
    <!-- loader Start -->
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <!-- loader END -->

    <div class="wrapper">
        <section class="login-content">
            <div class="container h-100">
                <div class="row align-items-center justify-content-center h-100">
                    <div class="col-md-12 col-lg-6">
                        <div class="card p-3">
                            <div class="card-body">
                            <a href="https://templates.iqonic.design/datum-dist/laravel/public" class="auth-logo">
                                <img src="https://templates.iqonic.design/datum-dist/laravel/public/images/logo-dark.png" class="img-fluid rounded-normal "
                                    alt="logo">
                            </a>
                            <h3 class="mb-3 fw-bold text-center">Đăng ký</h3>
                            <div class="mb-5">
                                <p class="line-around text-secondary mb-0"><span class="line-around-1">Đăng ký bằng email</span></p>
                            </div>
                            
                            <!-- Validation Errors -->

                            <!-- Form đăng ký -->
                            <form method="POST" class="mt-5" action="{{ route('admin.register') }}" data-toggle="validator">
                                @csrf

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group mb-3">
                                            <label class="text-secondary mb-2 form-label text-dark">Họ Và Tên</label>
                                            <input class="form-control" type="text" placeholder="Nhập Họ Và Tên" id="name"  name="name" value="{{ old('name') }}">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group mb-3">
                                            <label class="text-secondary mb-2 form-label text-dark">Số Điện Thoại</label>
                                            <input class="form-control" type="text" placeholder="Nhập Số Điện Thoại" id="phone"  name="phone" value="{{ old('phone') }}">
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-2">
                                        <div class="form-group mb-3">
                                            <label class="text-secondary mb-2 form-label text-dark">Email</label>
                                            <input class="form-control" type="email" placeholder="admin@example.com" id="email"  name="email" value="{{ old('email') }}">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-2">
                                        <div class="form-group">
                                            <label class="text-secondary mb-2 form-label text-dark">Mật Khẩu</label>
                                            <input class="form-control" type="password" placeholder="********" id="password" name="password" autocomplete="new-password" >
                                            @error('password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-2">
                                        <div class="form-group">
                                            <label class="text-secondary mb-2 form-label text-dark">Xác Nhận Mật Khẩu</label>
                                            <input class="form-control" type="password" placeholder="********" id="password_confirmation" name="password_confirmation" autocomplete="new-password" >
                                            @error('password_confirmation')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 d-block mt-2">Tạo tài khoản</button>

                                <div class="col-lg-12 mt-3">
                                    <p class="mb-0 text-center">Bạn có tài khoản chưa? <a href="{{ route('admin.login.show') }}"
                                        class="text-primary">Đăng nhập</a></p>
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

    <script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

</body>

</html>
