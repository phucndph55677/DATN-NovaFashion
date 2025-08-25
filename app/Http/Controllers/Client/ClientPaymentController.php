<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\CartDetail;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function momo(Request $request)
    {
        $order = session('checkout_data');
        if (!$order) {
            return redirect()->route('checkouts.index')->withErrors(['Không tìm thấy dữ liệu đặt hàng.']);
        }

        $endpoint = env('MOMO_ENDPOINT');
        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');
        $orderInfo = "Thanh toán đơn hàng" . $order['order_code'];
        $amount = (string)$order['total_amount'];
        $orderId = $order['order_code'];
        $redirectUrl = env('MOMO_REDIRECT_URL');
        $ipnUrl = env('MOMO_IPN_URL');
        $requestId = time() . "";
        $extraData = ""; // có thể truyền user_id nếu muốn

        // B1. Tạo raw signature
        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=captureWallet";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        // B2. Tạo body gửi đi
        $body = [
            'partnerCode' => $partnerCode,
            'partnerName' => "NovaFashion",
            'storeId' => "NovaFashion",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => "captureWallet",
            'signature' => $signature,
        ];

        $response = Http::withoutVerifying()->post($endpoint, $body)->json();

        if (!empty($response['payUrl'])) {
            return redirect($response['payUrl']);
        } else {
            return redirect()->route('checkouts.index')->withErrors(['Không thể kết nối Momo: ' . ($response['message'] ?? 'Lỗi không xác định')]);
        }
    }

    public function momoCallback(Request $request)
    {        
        $resultCode = $request->input('resultCode');
        $orderCode = $request->input('orderId');

        // Trường hợp thanh toán thất bại
        if ($resultCode != 0) {
            return redirect()->route('checkouts.index')->withErrors(['Thanh toán thất bại hoặc bị hủy.']);
        }

        $checkout = session('checkout_data');
        if (!$checkout || $checkout['order_code'] !== $orderCode) {
            return redirect()->route('checkouts.index')->withErrors(['Không tìm thấy dữ liệu phiên thanh toán.']);
        }

        // Tạo đơn hàng sau khi thanh toán thành công
        $order = Order::create([
            'user_id'           => $checkout['user_id'],
            'name'              => $checkout['name'],
            'phone'             => $checkout['phone'],
            'address'           => $checkout['address'],
            'note'              => $checkout['note'],
            'voucher_id'        => $checkout['voucher_id'] ?? null,
            'payment_method_id' => $checkout['payment_method_id'],
            'payment_status_id' => 2,
            'order_status_id'   => 2,
            'order_code'        => $checkout['order_code'],
            'subtotal'          => $checkout['subtotal'],
            'discount'          => $checkout['discount'],
            'shipping_fee'      => $checkout['shipping_fee'],
            'total_amount'      => $checkout['total_amount'],
        ]);

        // Voucher
        if (!empty($checkout['voucher_id']) && $checkout['discount'] > 0) {
            $voucherId = $checkout['voucher_id'];
            $userId    = $checkout['user_id'];

            // Lấy thông tin voucher
            $voucher = DB::table('vouchers')->where('id', $voucherId)->first();

            if ($voucher) {
                // Kiểm tra số lần user đã dùng
                $usedCount = DB::table('order_vouchers')
                    ->where('user_id', $userId)
                    ->where('voucher_id', $voucherId)
                    ->count();

                if ($usedCount < (int)$voucher->user_limit) {
                    // Nếu còn hạn mức, lưu vào order_vouchers
                    DB::table('order_vouchers')->insert([
                        'user_id'    => $userId,
                        'voucher_id' => $voucherId,
                        'order_id'   => $order->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Tăng total_used
                    DB::table('vouchers')->where('id', $voucherId)->increment('total_used');
                } else {
                    // Vượt hạn mức, không áp dụng voucher
                    // Có thể ghi log hoặc thông báo nếu muốn
                }
            }
        }

        // Random mã thanh toán và không trùng mã đã có
        do {
            // Kết hợp tiền tố + thời gian + số ngẫu nhiên
            $randomCodePayment = 'PAY' . now()->format('YmdHis') . random_int(100, 999);
        } while (Payment::where('payment_code', $randomCodePayment)->exists());

        // Tạo payment record cho MOMO
        Payment::create([
            'order_id'          => $order->id,
            'payment_method_id' => $checkout['payment_method_id'],
            'payment_amount'    => $checkout['total_amount'],
            'payment_code'      => $randomCodePayment,
            'status'            => 'pending', // hoặc trạng thái bạn định nghĩa
        ]);

        // Tạo chi tiết đơn hàng
        $cartDetails = CartDetail::with('productVariant')
            ->whereIn('id', $checkout['cart_detail_ids'])
            ->whereHas('cart', function ($q) use ($checkout) {
                $q->where('user_id', $checkout['user_id']);
            })
            ->get();

        foreach ($cartDetails as $item) {
            OrderDetail::create([
                'order_id'           => $order->id,
                'product_variant_id' => $item->product_variant_id,
                'price'              => $item->price,
                'quantity'           => $item->quantity,
                'total_amount'       => $item->price * $item->quantity,
                'status'             => 1,
            ]);
        }

        // Giảm số lượng sản phẩm trong kho ngay sau khi tạo order detail
        $item->productVariant->decrement('quantity', $item->quantity);

        // Xoá giỏ hàng đã đặt
        CartDetail::whereIn('id', $checkout['cart_detail_ids'])->delete();

        // Xoá session
        session()->forget('checkout_data');

        return redirect()->route('checkouts.success')->with('order_id', $order->id);
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
