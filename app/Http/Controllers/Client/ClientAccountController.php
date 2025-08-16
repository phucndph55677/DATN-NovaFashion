<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductFavorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClientAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function info()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        $user = Auth::user();

        return view('client.account.info', compact('user'));
    }

    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        $user = Auth::user(); // Lấy user đang đăng nhập

        $ordersQuery = Order::with('orderDetails.productVariant.product', 'orderStatus')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $ordersQuery->where('order_status_id', $request->status);
        }

        $orders = $ordersQuery->paginate(10);

        return view('client.account.orders.index', compact('orders'));
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
    public function track(string $id)
    {
        if (!Auth::check()) {
                return redirect()->route('login');
            }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        // Lấy đơn hàng theo ID
        $order = Order::with(['orderStatus', 'payment.paymentMethod']) // load quan hệ trạng thái đơn, phương thức thanh toán
            ->where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Lấy chi tiết đơn hàng, kèm thông tin biến thể, màu, size
        $orderDetail = $order->orderDetails()->with([
            'productVariant.product',
            'productVariant.color',
            'productVariant.size',
        ])->get();

        // Tính tổng số lượng
        $order->total_quantity = $orderDetail->sum('quantity');

        // Nếu chưa có shipping_fee trong DB, gán cố định
        if (is_null($order->shipping_fee)) {
            $order->shipping_fee = 30000;
        }

        return view('client.account.orders.track', compact('order', 'orderDetail'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!Auth::check()) {
                return redirect()->route('login');
            }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        // Lấy đơn hàng theo ID
        $order = Order::with(['orderStatus', 'payment.paymentMethod']) // load quan hệ trạng thái đơn, phương thức thanh toán
            ->where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Lấy chi tiết đơn hàng, kèm thông tin biến thể, màu, size
        $orderDetail = $order->orderDetails()->with([
            'productVariant.product',
            'productVariant.color',
            'productVariant.size',
        ])->get();

        // Tính tổng số lượng
        $order->total_quantity = $orderDetail->sum('quantity');

        // Nếu chưa có shipping_fee trong DB, gán cố định
        if (is_null($order->shipping_fee)) {
            $order->shipping_fee = 30000;
        }

        return view('client.account.orders.show', compact('order', 'orderDetail'));
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        // Chỉ cho phép hủy khi trạng thái là 1 (Chưa xác nhận) hoặc 2 (Đã xác nhận)
        if (in_array($order->order_status_id, [1, 2])) {
            $order->order_status_id = 8; // 8 = Trạng thái Hủy đơn
            $order->save();
        }

        return redirect()->back();
    }

     /**
     * Hoàn hàng
     */
    public function return($id)
    {
        $order = Order::findOrFail($id);

        // Chỉ cho phép hoàn hàng khi trạng thái là 6 (Thành công)
        if ($order->order_status_id == 6) {
            $order->order_status_id = 7; // 7 = Trạng thái Hoàn hàng
            $order->save();
        }

        return redirect()->back();
    }

    public function favorite(Request $request)
    {
        if (!Auth::check()) {
                return redirect()->route('login');
            }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        // Lấy danh sách sản phẩm yêu thích của user
        $favorites = ProductFavorite::with([
            'product.variants.color',
            'product.variants.size'
        ])->where('user_id', $userId)->get();

        // Lấy giá trị sắp xếp từ query string
        $sort = $request->query('sort', 'newest');

        // Sắp xếp theo yêu cầu
        $favorites = match ($sort) {
            'price_asc' => $favorites->sortBy(fn($f) => optional($f->product->variants->first())->sale ?? optional($f->product->variants->first())->price ?? 0),
            'price_desc' => $favorites->sortByDesc(fn($f) => optional($f->product->variants->first())->sale ?? optional($f->product->variants->first())->price ?? 0),
            'name_asc' => $favorites->sortBy(fn($f) => $f->product->name),
            'name_desc' => $favorites->sortByDesc(fn($f) => $f->product->name),
            default => $favorites->sortByDesc('created_at'),
        };

        return view('client.account.favorite', compact('favorites', 'sort'));
    }

    public function toggleFavorite(Request $request)
    {
        $userId = Auth::id();
        $productId = $request->input('product_id');

        if (!$userId) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập để thêm sản phẩm yêu thích.');
        }

        if (!$productId) {
            return redirect()->back()->with('error', 'Thông tin sản phẩm không hợp lệ.');
        }

        $favorite = ProductFavorite::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($favorite) {
            $favorite->delete();
            return redirect()->back()->with('success', 'Đã bỏ thích sản phẩm.');
        }

        ProductFavorite::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào yêu thích.');
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
