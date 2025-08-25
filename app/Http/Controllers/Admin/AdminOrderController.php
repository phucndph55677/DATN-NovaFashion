<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $payment_statuses = PaymentStatus::all();
        
        return view('admin.orders.index', compact('orders', 'order_statuses', 'payment_statuses'));
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
        $order = Order::with(['orderStatus', 'payment.paymentMethod'])->findOrFail($id); // lấy 1 đơn hàng theo ID
        
        // Lấy danh sách sản phẩm trong đơn hàng
        $orderDetails = $order->orderDetails()->with([
            'productVariant.product',
            'productVariant.color',
            'productVariant.size',
        ])->get();

        // Truyền danh sách trạng thái đơn hàng
        $order_statuses = OrderStatus::all();
        $payment_statuses = PaymentStatus::all();

        return view('admin.orders.show', compact('order', 'orderDetails', 'order_statuses', 'payment_statuses'));
    }

    public function orderReturn(Request $request)
    {
        $query = Order::with('orderStatus')->where('order_status_id', 7); // chỉ lấy đơn trạng thái yêu cầu hoàn hàng

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
        $payment_statuses = PaymentStatus::all();
        
        return view('admin.orders.indexReturn', compact('orders', 'order_statuses', 'payment_statuses'));
    }

    public function showReturn(string $id)
    {
        // Lấy đơn hàng + trạng thái
        $order = Order::with(['orderStatus', 'payment.paymentMethod'])->findOrFail($id); // lấy 1 đơn hàng theo ID
        
        // Lấy danh sách sản phẩm trong đơn hàng
        $orderDetails = $order->orderDetails()->with([
            'productVariant.product',
            'productVariant.color',
            'productVariant.size',
        ])->get();

        // Truyền danh sách trạng thái đơn hàng
        $order_statuses = OrderStatus::all();
        $payment_statuses = PaymentStatus::all();

        return view('admin.orders.showReturn', compact('order', 'orderDetails', 'order_statuses', 'payment_statuses'));
    }

    public function handleReturn(Request $request, $id)
    {
        // Lấy đơn hàng hoặc báo lỗi nếu không tồn tại
        $order = Order::findOrFail($id);

        // Chỉ xử lý khi đơn đang ở trạng thái "Chờ hoàn hàng"
        if ($order->orderStatus->name !== 'Chờ hoàn hàng') {
            return redirect()->back()->with('error', 'Đơn hàng không ở trạng thái chờ hoàn hàng.');
        }

        // Kiểm tra hành động gửi từ form: approve hoặc reject
        DB::transaction(function () use ($request, $order) {
            if ($request->action === 'approve') {
                $order->update([
                    'order_status_id' => 8, // Hoàn hàng
                ]);

            } else {
                $order->update([
                    'order_status_id' => 6,   // Thành công
                    'return_reason' => null,
                    'return_bank' => null,
                    'return_stk' => null,
                    'return_image' => null,
                ]);
            }
        });

        return redirect()->back()->with(
            $request->action === 'approve' ? 'success' : 'info',
            $request->action === 'approve'
                ? 'Đơn hàng đã được chấp thuận hoàn hàng.'
                : 'Yêu cầu hoàn hàng bị từ chối, đơn hàng trở về trạng thái Thành công.'
        );
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
    public function updatePaymentStatus(Request $request, string $id)
    {
        $data = $request->validate([
            'payment_status_id' => 'required|exists:payment_statuses,id',
        ]);

        $order = Order::findOrFail($id);
        $order->payment_status_id = $data['payment_status_id'];
        $order->save();

        return redirect()->back();
    }

    public function updateOrderStatus(Request $request, string $id)
    {
        $data = $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id',
        ]);

        $order = Order::findOrFail($id);

        // Nếu đơn hàng đã hủy, thành công hoặc hoàn hàng thì không cho cập nhật nữa
        if (in_array($order->order_status_id, [9])) {
            return redirect()->back()->with('error', 'Đơn hàng đã ở trạng thái Thành công hoặc Hủy đơn, không thể thay đổi!');
        }
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
