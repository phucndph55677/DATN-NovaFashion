<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Lấy khoảng thời gian từ request, mặc định là từ đầu tháng đến cuối tháng của tháng hiện tại
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Thống kê tổng quan
        $totalProducts = Product::count();
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');

        // Doanh thu theo ngày
        $revenueData = Order::whereBetween('created_at', [$startDate, $endDate])
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
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->selectRaw('products.*, COUNT(DISTINCT order_details.order_id) as total_orders, SUM(order_details.quantity) as total_quantity')
            ->groupBy('products.id')
            ->orderBy('total_orders', 'desc')
            ->take(10)
            ->get();

        $productLabels = $topProductsChart->pluck('name')->toArray();
        $productData = $topProductsChart->pluck('total_orders')->toArray();

        // Dữ liệu cho biểu đồ người dùng theo ngày
        $usersData = User::whereBetween('created_at', [$startDate, $endDate])
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
            ->whereBetween('orders.created_at', [$startDate, $endDate])
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
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Khách hàng mới
        $newCustomers = User::where('role_id', 2)
            ->whereBetween('created_at', [$startDate, $endDate])
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

    public function create() {}
    public function store(Request $request) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
