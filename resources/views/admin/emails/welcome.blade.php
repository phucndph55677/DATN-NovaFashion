<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chào mừng đến với NovaFashion</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f7f7f7; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .footer { margin-top: 30px; font-size: 13px; color: #888; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🎉 Chào mừng bạn đến với NovaFashion!</h2>
        <p>Xin chào <strong>{{ $user->name }}</strong>,</p>
        <p>Cảm ơn bạn đã xác minh tài khoản thành công. Từ bây giờ, bạn có thể đăng nhập và trải nghiệm các sản phẩm thời trang của chúng tôi.</p>
        <p>Hãy khám phá những ưu đãi mới nhất dành cho thành viên nhé!</p>

        <div class="footer">
            &copy; {{ date('Y') }} NovaFashion. Mọi quyền được bảo lưu.
        </div>
    </div>
</body>
</html>
