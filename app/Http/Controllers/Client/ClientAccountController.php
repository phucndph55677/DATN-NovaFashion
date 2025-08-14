<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
