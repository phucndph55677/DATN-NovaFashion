<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClientCheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        $ids = explode(',', $request->query('ids'));

        $cart = Cart::where('user_id', $userId)->first();   
        if (!$cart) {
            return redirect()->back()->withErrors(['cart' => 'Giỏ hàng trống.']);
        }
        $cartDetails = CartDetail::with('productVariant.product')
            ->where('cart_id', $cart->id)
            ->whereIn('id', $ids)
            ->orderBy('created_at', 'desc')
            ->get();

        $paymentMethods = DB::table('payment_methods')->get(); // hoặc PaymentMethod::all();

        // Trả về view checkout với các sản phẩm được chọn
        return view('client.checkouts.index', compact('cart', 'cartDetails', 'paymentMethods'));
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
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        // Validate input
        $data = $request->validate(
            [
                'name' => 'required|string|max:50',
                'phone' => 'required|regex:/^[0-9]{9,11}$/',
                'province' => 'required|string',
                'district' => 'required|string',
                'ward' => 'required|string',
                'address' => 'required|string',
                'note' => 'max:100',

                'payment_method_id' => 'required',
            ],
            [
                'name.required' => 'Vui lòng nhập Họ tên.',
                'name.max' => 'Họ tên không vượt quá 50 ký tự.',
                'phone.required' => 'Vui lòng nhập Số điện thoại.',
                'phone.regex' => 'Số điện thoại không đúng định dạng.',
                'province.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
                'district.required' => 'Vui lòng chọn Quận/Huyện.',
                'ward.required' => 'Vui lòng chọn Phường/Xã.',
                'address.required' => 'Vui lòng nhập Địa chỉ.',
                'note.max' => 'Ghi chú không vượt quá 1000 ký tự.',

                'payment_method_id.required' => 'Vui lòng chọn Phương thức thanh toán.',
            ]
        );
        
        // Ghép địa chỉ đầy đủ
        try {
            $provinceName = Http::withoutVerifying()->get("https://provinces.open-api.vn/api/p/{$data['province']}")->json('name') ?? '';
            $districtData = Http::withoutVerifying()->get("https://provinces.open-api.vn/api/d/{$data['district']}?depth=2")->json();
            $districtName = $districtData['name'] ?? '';
            $ward = collect($districtData['wards'] ?? [])->firstWhere('code', $data['ward']);
            $wardName = $ward['name'] ?? '';
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['address' => 'Không lấy được địa chỉ.']);
        }

        $fullAddress = "{$data['address']}, {$wardName}, {$districtName}, {$provinceName}";

        // Lấy thông tin cart detail được chọn
        $cartDetailIds = $request->input('cart_detail_ids', []);

        $selectedCartDetails = CartDetail::with('productVariant')
            ->whereIn('id', $cartDetailIds)
            ->whereHas('cart', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->get();

        if ($selectedCartDetails->isEmpty()) {
            return redirect()->back()->withErrors(['cart_detail_ids' => 'Không tìm thấy sản phẩm hợp lệ để đặt hàng.']);
        }

        $subtotal = $selectedCartDetails->sum(fn($item) => $item->price * $item->quantity);
        $shippingFee = 30000; // tạm thời
        $discount = 0;        // sau này xử lý voucher

        // Ưu tiên voucher từ session để đảm bảo tính nhất quán
        $appliedVoucher = session('applied_voucher');
        $voucherId      = $appliedVoucher['id'] ?? $request->input('voucher_id');

        $voucher = null;
        if (!empty($voucherId)) {
            $voucher = DB::table('vouchers')->where('id', $voucherId)->first();
        }

        if ($voucher) {
            $now = now();
            $isActive = ($voucher->status == 1 && $voucher->start_date <= $now && $voucher->end_date >= $now);
            $hasQty   = (is_null($voucher->quantity) || is_null($voucher->total_used) || $voucher->quantity > $voucher->total_used);
            $reachMin = (is_null($voucher->min_order_value) || $subtotal >= (float) $voucher->min_order_value);

            if ($isActive && $hasQty && $reachMin) {
                $sale = (float) $voucher->sale_price;
                if ($sale > 0 && $sale < 100) {
                    // giảm % theo tổng
                    $discount = $subtotal * ($sale / 100);
                } else {
                    // giảm cố định
                    $discount = min($sale, $subtotal);
                }
            }
        }

        $discount = (int) round($discount, 0);
        $totalAmount = max($subtotal - $discount, 0) + $shippingFee;

        // Random mã đơn hàng và không trùng mã đã có
        do {
            // Kết hợp tiền tố + thời gian + số ngẫu nhiên
            $randomCodeOrder = 'ORD' . now()->format('YmdHis') . random_int(100, 999);

        } while (Order::where('order_code', $randomCodeOrder)->exists());

        // Random mã thanh toán và không trùng mã đã có
        do {
            // Kết hợp tiền tố + thời gian + số ngẫu nhiên
            $randomCodePayment = 'PAY' . now()->format('YmdHis') . random_int(100, 999);
        } while (Payment::where('payment_code', $randomCodePayment)->exists());

        // Lấy phương thức thanh toán
        $paymentMethod = DB::table('payment_methods')->where('id', $data['payment_method_id'])->first();

        if ($paymentMethod->code === 'cod') {
            DB::transaction(function () use (&$order, $userId, $data, $fullAddress, $randomCodeOrder, $subtotal, $discount, $shippingFee, $totalAmount, $randomCodePayment, $selectedCartDetails, $cartDetailIds, $voucherId) {
                // Tạo đơn hàng khi COD
                $order = Order::create([
                    'user_id'           => $userId,
                    'name'              => $data['name'],
                    'phone'             => $data['phone'],
                    'address'           => $fullAddress,
                    'note'              => $data['note'] ?? null,
                    'voucher_id'        => ($discount > 0 && $voucherId) ? $voucherId : null,
                    'payment_method_id' => $data['payment_method_id'],
                    'payment_status_id' => 1,
                    'order_status_id'   => 1,
                    'order_code'        => $randomCodeOrder,
                    'subtotal'          => $subtotal,
                    'discount'          => $discount,
                    'shipping_fee'      => $shippingFee,
                    'total_amount'      => $totalAmount,
                ]);

                // Tạo payment record cho COD
                Payment::create([
                    'order_id'          => $order->id,
                    'payment_method_id' => $data['payment_method_id'],
                    'payment_amount'    => $totalAmount,
                    'payment_code'      => 'PAY' . $randomCodePayment,
                    'status'            => 'pending',
                ]);

                // Tạo chi tiết đơn hàng
                foreach ($selectedCartDetails as $cartDetail) {
                    OrderDetail::create([
                        'order_id'           => $order->id,
                        'product_variant_id' => $cartDetail->product_variant_id,
                        'price'              => $cartDetail->price,
                        'quantity'           => $cartDetail->quantity,
                        'total_amount'       => $cartDetail->price * $cartDetail->quantity,
                        'status'             => 1,
                    ]);
                }  

                // Giảm số lượng sản phẩm trong kho ngay sau khi tạo order detail
                $cartDetail->productVariant->decrement('quantity', $cartDetail->quantity);

                // Xoá cart detail đã đặt
                CartDetail::whereIn('id', $cartDetailIds)->delete();

                // Voucher
                if ($discount > 0 && $voucherId) {
                    // Lấy voucher
                    $voucher = DB::table('vouchers')->where('id', $voucherId)->first();

                    // Kiểm tra số lần user đã dùng
                    $usedCount = DB::table('order_vouchers')
                        ->where('user_id', $userId)
                        ->where('voucher_id', $voucherId)
                        ->count();

                    if ($usedCount >= (int)$voucher->user_limit) {
                        // Nếu vượt hạn mức, không áp dụng voucher
                        $discount = 0;
                        $voucherId = null;
                    } else {
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
                    }
                }
            });
            session()->forget('applied_voucher');

            return redirect()->route('checkouts.success')->with('order_id', $order->id);
        }

        // Nếu là momo/vnpay: KHÔNG tạo đơn ở đây
        // Thay vào đó, chuẩn bị dữ liệu tạm và chuyển sang Controller thanh toán
        $request->session()->put('checkout_data', [
            'user_id'           => $userId,
            'name'              => $data['name'],
            'phone'             => $data['phone'],
            'address'           => $fullAddress,
            'note'              => $data['note'] ?? null,
            'voucher_id'        => ($discount > 0 && $voucherId) ? $voucherId : null,
            'payment_method_id' => $data['payment_method_id'],
            'order_code'        => $randomCodeOrder,
            'subtotal'          => $subtotal,
            'discount'          => $discount,
            'shipping_fee'      => $shippingFee,
            'total_amount'      => $totalAmount,
            'cart_detail_ids'   => $cartDetailIds,
        ]);

        if ($paymentMethod->code === 'momo') {

            return redirect()->route('payments.momo');

        } elseif ($paymentMethod->code === 'vnpay') {

            return redirect()->route('payments.vnpay');

        } else {

            return redirect()->back()->withErrors(['payment_method_id' => 'Phương thức thanh toán không hợp lệ']);
        }
    }

    public function success(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $orderId = session('order_id');

        if (!$orderId) {
            return redirect()->route('home'); // Không có order_id → về trang chủ
        }

        $order = Order::find($orderId); // Tìm đúng đơn hàng vừa tạo theo ID

        if (!$order || $order->user_id !== Auth::id()) {
            return redirect()->route('home'); // Đảm bảo là đơn của chính user hiện tại
        }

        return view('client.checkouts.success', compact('order'));
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
        ], [
            'voucher_code.required' => 'Vui lòng nhập mã giảm giá.',
        ]);

        $voucherCode = $request->voucher_code;
        $subtotal    = (float) $request->total_amount;
        $userId      = Auth::id();

        // 1. Kiểm tra voucher tồn tại
        $voucher = Voucher::where('voucher_code', $voucherCode)->first();
        if (!$voucher) {
            return response()->json(['error' => 'Mã giảm giá không hợp lệ.'], 400);
        }

        // 2. Kiểm tra trạng thái, thời hạn
        $now = now();
        if ($voucher->status != 1 || $voucher->start_date > $now || $voucher->end_date < $now) {
            return response()->json(['error' => 'Mã giảm giá đã hết hạn.'], 400);
        }

        // 3. Kiểm tra số lượng còn
        if (!is_null($voucher->quantity) && !is_null($voucher->total_used) && $voucher->quantity <= $voucher->total_used) {
            return response()->json(['error' => 'Mã giảm giá đã được sử dụng hết.'], 400);
        }

        // 4. Kiểm tra giá trị đơn hàng tối thiểu
        if (!is_null($voucher->min_order_value) && $subtotal < (float) $voucher->min_order_value) {
            return response()->json(['error' => 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã.'], 400);
        }

        // 5. Hạn mức mỗi user
        if (!is_null($voucher->user_limit) && $userId && Schema::hasTable('order_vouchers')) {
            $usedCount = DB::table('order_vouchers')
                ->where('user_id', $userId)
                ->where('voucher_id', $voucher->id)
                ->count();

            if ($usedCount >= (int) $voucher->user_limit) {
                return response()->json(['error' => 'Bạn đã sử dụng mã này tối đa số lần cho phép.'], 400);
            }
        }

        // 6. Tính toán giảm giá (đồng bộ công thức)
        $sale     = (float) $voucher->sale_price;
        $discount = 0;

        if ($sale > 0 && $sale < 100) {
            // giảm % theo tổng
            $discount = $subtotal * ($sale / 100);
        } else {
            // giảm cố định
            $discount = min($sale, $subtotal);
        }
        $finalSubtotal = max($subtotal - $discount, 0);

        // Lưu session (chỉ lưu id/code, KHÔNG lưu số tiền để tránh lệch khi giỏ thay đổi)
        session()->put('applied_voucher', [
            'id'   => $voucher->id,
            'code' => $voucher->voucher_code,
        ]);

        return response()->json([
            'success'       => true,
            'voucher'       => ['id' => $voucher->id, 'code' => $voucher->voucher_code],
            'discount'     => (int) round($discount, 0),
            'final_amount' => (int) round($finalSubtotal, 0),
            'message'       => 'Áp dụng mã giảm giá thành công!',
        ]);
    }

    // Xóa voucher
    public function clearVoucher(Request $request)
    {
        $request->session()->forget('applied_voucher');
        return response()->json(['success' => true]);
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
