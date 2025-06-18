<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = Order::with('user', 'orderStatus')->orderBy('created_at', 'desc');

        // Lọc theo trạng thái nếu có
        if ($request->filled('status')) {
            $query->where('order_status_id', $request->status);
        }

        // Lọc theo thời gian
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->paginate(10);
        $statuses = OrderStatus::all(); // truyền danh sách trạng thái
        return view('admin.orders.index', compact('orders', 'statuses'));
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
        $order = Order::with('orderDetails.productVariant.product')->findOrFail($id);
        $order_statuses = OrderStatus::all();

        $total = $order->orderDetails->sum(function ($detail) {
            return $detail->price * $detail->quantity;
        });
        $invoices = Invoice::all();
        $completedStatus = OrderStatus::where('name', 'Hoàn thành')->first();
        return view('admin.orders.show', compact('order', 'invoices', 'total', 'order_statuses', 'completedStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        $statuses = OrderStatus::all();
        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id',
        ]);



        $order = Order::findOrFail($id);
        $order->order_status_id = $request->order_status_id;
        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Cập nhật đơn hàng thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
