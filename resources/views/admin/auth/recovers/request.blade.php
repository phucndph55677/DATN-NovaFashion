
<!DOCTYPE html>
<html lang="en" class="theme-fs-md">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="5iKUe50erAgjxUXUyXJPhdGYqZFOez6uQTUrhMeq">

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
                        <div class="card p-5">
                            <div class="card-body">
                            <a href="https://templates.iqonic.design/datum-dist/laravel/public" class="auth-logo">
                                <img src="https://templates.iqonic.design/datum-dist/laravel/public/images/logo-dark.png" class="img-fluid rounded-normal " alt="logo">
                            </a>
                            <h3 class="mb-3 font-weight-bold text-center">Chào bạn,</h3>
                            <div class="mb-5">
                                <p class="line-around text-secondary mb-0"><span class="line-around-1"> Vui lòng nhập Email của bạn để nhận liên kết đổi mật khẩu.</span></p>
                            </div>
                            
                            <!-- Validation Errors -->
                            @if (session('success'))
                                <div class="alert alert-success">
                                    <ul class="mb-0 mt-1">
                                        <li>{{ session('success') }}</li>
                                    </ul>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0 mt-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.request') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="text-secondary form-label text-dark">Email</label>
                                            <input class="form-control" type="email" id="email" name="email" placeholder="admin@example.com" value="{{ old('email') }}">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary d-block w-100">Gửi Link Đặt Lại Mật Khẩu</button>
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
