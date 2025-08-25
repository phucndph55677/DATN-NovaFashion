<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        // Doanh thu theo ngày
        $revenueData = Order::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $labels = [];
        $data = [];
        $currentDate = Carbon::parse($startDate);
        $endDateObj = Carbon::parse($endDate);

        while ($currentDate->lte($endDateObj)) {
            $dateStr = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('d/m/Y');
            $revenue = $revenueData->firstWhere('date', $dateStr);
            $data[] = $revenue ? (float) $revenue->total : 0;
            $currentDate = $currentDate->copy()->addDay();
        }

        // Dữ liệu cho biểu đồ sản phẩm bán chạy (top 10)
        $topProductsChart = Product::select('products.*')
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->join('order_details', 'product_variants.id', '=', 'order_details.product_variant_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw('products.*, COUNT(DISTINCT order_details.order_id) as total_orders, SUM(order_details.quantity) as total_quantity')
            ->groupBy('products.id')
            ->orderBy('total_orders', 'desc')
            ->take(10)
            ->get();

        $productLabels = $topProductsChart->pluck('name')->toArray();
        $productData = $topProductsChart->pluck('total_orders')->toArray();

        // Dữ liệu cho biểu đồ người dùng theo ngày
        $usersData = User::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $userLabels = [];
        $userData = [];
        $currentDate = Carbon::parse($startDate);
        $endDateObj = Carbon::parse($endDate);

        while ($currentDate->lte($endDateObj)) {
            $dateStr = $currentDate->format('Y-m-d');
            $userLabels[] = $currentDate->format('d/m/Y');
            $users = $usersData->firstWhere('date', $dateStr);
            $userData[] = $users ? (int) $users->total : 0;
            $currentDate = $currentDate->copy()->addDay();
        }

        // Sản phẩm bán chạy nhất
        $topProducts = Product::select('products.*')
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->join('order_details', 'product_variants.id', '=', 'order_details.product_variant_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw('products.*, COUNT(DISTINCT order_details.order_id) as total_orders, SUM(order_details.quantity) as total_quantity')
            ->groupBy('products.id')
            ->with(['variants' => function($query) {
                $query->select('product_id', 'price');
            }])
            ->orderBy('total_orders', 'desc')
            ->take(5)
            ->get();

        // Đơn hàng gần đây
        $recentOrders = Order::with(['user', 'orderDetails.productVariant.product'])
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Khách hàng mới
        $newCustomers = User::where('role_id', 2)
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboards.index', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'topProducts',
            'recentOrders',
            'newCustomers',
            'startDate',
            'endDate',
            'labels',
            'data',
            'productLabels',
            'productData',
            'userLabels',
            'userData'
        ));
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
