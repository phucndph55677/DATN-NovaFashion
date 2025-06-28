@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Filter -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold">Dashboard</h4>
            <p class="text-muted">
                Data from {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                to {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            </p>
        </div>
        <form id="filterForm" action="{{ route('admin.dashboards.index') }}" method="GET" class="d-flex gap-2">
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.dashboards.index') }}" class="btn btn-outline-primary">Current Month</a>
        </form>
    </div>
    <div id="error-message" class="alert alert-danger alert-dismissible fade show text-center d-none mt-2" role="alert">
        <span id="error-text"></span>
        <button type="button" class="btn-close position-absolute end-0 top-50 translate-middle-y me-3" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Overview -->
    <div class="row mb-4">
        @foreach ([
            ['label' => 'Total Products', 'value' => $totalProducts],
            ['label' => 'Total Orders', 'value' => $totalOrders],
            ['label' => 'Total Users', 'value' => $totalUsers],
            ['label' => 'Total Revenue', 'value' => number_format($totalRevenue) . ' VND']
        ] as $item)
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6 class="text-muted">{{ $item['label'] }}</h6>
                <h4>{{ $item['value'] }}</h4>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Chart -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">📊 Statistics Chart</h4>
                <div class="btn-group">
                    <button id="btn-revenue" class="btn btn-primary" onclick="switchChart('revenue', this)">Revenue</button>
                    <button id="btn-products" class="btn btn-outline-primary" onclick="switchChart('products', this)">Products</button>
                    <button id="btn-users" class="btn btn-outline-primary" onclick="switchChart('users', this)">Users</button>
                </div>
            </div>
            <div style="height: 400px;">
                <canvas id="chartCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold">Recent Orders</h5>
            @if($recentOrders->count())
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr><th>ID</th><th>Name</th><th>Total</th><th>Time</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->name }}</td>
                            <td>{{ number_format($order->total_amount) }} VND</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $order->orderStatus->name ?? 'Unknown' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted text-center">No orders found for this date range.</p>
            @endif
        </div>
    </div>

    <!-- New Users -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold">New Users</h5>
            @if($newCustomers->count())
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Name</th><th>Email</th><th>Registered At</th></tr></thead>
                    <tbody>
                        @foreach($newCustomers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted text-center">No new users in this date range.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.getElementById('filterForm').addEventListener('submit', function (e) {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        const errorBox = document.getElementById('error-message');
        const errorText = document.getElementById('error-text');

        errorBox.classList.add('d-none');
        errorText.textContent = '';

        if (!start || !end) {
            e.preventDefault();
            errorText.textContent = '❌ Please select both start and end dates.';
            errorBox.classList.remove('d-none');
            return;
        }

        if (start > end) {
            e.preventDefault();
            errorText.textContent = '❌ Start date cannot be later than end date.';
            errorBox.classList.remove('d-none');
        }
    });

    const chartLabels = {
        revenue: @json($labels),
        products: @json($productLabels),
        users: @json($userLabels),
    };

    const chartData = {
        revenue: {
            label: 'Revenue (VND)',
            data: @json($data),
            type: 'line',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            fill: true
        },
        products: {
            label: 'Orders Count',
            data: @json($productData),
            type: 'bar',
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            fill: false
        },
        users: {
            label: 'New Users',
            data: @json($userData),
            type: 'line',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            fill: true
        }
    };

    const ctx = document.getElementById('chartCanvas').getContext('2d');
    let currentChart;

    function renderChart(type) {
        if (currentChart) currentChart.destroy();

        const maxValues = {
            revenue: 100000000,
            products: 100,
            users: 100
        };

        currentChart = new Chart(ctx, {
            type: chartData[type].type,
            data: {
                labels: chartLabels[type],
                datasets: [{
                    label: chartData[type].label,
                    data: chartData[type].data,
                    backgroundColor: chartData[type].backgroundColor,
                    borderColor: chartData[type].borderColor,
                    fill: chartData[type].fill,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: maxValues[type],
                        ticks: {
                            precision: 0,
                            stepSize: type === 'revenue' ? 5000000 : undefined,
                            callback: function(value) {
                                return type === 'revenue' ? value.toLocaleString('vi-VN') + ' ₫' : value;
                            }
                        }
                    }
                }
            }
        });
    }

    function switchChart(type, btn) {
        renderChart(type);
        document.querySelectorAll('.btn-group .btn').forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-outline-primary');
        });
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-primary');
    }

    window.onload = () => {
        const defaultBtn = document.getElementById('btn-revenue');
        switchChart('revenue', defaultBtn);
    };
</script>
@endsection
