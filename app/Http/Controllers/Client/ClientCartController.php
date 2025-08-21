<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClientCartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        $cart = Cart::where('user_id', $userId)->first();
        $cartDetails = $cart ? CartDetail::with('productVariant.product')->where('cart_id', $cart->id)->orderBy('created_at', 'desc')->get() : collect();//nếu chưa có giỏ hàng thì trả về mảng rỗng

        // Tính tổng số lượng sản phẩm và tổng tiền
        $totalQuantity = $cartDetails->sum('quantity');
        $totalAmount = $cartDetails->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Cập nhật lại cart trong DB
        if ($cart) {
            $cart->update([
                'quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
            ]);
        }

        foreach ($cartDetails as $detail) {
            $detail->total_amount = $detail->price * $detail->quantity;
            $detail->save();
        }

        return view('client.carts.index', compact('cart', 'cartDetails'));
    }

    /**
     * Update quantity of cart item via AJAX
     */
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'cart_detail_id' => 'required|exists:cart_details,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $cartDetail = CartDetail::findOrFail($request->cart_detail_id);
            $cartDetail->quantity = $request->quantity;
            $cartDetail->total_amount = $cartDetail->price * $request->quantity;
            $cartDetail->save();

            // Cập nhật cart tổng
            $cart = Cart::find($cartDetail->cart_id);
            if ($cart) {
                $cartDetails = CartDetail::where('cart_id', $cart->id)->get();
                $totalQuantity = $cartDetails->sum('quantity');
                $totalAmount = $cartDetails->sum('total_amount');

                $cart->update([
                    'quantity' => $totalQuantity,
                    'total_amount' => $totalAmount,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật số lượng thành công',
                'data' => [
                    'quantity' => $cartDetail->quantity,
                    'total_amount' => $cartDetail->total_amount,
                    'cart_total_amount' => $cart ? $cart->total_amount : 0,
                    'cart_total_quantity' => $cart ? $cart->quantity : 0,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
        ]);
        $quantity = $data['quantity'] ?? 1; // Nếu không có thì mặc định = 1

        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        // Lấy hoặc tạo giỏ hàng
        $cart = Cart::firstOrCreate(
            ['user_id' => $userId],
            ['created_at' => now(), 'updated_at' => now()]
        );

        // Lấy thông tin biến thể, lấy giá
        $variant = ProductVariant::find($data['product_variant_id']);
        $price = $variant->sale > 0 ? $variant->sale : $variant->price;

        // Kiểm tra nếu đã tồn tại trong giỏ → cập nhật
        $cartDetail = CartDetail::where('cart_id', $cart->id)
            ->where('product_variant_id', $data['product_variant_id'])
            ->first();

        if ($cartDetail) {
        // Nếu đã có → tăng số lượng
            $cartDetail->quantity += $quantity;
            $cartDetail->total_amount = $cartDetail->quantity * $cartDetail->price;
            $cartDetail->save();
        } else {
            // Nếu chưa có → thêm mới
            CartDetail::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $data['product_variant_id'],
                'price' => $price,  
                'quantity' => $quantity,
                'total_amount' => $price * $quantity,
            ]);
        }

        return redirect()->back();
    }

    public function buyNow(Request $request)
    {
        $data = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
        ]);
        $quantity = $data['quantity'] ?? 1; // Nếu không có thì mặc định = 1

        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        // Lấy hoặc tạo giỏ hàng
        $cart = Cart::firstOrCreate(
            ['user_id' => $userId],
            ['created_at' => now(), 'updated_at' => now()]
        );

        // Lấy thông tin biến thể, lấy giá
        $variant = ProductVariant::findOrFail($data['product_variant_id']);
        $price = $variant->sale > 0 ? $variant->sale : $variant->price;

        // Kiểm tra nếu đã tồn tại trong giỏ → cập nhật
        $cartDetail = CartDetail::where('cart_id', $cart->id)
            ->where('product_variant_id', $data['product_variant_id'])
            ->first();

        if ($cartDetail) {
            // Nếu đã có → tăng số lượng
            $cartDetail->quantity += $quantity;
            $cartDetail->total_amount = $cartDetail->quantity * $cartDetail->price;
            $cartDetail->save();
        } else {
            CartDetail::create([
                // Nếu chưa có → thêm mới
                'cart_id' => $cart->id,
                'product_variant_id' => $data['product_variant_id'],
                'price' => $price,
                'quantity' => $quantity,
                'total_amount' => $price * $quantity,
            ]);
        }

        session()->flash('buy_now_variant_id', $data['product_variant_id']);

        return redirect()->route('carts.index');
    }

    public function miniCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        $miniCart = Cart::with(['cartDetails.productVariant.product'])
            ->where('user_id', $userId)
            ->first();

        return view('client.partials.navbar', compact('miniCart'));
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
        $cartDetail = CartDetail::findOrFail($id);
        $cartDetail->delete();

        return redirect()->back();
    }
}
