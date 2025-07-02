<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác minh địa chỉ email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        p {
            color: #555;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            background-color: #007BFF;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Xác minh địa chỉ email của bạn</h2>

        <p>Xin chào <strong>{{ $user->name }}</strong>,</p>

        <p>
            Cảm ơn bạn đã đăng ký tài khoản tại <strong>NovaFashion</strong>.
            Vui lòng nhấn vào nút bên dưới để xác minh địa chỉ email của bạn và hoàn tất việc đăng ký.
        </p>

        <a href="{{ route('admin.verify.email', $user->verification_token) }}" class="btn">
            Xác minh email ngay
        </a>

        <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>

        Trân trọng,<br>

        <div class="footer">
            &copy; {{ date('Y') }} NovaFashion. Mọi quyền được bảo lưu.
        </div>
    </div>
</body>
</html>
