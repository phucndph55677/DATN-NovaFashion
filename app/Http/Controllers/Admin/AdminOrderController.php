<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = Order::with('orderStatus');

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('order_status_id', $request->status);
        }

        // Lọc theo khoảng thời gian có sẵn
        if ($request->filled('filter_by_time')) {
            match ($request->filter_by_time) {
                'week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                'month' => $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]),
                'year' => $query->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()]),
                default => null
            };
        }

        // Lọc theo ngày cụ thể
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $start = $request->start_date . ' 00:00:00';
            $end = $request->end_date . ' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);

        } elseif (!empty($request->start_date)) {
            $query->where('created_at', '>=', $request->start_date . ' 00:00:00');

        } elseif (!empty($request->end_date)) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // Lấy danh sách đơn hàng sau khi lọc
        $orders = $query->latest()->get();

        // Truyền danh sách trạng thái đơn hàng
        $order_statuses = OrderStatus::all();
        
        return view('admin.orders.index', compact('orders', 'order_statuses'));
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
        // Lấy đơn hàng + trạng thái
        $order = Order::with('orderStatus')->findOrFail($id); // lấy 1 đơn hàng theo ID

        // Lấy danh sách sản phẩm trong đơn hàng
        $orderDetails = $order->orderDetails()->with([
            'productVariant.product',
            'productVariant.color',
            'productVariant.size',
        ])->get();

        // Truyền danh sách trạng thái đơn hàng
        $order_statuses = OrderStatus::all();

        return view('admin.orders.show', compact('order', 'orderDetails', 'order_statuses'));
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
        $data = $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id',
        ]);

        $order = Order::findOrFail($id);
        $order->order_status_id = $data['order_status_id'];
        $order->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
