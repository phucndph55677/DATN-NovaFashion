<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
</head>
<body>
    <h2>Xin chào {{ $user->name }},</h2>

    <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình tại <strong>NovaFashion</strong>.</p>

    <p>Nhấn vào nút bên dưới để thiết lập lại mật khẩu:</p>

    <a href="{{ route('password.reset', ['token' => $token, 'email' => $user->email]) }}">
        Đặt lại mật khẩu
    </a>

    <p>Nếu bạn không yêu cầu thay đổi này, vui lòng bỏ qua email này.</p>

    <p>Trân trọng,<br>NovaFashion</p>
</body>
</html>