<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hóa Đơn - {{ $invoice->invoice_code }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            line-height: 1.4;
        }

        .normal-weight {
            font-weight: normal; /* hoặc 400 */
        }

        /* Thiết lập cỡ chữ cho các phần tử */
        h1, h2, h3, h4, h5, h6 {
            font-size: inherit;
            margin: 0;
            padding: 0;
        }

        h3 {
            font-size: 20px;
            font-weight: bold;
        }

        h5 {
            font-size: 13px;
        }

        small {
            font-size: 11px;
            padding-left: 20px;
        }

        p {
            font-size: 13px;
            margin: 5px 0;
        }

        table {
            font-size: 13px;
        }

        th, td {
            font-size: 13px;
        }

        strong {
            font-weight: bold;
        }

        em {
            font-style: italic;
        }

        /* Header styles */
        .header-table {
            border-bottom: 1px solid #000;
        }

        .logo-cell {
            width: 35%;
            vertical-align: middle;
            text-align: center;
        }

        .title-cell {
            width: 65%;
            vertical-align: middle;
        }

        .main-title {
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .logo-img {
            height: 100px;
        }

        /* Info table styles */
        .info-table {
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        /* Product table styles */
        .product-table {
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table-header {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            height: 25px;
            vertical-align: middle;
        }

        .table-cell {
            border: 1px solid #000;
            padding: 5px;
            height: 25px !important;
            vertical-align: middle;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .price-cell {
            text-align: right;
        }

        .total-row {
            height: 25px !important;
            text-align: center;
            vertical-align: middle;
        }

        .total-label {
            float: left;
            line-height: 25px;
            vertical-align: middle;
        }

        .total-value {
            float: right;
            line-height: 25px;
            vertical-align: middle;
        }

        .total-bold {
            font-weight: bold;
        }

        .amount-in-words {
            text-align: left;
            line-height: 25px;
            vertical-align: middle;
            height: 25px !important;
        }

        /* Signature section */
        .signature-table {
            width: 100%;
        }

        .signature-cell {
            border: none;
            padding-top: 50px;
            text-align: center;
            vertical-align: top;
            height: 120px;
            width: 50%;
            position: relative;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .signature-note {
            font-style: italic;
            margin-bottom: 10px;
        }

        .signature-company {
            margin-top: 20px;
            font-weight: bold;
            color: #007bff;
        }

        /* Box chữ ký điện tử */
        .signature-box {
            margin-top: 20px;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background-color: #f8f9fa;
            display: inline-block;
            width: auto;
            min-width: 150px;
            text-align: left;
            position: relative;
        }

        .signature-logo {
            position: absolute;
            top: -15px;
            right: -15px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 2px solid #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .signature-logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            display: block;
            margin: 0;
            padding: 0;
        }

        .signature-status {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .signature-info {
            margin-bottom: 8px;
            font-size: 12px;
        }

        .signature-name {
            font-weight: bold;
            color: #28a745;
        }

        .signature-checkmark {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            color: #28a745;
            font-size: 24px;
            font-weight: bold;
        }

        /* Watermark logo styles */
        .watermark-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
            pointer-events: none;
            opacity: 0.1;
        }

        .watermark-logo {
            width: 400px;
            height: auto;
            transform: rotate(-45deg);
        }

        /* Đảm bảo watermark hiển thị trên mỗi trang khi in */
        @media print {
            .watermark-container {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                z-index: -1;
                opacity: 0.1;
            }

            .watermark-logo {
                width: 400px;
                height: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Watermark logo ở giữa trang -->
    <div class="watermark-container">
        <img src="{{ public_path('storage/logo/logo_nf_hcn.png') }}"
             alt="NovaFashion Logo"
             class="watermark-logo">
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Header -->
            <table width="100%" class="header-table">
                <tr>
                    <!-- Logo bên trái -->
                    <td width="35%" class="logo-cell">
                        <img src="{{ public_path('storage/logo/logo_nf_hcn.png') }}"
                            alt="Logo"
                            class="logo-img">
                    </td>

                    <!-- Tiêu đề bên phải -->
                    <td width="65%" class="title-cell">
                        <h3 class="main-title">
                            HÓA ĐƠN BÁN HÀNG
                        </h3>
                        <small>
                            Ngày {{ now()->format('d') }} tháng {{ now()->format('m') }} năm {{ now()->format('Y') }}
                        </small>
                    </td>
                </tr>
            </table>

            <table width="100%" style="border-bottom: 1px solid #000;">
                <p><strong>Website bán quần áo thời trang NovaFashion</strong></p>
                <h5 class="normal-weight">Số điện thoại: 0899505715</h5>
                <h5 class="normal-weight">Email: novafashion.contact.us@gmail.com</h5>
                <h5 class="normal-weight">Địa chỉ: Số nhà 51/101A, Tây Lai Xá, Kim Chung, Hoài Đức, Hà Nội</h5>
            </table>

            <table width="100%" class="info-table">
                <p><strong>Mã đơn hàng: {{ $invoice->order->order_code }}</strong></p>
                <h5 class="normal-weight">Hình thức thanh toán: {{ $invoice->order->payment->paymentMethod->name }}</h5>
                <h5 class="normal-weight">Trạng thái thanh toán: {{ $invoice->order->paymentStatus->name }}</h5>
                <h5 class="normal-weight">Họ tên người mua hàng: {{ $invoice->order->name }}</h5>
                <h5 class="normal-weight">Số điện thoại: {{ $invoice->order->phone }}</h5>
                <h5 class="normal-weight">Địa chỉ: {{ $invoice->order->address }}</h5>
                <h5 class="normal-weight">Ghi chú: {{ $invoice->order->note }}</h5>
            </table>

            <table width="100%" class="product-table">
                <thead>
                    <tr>
                        <th class="table-header">STT</th>
                        <th class="table-header">Sản phẩm</th>
                        <th class="table-header">Màu sắc</th>
                        <th class="table-header">Kích thước</th>
                        <th class="table-header">Số lượng</th>
                        <th class="table-header">Giá</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->order->orderDetails as $index => $detail)
                        @php
                            $variant = $detail->productVariant;
                            $product = $variant?->product;
                        @endphp
                        <tr>
                            <td class="table-cell">{{ $index +1 }}</td>
                            <td class="table-cell" style="text-align: left">{{ $product->name ?? '' }}</td>
                            <td class="table-cell">{{ $variant?->color->name ?? '' }}</td>
                            <td class="table-cell">{{ $variant?->size->name ?? '' }}</td>
                            <td class="table-cell">{{ $detail->quantity }}</td>
                            <td class="table-cell price-cell">{{ number_format($detail->price, 0, ',', '.') }} VND</td>
                        </tr>
                    @endforeach

                    @php
                        $shippingFee = 30000;
                    @endphp
                    <tr>
                        <td colspan="6" class="table-cell total-row">
                            <span class="total-label">Tổng tiền hàng:</span>
                            <span class="total-value">{{ number_format($invoice->order->subtotal, 0, ',', '.') }} VND</span>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="6" class="table-cell total-row">
                            <span class="total-label">Phí vận chuyển:</span>
                            <span class="total-value">{{ number_format($shippingFee, 0, ',', '.') }} VND</span>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="6" class="table-cell total-row">
                            <span class="total-label">Giảm giá:</span>
                            <span class="total-value">-{{ number_format($invoice->order->discount, 0, ',', '.') }} VND</span>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="6" class="table-cell total-row total-bold">
                            <span class="total-label">Tổng số tiền phải trả:</span>
                            <span class="total-value">{{ number_format($invoice->order->total_amount, 0, ',', '.') }} VND</span>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="6" class="table-cell amount-in-words">Số tiền viết bằng chữ: {{ $totalInWords ?? '' }} đồng</td>
                    </tr>
                </tbody>
            </table>

            <table width="100%" class="signature-table">
                <!-- Phần chữ ký -->
                <tr>
                    <td width="50%" class="signature-cell">
                        <div class="signature-title">Bên mua hàng</div>
                        <div class="signature-note">(Ký, ghi rõ họ tên)</div>
                        <div style="height: 40px;"></div>
                    </td>

                    <td width="50%" class="signature-cell">
                        <div class="signature-title">Bên bán hàng</div>
                        <div class="signature-note">(Chữ ký điện tử, Chữ ký số)</div>

                        <!-- Box chữ ký điện tử -->
                        <div class="signature-box">
                            <div class="signature-info">
                                <strong>Ký bởi:</strong> <span class="signature-name">NovaFashion</span>
                            </div>
                            <div class="signature-info">
                                <strong>Ký ngày:</strong> {{ now()->format('d/m/Y') }}
                            </div>
                            <div class="signature-logo">
                                <img src="{{ public_path('storage/logo/logo_nf_transparent.png') }}" alt="NovaFashion Logo">
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
