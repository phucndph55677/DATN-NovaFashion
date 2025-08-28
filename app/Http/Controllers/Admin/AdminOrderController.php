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
     * Giao diện trang Tất cả đơn hàng
     */
    public function index(Request $request)
    {

        $query = Order::with('orderStatus');

        // Lọc theo trạng thái thanh toán
        if ($request->filled('payment_status')) {
            $query->where('payment_status_id', $request->payment_status);
        }

        // Lọc theo trạng thái đơn hàng
        if ($request->filled('order_status')) {
            $query->where('order_status_id', $request->order_status);
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
     * Giao diện trang xem chi tiết đơn hàng của (Tất cả đơn hàng)
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

    /**
     * Giao diện trang Đơn yêu cầu hoàn hàng
     */
    public function indexReturn(Request $request)
    {
        $query = Order::with('orderStatus')->where('order_status_id', 7); // chỉ lấy đơn trạng thái yêu cầu hoàn hàng

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

    /**
     * Giao diện trang xem chi tiết đơn hàng của (Đơn yêu cầu hoàn)
    */
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

    /**
     * Hàm xử lý Đòng ý / Từ chối của (Đơn yêu cầu hoàn)
    */
    public function handleReturn(Request $request, $id)
    {
        // Lấy đơn hàng hoặc báo lỗi nếu không tồn tại
        $order = Order::findOrFail($id);

        // Chỉ xử lý khi đơn đang ở trạng thái "Chờ hoàn hàng"
        if ($order->orderStatus->name !== 'Chờ hoàn hàng') {
            return redirect()->back()->with('danger', 'Đơn hàng' . $order->order_code . 'không ở trạng thái chờ hoàn hàng.');
        }

        // Kiểm tra hành động gửi từ form: approve hoặc reject
        DB::transaction(function () use ($request, $order) {
            if ($request->action === 'approve') {
                // Đồng ý hoàn hàng → chuyển trạng thái đơn & thanh toán
                $order->update([
                    'order_status_id' => 8, // Hoàn hàng
                    'payment_status_id' => 3, // Hoàn tiền
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
                ? 'Yêu cầu hoàn hàng cho đơn hàng ' . $order->order_code . ' được chấp thuận.'
                : 'Yêu cầu hoàn hàng cho đơn hàng ' . $order->order_code . ' bị từ chối, đơn hàng trở về trạng thái Thành công.'
        );
    }

    /**
     * Giao diện trang Đơn hoàn tiền
     */
    public function indexRefund(Request $request)
    {

        $query = Order::with('paymentStatus')->where('payment_status_id', 3); // chỉ lấy đơn trạng thái hoàn tiền

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
        
        return view('admin.orders.indexRefund', compact('orders', 'order_statuses', 'payment_statuses'));
    }

    /**
     * Giao diện trang xem chi tiết đơn hàng của (Đơn hoàn tiền)
    */
    public function showRefund(string $id)
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

        return view('admin.orders.showRefund', compact('order', 'orderDetails', 'order_statuses', 'payment_statuses'));
    }

    /**
     * Hàm xử lý Xác nhận hoàn tiền của (Đơn hoàn tiền)
    */
    public function handleRefund(Request $request, $id)
    {
        // Lấy đơn hàng hoặc báo lỗi nếu không tồn tại
        $order = Order::findOrFail($id);

        // Chỉ xử lý khi đơn hàng đang ở trạng thái "Hoàn hàng"
        if ($order->orderStatus->name !== 'Hoàn hàng') {
            return redirect()->back()->with('danger', 'Đơn hàng' . $order->order_code . 'không ở trạng thái hoàn hàng.');
        }

        // Cập nhật trạng thái thanh toán thành "Đã hoàn tiền"
        $order->update([
            'payment_status_id' => 4, // 4 = Đã hoàn tiền
        ]);

        return redirect()->back()->with('success', 'Đơn hàng ' . $order->order_code . ' đã được xác nhận hoàn tiền.');
    }

    /**
     * Hàm xử lý cập nhật Trạng thái thanh toán
     */
    public function updatePaymentStatus(Request $request, string $id)
    {
        $data = $request->validate([
            'payment_status_id' => 'required|exists:payment_statuses,id',
        ]);

        $order = Order::findOrFail($id);
        $order->payment_status_id = $data['payment_status_id'];
        $order->save();

        return redirect()->back()->with('success', 'Trạng thái thanh toán đơn hàng ' . $order->order_code . ' đã được cập nhật.');
    }

    /**
     * Hàm xử lý cập nhật Trạng thái đơn hàng
     */
    public function updateOrderStatus(Request $request, string $id)
    {
        $data = $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id',
        ]);

        $order = Order::findOrFail($id);

        // Nếu đơn hàng đã hủy thì không cho cập nhật nữa
        if (in_array($order->order_status_id, [9])) {
            return redirect()->back()->with('danger', 'Đơn hàng đã ở trạng thái Hủy đơn, không thể cập nhật thay đổi!');
        }
        $order->order_status_id = $data['order_status_id'];

        // Nếu cập nhật đơn hàng sang trạng thái "Thành công"
        if ($data['order_status_id'] == 6) { // 6 = Thành công
            $order->payment_status_id = 2; // thì 2 = Đã thanh toán
        }

        $order->save();

        return redirect()->back()->with('success', 'Trạng thái đơn hàng ' . $order->order_code . ' đã được cập nhật.');
    }
}
