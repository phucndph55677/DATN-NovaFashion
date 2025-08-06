<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $provinceName = Http::withoutVerifying()->get("https://provinces.open-api.vn/api/p/{$data['province']}")->json('name');
        $districtData = Http::withoutVerifying()->get("https://provinces.open-api.vn/api/d/{$data['district']}?depth=2")->json();
        $districtName = $districtData['name'] ?? '';
        $wardList = $districtData['wards'] ?? [];
        $ward = collect($wardList)->firstWhere('code', $data['ward']);
        $wardName = $ward['name'] ?? '';

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
        $shippingFee = 5000; // tạm thời
        $discount = 0;        // sau này xử lý voucher
        $totalAmount = $subtotal + $shippingFee - $discount;

        // Random mã đơn hàng và không trùng mã đã có
        do {
            $randomCode = (string) random_int(1000000000, 9999999999); // 10 chữ số ngẫu nhiên
        } while (Order::where('order_code', $randomCode)->exists());

        // Lấy phương thức thanh toán
        $paymentMethod = DB::table('payment_methods')->where('id', $data['payment_method_id'])->first();

        if ($paymentMethod->code === 'cod') {
            // Tạo đơn hàng khi COD
            $order = Order::create([
                'user_id' => $userId,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'address' => $fullAddress,
                'note' => $data['note'] ?? null,
                'payment_method_id' => $data['payment_method_id'],
                'payment_status_id' => 1, // unpaid
                'order_status_id' => 1,   // pending
                'order_code'      => 'NOVA' . $randomCode,
                'subtotal'        => $subtotal,
                'discount'        => $discount,
                'shipping_fee'    => $shippingFee,
                'total_amount'    => $totalAmount,
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

            // Xoá cart detail đã đặt
            CartDetail::whereIn('id', $cartDetailIds)->delete();

            return redirect()->route('checkouts.success')->with('order_id', $order->id);
        }

        // Nếu là momo/vnpay: KHÔNG tạo đơn ở đây
        // Thay vào đó, chuẩn bị dữ liệu tạm và chuyển sang Controller thanh toán
        $request->session()->put('checkout_data', [
            'user_id' => $userId,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $fullAddress,
            'note' => $data['note'] ?? null,
            'payment_method_id' => $data['payment_method_id'],
            'order_code'      => 'NOVA' . $randomCode,
            'subtotal'        => $subtotal,
            'discount'        => $discount,
            'shipping_fee'    => $shippingFee,
            'total_amount'    => $totalAmount,
            'cart_detail_ids' => $cartDetailIds,
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
