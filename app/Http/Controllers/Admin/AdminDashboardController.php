<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Bộ lọc ngày + limit
        $limit = $request->input('limit', 5);
        [$start, $end] = $this->getDateRange($request);

        $startDate = $start->format('Y-m-d');
        $endDate   = $end->format('Y-m-d');

        // 2. Tổng quan
        $totalProducts = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('product_variants', 'order_details.product_variant_id', '=', 'product_variants.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.order_status_id', 6)
            ->where('orders.payment_status_id', 2)
            ->sum('order_details.quantity');

        $totalOrders = Order::whereBetween('created_at', [$start, $end])
            ->where('order_status_id', 6)
            ->where('payment_status_id', 2)
            ->count();

        $totalRevenueMoney = Order::whereBetween('created_at', [$start, $end])
            ->where('order_status_id', 6)
            ->where('payment_status_id', 2)
            ->selectRaw('SUM(total_amount - 30000 - discount) as total_revenue')
            ->value('total_revenue');

        $totalRevenue = Order::whereBetween('created_at', [$start, $end])
            ->where('order_status_id', 6)
            ->where('payment_status_id', 2)
            ->sum('total_amount');

        // 3. Xác định groupType cho biểu đồ
        $diffDays = $start->diffInDays($end);
        $groupType = $diffDays <= 31 ? 'day' : ($diffDays <= 180 ? 'week4' : ($diffDays <= 365 ? 'month' : 'year'));

        // 4. Doanh thu theo thời gian
        [$labels, $data] = $this->buildChartData(
            Order::whereBetween('created_at', [$start, $end])
                ->where('order_status_id', 6)
                ->where('payment_status_id', 2),
            $start,
            $end,
            $groupType,
            'SUM(total_amount)'
        );

        // 5. Lợi nhuận theo thời gian
        [$profitLabels, $profitValues] = $this->buildChartData(
            DB::table('orders')->where('order_status_id', 6)
                ->where('payment_status_id', 2)
                ->whereBetween('created_at', [$start, $end]),
            $start,
            $end,
            $groupType,
            'SUM(total_amount - 30000 - discount)'
        );

        // 6. Người dùng mới theo thời gian
        [$userLabels, $userData] = $this->buildChartData(
            User::whereBetween('created_at', [$start, $end]),
            $start,
            $end,
            $groupType,
            'COUNT(*)'
        );

        // 7. Top sản phẩm bán chạy (chart)
        $topProductsChart = Product::select('products.*', DB::raw('SUM(order_details.quantity) as total_quantity'))
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->join('order_details', 'product_variants.id', '=', 'order_details.product_variant_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.order_status_id', 6)
            ->where('orders.payment_status_id', 2)
            ->groupBy('products.id')
            ->orderByDesc('total_quantity')
            ->take($limit)
            ->get();

        $productLabels = $topProductsChart->pluck('name')->toArray();
        $productData   = $topProductsChart->pluck('total_quantity')->toArray();

        // 9. Sản phẩm bán chậm
        $leastSellingProducts = Product::select(
            'products.id',
            'products.name',
            DB::raw('COALESCE(SUM(order_details.quantity), 0) as total_quantity')
        )
            ->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->leftJoin('order_details', 'product_variants.id', '=', 'order_details.product_variant_id')
            ->leftJoin('orders', function ($join) use ($start, $end) {
                $join->on('order_details.order_id', '=', 'orders.id')
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->where('orders.order_status_id', 6)
                    ->where('orders.payment_status_id', 2);
            })
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'asc')
            ->take($limit)
            ->get();


        $leastProductLabels = $leastSellingProducts->pluck('name')->toArray();
        $leastProductData   = $leastSellingProducts->pluck('total_quantity')->toArray();

        // 10. Trạng thái đơn hàng
        $orderStatusStats = DB::table('orders')
            ->join('order_statuses', 'orders.order_status_id', '=', 'order_statuses.id')
            ->where('payment_status_id', 2)
            ->whereBetween('orders.created_at', [$start, $end])
            ->select('order_statuses.name as status_name', DB::raw('COUNT(*) as count'))
            ->groupBy('order_statuses.name')->get();

        $orderStatusLabels = $orderStatusStats->pluck('status_name');
        $orderStatusData   = $orderStatusStats->pluck('count');

        // 11. Đơn hàng gần đây
        $recentOrders = Order::with(['user', 'orderDetails.productVariant.product'])
            ->whereBetween('created_at', [$start, $end])
            ->latest()->take(5)->get();

        // 12. Người dùng mới
        $newCustomers = User::where('role_id', 2)
            ->whereBetween('created_at', [$start, $end])
            ->latest()->take(5)->get();

        // 13. Top người dùng
        $topUsers = User::select(
            'users.id',
            'users.name',
            'users.email',
            DB::raw('COUNT(orders.id) as total_orders'),
            DB::raw('SUM(orders.total_amount) as total_amount')
        )
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->where('orders.order_status_id', 6)
            ->where('payment_status_id', 2)
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_orders')->take(5)->get();

        // 14. Xử lý AJAX (chart update)
        if ($request->ajax()) {
            if ($request->input('type') === 'least') {
                return response()->json([
                    'labels' => $leastProductLabels,
                    'data'   => collect($leastProductData)->map(fn($q) => (int)$q),
                ]);
            }
            return response()->json([
                'labels' => $productLabels,
                'data'   => collect($productData)->map(fn($q) => (int)$q),
            ]);
        }

        // 15. Trả dữ liệu về view
        $overview = [
            ['label' => 'Tổng sản phẩm', 'value' => $totalProducts],
            ['label' => 'Tổng đơn hàng', 'value' => $totalOrders],
            ['label' => 'Tổng doanh thu thực tế', 'value' => number_format($totalRevenueMoney, 0, ',', '.') . ' VND'],
            ['label' => 'Tổng doanh thu', 'value' => number_format($totalRevenue, 0, ',', '.') . ' VND'],
        ];
        $startDateFormatted = Carbon::parse($startDate)->format('m/d/Y');
        $endDateFormatted   = Carbon::parse($endDate)->format('m/d/Y');

        return view('admin.dashboards.index', compact(
            'totalProducts',
            'totalOrders',
            'totalRevenueMoney',
            'totalRevenue',
            'labels',
            'data',
            'profitLabels',
            'profitValues',
            'userLabels',
            'userData',
            'productLabels',
            'productData',
            'leastProductLabels',
            'leastProductData',
            'leastSellingProducts',
            'orderStatusLabels',
            'orderStatusData',
            'recentOrders',
            'newCustomers',
            'topUsers',
            'limit',
            'overview',
            'startDateFormatted',
            'endDateFormatted'
        ));
    }


    private function buildChartData($query, $start, $end, $type, $sumExpr)
    {
        $labels = $values = [];
        switch ($type) {
            case 'day':
                $data = $query->selectRaw("DATE(created_at) as period, $sumExpr as val")
                    ->groupBy('period')->pluck('val', 'period');
                for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                    $key = $d->format('Y-m-d');
                    $labels[] = $d->format('d/m/Y');
                    $values[] = (float) ($data[$key] ?? 0);
                }
                break;
            case 'month':
                $data = $query->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period, $sumExpr as val")
                    ->groupBy('period')->pluck('val', 'period');
                for ($d = $start->copy()->startOfMonth(); $d->lte($end); $d->addMonth()) {
                    $key = $d->format('Y-m');
                    $labels[] = $d->format('m/Y');
                    $values[] = (float) ($data[$key] ?? 0);
                }
                break;
            case 'year':
                $data = $query->selectRaw("YEAR(created_at) as period, $sumExpr as val")
                    ->groupBy('period')->pluck('val', 'period');
                for ($d = $start->copy()->startOfYear(); $d->lte($end); $d->addYear()) {
                    $key = $d->format('Y');
                    $labels[] = $key;
                    $values[] = (float) ($data[$key] ?? 0);
                }
                break;
            default: // week4
                $data = $query->selectRaw("YEAR(created_at) as y, MONTH(created_at) as m,
                        CASE WHEN DAY(created_at) BETWEEN 1 AND 7 THEN 1
                             WHEN DAY(created_at) BETWEEN 8 AND 14 THEN 2
                             WHEN DAY(created_at) BETWEEN 15 AND 21 THEN 3
                             ELSE 4 END as w, $sumExpr as val")
                    ->groupBy('y', 'm', 'w')->get()
                    ->mapWithKeys(fn($r) => ["{$r->y}-{$r->m}-{$r->w}" => $r->val]);
                for ($d = $start->copy()->startOfMonth(); $d->lte($end); $d->addMonth()) {
                    for ($w = 1; $w <= 4; $w++) {
                        $key = "{$d->year}-{$d->month}-{$w}";
                        if ($d->lte($end)) {
                            $labels[] = "Tuần $w/{$d->month}-{$d->year}";
                            $values[] = (float)($data[$key] ?? 0);
                        }
                    }
                }
        }
        return [$labels, $values];
    }

    private function getDateRange(Request $request)
    {
        $filterType = $request->input('filter_type');
        $start = $end = null;
        switch ($filterType) {
            case 'month':
                if ($request->filled('month')) {
                    $start = Carbon::createFromFormat('Y-m', $request->month)->startOfMonth();
                    $end   = Carbon::createFromFormat('Y-m', $request->month)->endOfMonth();
                }
                break;
            case 'year':
                if ($request->filled('year')) {
                    $start = Carbon::createFromDate($request->year, 1, 1)->startOfDay();
                    $end   = Carbon::createFromDate($request->year, 12, 31)->endOfDay();
                }
                break;
            case 'quarter':
                if ($request->filled('quarter') && $request->filled('year_quarter')) {
                    $start = Carbon::createFromDate($request->year_quarter, ($request->quarter - 1) * 3 + 1, 1);
                    $end   = (clone $start)->addMonths(2)->endOfMonth();
                }
                break;
            case 'day':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $start = Carbon::parse($request->start_date)->startOfDay();
                    $end   = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
        }
        return [$start ?? now()->startOfMonth(), $end ?? now()->endOfMonth()];
    }
}
