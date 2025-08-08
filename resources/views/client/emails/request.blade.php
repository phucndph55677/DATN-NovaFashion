<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu NovaFashion</title>
</head>
<body>
    <h2>Xin chào {{ $user->name }}</h2>
    <p>Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản NovaFashion.</p>
    <p>Nhấn vào nút bên dưới để đặt lại mật khẩu:</p>
    <p>
        <a href="{{ route('password.reset', ['token' => $token, 'email' => $user->email]) }}"
           style="display: inline-block; padding: 10px 18px; background: #3490dc; color: #fff; border-radius: 4px; text-decoration: none;">
            Đặt lại mật khẩu
        </a>
    </p>
    <p>Nếu nút trên không hoạt động, hãy copy đường link sau và dán vào trình duyệt:</p>
    <p style="word-break: break-all;">
        {{ route('password.reset', ['token' => $token, 'email' => $user->email]) }}
    </p>
    <hr>
    <p>Nếu bạn không thực hiện yêu cầu này, hãy bỏ qua email này.</p>
    <p>Trân trọng,<br>NovaFashion Team</p>
</body>
</html>
