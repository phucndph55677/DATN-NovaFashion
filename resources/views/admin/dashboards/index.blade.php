@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Page start  -->
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-2 mb-lg-0">Dashboard</h4>
                <p class="text-muted mb-0">Data from {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
            </div>
            <div class="d-flex align-items-center">
                <form action="{{ route('admin.dashboards.index') }}" method="GET" class="d-flex gap-2">
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" onchange="this.form.submit()" placeholder="From date">
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" onchange="this.form.submit()" placeholder="To date">
                    <a href="{{ route('admin.dashboards.index') }}" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="ri-calendar-line"></i>
                        <span>Current Month</span>
                    </a>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-lg">
                                    <div class="avatar-title rounded-circle bg-light-primary text-primary">
                                        <i class="ri-shopping-bag-line fs-24"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Total Products</p>
                                <h4 class="mb-0">{{ $totalProducts }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-lg">
                                    <div class="avatar-title rounded-circle bg-light-success text-success">
                                        <i class="ri-shopping-cart-line fs-24"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Total Orders</p>
                                <h4 class="mb-0">{{ $totalOrders }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-lg">
                                    <div class="avatar-title rounded-circle bg-light-info text-info">
                                        <i class="ri-user-line fs-24"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Total Users</p>
                                <h4 class="mb-0">{{ $totalUsers }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-lg">
                                    <div class="avatar-title rounded-circle bg-light-warning text-warning">
                                        <i class="ri-money-dollar-circle-line fs-24"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">Total Revenue</p>
                                <h4 class="mb-0">{{ number_format($totalRevenue) }} VND</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold">Revenue Statistics Chart</h4>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary active" id="revenueBtn" onclick="showChart('revenue')">
                                    <i class="ri-money-dollar-circle-line me-2"></i>Revenue
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="productsBtn" onclick="showChart('products')">
                                    <i class="ri-shopping-bag-line me-2"></i>Top Products
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="usersBtn" onclick="showChart('users')">
                                    <i class="ri-user-line me-2"></i>Users
                                </button>
                            </div>
                        </div>

                        <!-- Revenue Chart -->
                        <div id="revenueChartContainer" class="chart-container" style="width: 100%; height: 100%;">
                            <div style="width: 100%; height: 400px;">
                                <canvas id="revenueChart" style="width: 100% !important; height: 100% !important;"></canvas>
                            </div>
                        </div>

                        <!-- Products Chart -->
                        <div id="productsChartContainer" class="chart-container" style="display: none; width: 100%; height: 100%;">
                            <div style="width: 100%; height: 400px;">
                                <canvas id="productsChart" style="width: 100% !important; height: 100% !important;"></canvas>
                            </div>
                        </div>

                        <!-- Users Chart -->
                        <div id="usersChartContainer" class="chart-container" style="display: none; width: 100%; height: 100%;">
                            <div style="width: 100%; height: 400px;">
                                <canvas id="usersChart" style="width: 100% !important; height: 100% !important;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="fw-bold mb-3">Top Selling Products</h4>
                        <div class="table-responsive">
                            @if($topProducts->count() > 0)
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Image</th>
                                        <th>Price</th>
                                        <th>Total Orders</th>
                                        <th>Total Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProducts as $product)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">{{ $product->name }}</h6>
                                        </td>
                                        <td>
                                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="rounded" width="40"
                                                 onerror="this.src='https://picsum.photos/40/40?random={{ $loop->index }}'; this.onerror=null;">
                                        </td>
                                        <td>
                                            @if($product->variants->isNotEmpty())
                                                {{ number_format($product->variants->min('price')) }} VND
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->total_orders ?? 0 }}</td>
                                        <td>{{ $product->total_quantity ?? 0 }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="text-center text-muted py-5">
                                <i class="ri-shopping-bag-line fs-48 mb-3"></i>
                                <p>No selling products data available for the selected period</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="fw-bold mb-3">Recent Orders</h4>
                        <div class="table-responsive">
                            @if($recentOrders->count() > 0)
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Products</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->name }}</td>
                                        <td>
                                            @if($order->orderDetails && $order->orderDetails->count() > 0)
                                                @foreach($order->orderDetails->take(2) as $item)
                                                    @if($item->productVariant && $item->productVariant->product)
                                                        <span class="badge bg-light text-dark">{{ $item->productVariant->product->name }} ({{ $item->quantity }})</span>
                                                    @endif
                                                @endforeach
                                                @if($order->orderDetails->count() > 2)
                                                    <span class="badge bg-light text-dark">+{{ $order->orderDetails->count() - 2 }} more</span>
                                                @endif
                                            @else
                                                <span class="text-muted">No products</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($order->total_amount) }} VND</td>
                                        <td>
                                            {{ $order->orderStatus ? $order->orderStatus->name : 'Not updated' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="text-center text-muted py-5">
                                <i class="ri-shopping-cart-line fs-48 mb-3"></i>
                                <p>No orders data available for the selected period</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="fw-bold mb-3">New Customers</h4>
                        <div class="table-responsive">
                            @if($newCustomers->count() > 0)
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Join Date</th>
                                        <th>Orders</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($newCustomers as $customer)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-lg">
                                                        <div class="avatar-title rounded-circle bg-light-primary text-primary">
                                                            {{ substr($customer->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ $customer->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @php
                                                $orderCount = \App\Models\Order::where('user_id', $customer->id)
                                                    ->whereBetween('created_at', [$startDate, $endDate])
                                                    ->count();
                                            @endphp
                                            {{ $orderCount }}
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="text-center text-muted py-5">
                                <i class="ri-user-line fs-48 mb-3"></i>
                                <p>No new customers data available for the selected period</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dữ liệu từ controller
    const revenueLabels = @json($labels);
    const revenueData = @json($data);

    const productLabels = @json($productLabels);
    const productData = @json($productData);

    const userLabels = @json($userLabels);
    const userData = @json($userData);

    // Khởi tạo biểu đồ doanh thu
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Doanh thu',
                data: revenueData,
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' VND';
                        },
                        stepSize: 5000000,
                        min: 5000000,
                        max: 100000000
                    },
                    min: 0,
                    max: 100000000
                }
            }
        }
    });

    // Khởi tạo biểu đồ sản phẩm bán chạy
    const productsCtx = document.getElementById('productsChart').getContext('2d');
    const productsChart = new Chart(productsCtx, {
        type: 'bar',
        data: {
            labels: productLabels,
            datasets: [{
                label: 'Số đơn hàng',
                data: productData,
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });

    // Khởi tạo biểu đồ người dùng
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    const usersChart = new Chart(usersCtx, {
        type: 'line',
        data: {
            labels: userLabels,
            datasets: [{
                label: 'Người dùng mới',
                data: userData,
                borderColor: 'rgba(255, 159, 64, 1)',
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                fill: true,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });

    // Hàm chuyển đổi hiển thị biểu đồ
    function showChart(chartType) {
        document.getElementById('revenueChartContainer').style.display = 'none';
        document.getElementById('productsChartContainer').style.display = 'none';
        document.getElementById('usersChartContainer').style.display = 'none';

        document.getElementById('revenueBtn').classList.remove('active');
        document.getElementById('productsBtn').classList.remove('active');
        document.getElementById('usersBtn').classList.remove('active');

        if (chartType === 'revenue') {
            document.getElementById('revenueChartContainer').style.display = 'block';
            document.getElementById('revenueBtn').classList.add('active');
        } else if (chartType === 'products') {
            document.getElementById('productsChartContainer').style.display = 'block';
            document.getElementById('productsBtn').classList.add('active');
        } else if (chartType === 'users') {
            document.getElementById('usersChartContainer').style.display = 'block';
            document.getElementById('usersBtn').classList.add('active');
        }
    }
</script>
@endsection

