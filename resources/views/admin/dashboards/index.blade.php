    @extends('admin.layouts.app')

    @section('title', 'Bảng điều khiển')

    @section('content')
        <div class="container-fluid">
            <!-- Spinner khi loading -->
            <div id="loadingSpinner" class="text-center my-5 d-none">
                <div class="spinner-border text-primary spinner-border-lg" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                </div>
                <p class="mt-2 fw-bold text-primary">Đang tải dữ liệu, vui lòng chờ...</p>
            </div>
            <!-- Bộ lọc dữ liệu -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold">Bảng điều khiển</h4>
                    <p class="text-muted">
                        Dữ liệu từ {{ $startDateFormatted }} đến {{ $endDateFormatted }}
                    </p>
                </div>
                <form id="filterForm" action="{{ route('admin.dashboards.index') }}" method="GET"
                    class="d-flex gap-2 align-items-center  ">
                    {{-- Kiểu lọc --}}
                    <select name="filter_type" id="filter_type" class="form-select form-select-sm w-auto">
                        <option value="" disabled selected>-- Chọn kiểu lọc --</option>
                        <option value="day" {{ request('filter_type') == 'day' ? 'selected' : '' }}>Theo ngày</option>
                        <option value="month" {{ request('filter_type') == 'month' ? 'selected' : '' }}>Theo tháng</option>
                        <option value="year" {{ request('filter_type') == 'year' ? 'selected' : '' }}>Theo năm</option>
                        <option value="quarter" {{ request('filter_type') == 'quarter' ? 'selected' : '' }}>Theo quý
                        </option>
                    </select>

                    {{-- Input lọc động --}}
                    <div id="filter-inputs" class="d-flex gap-2 align-items-center">
                        {{-- Theo tháng --}}
                        <input type="month" name="month" class="form-control form-control-sm w-auto d-none"
                            value="{{ request('month', now()->format('Y-m')) }}">

                        {{-- Theo năm --}}
                        <input type="number" name="year" class="form-control form-control-sm w-auto d-none"
                            value="{{ request('year', now()->year) }}" placeholder="Nhập năm">

                        {{-- Theo quý --}}
                        <select name="quarter" class="form-select form-select-sm w-auto d-none">
                            <option value="1" {{ request('quarter') == 1 ? 'selected' : '' }}>Quý 1</option>
                            <option value="2" {{ request('quarter') == 2 ? 'selected' : '' }}>Quý 2</option>
                            <option value="3" {{ request('quarter') == 3 ? 'selected' : '' }}>Quý 3</option>
                            <option value="4" {{ request('quarter') == 4 ? 'selected' : '' }}>Quý 4</option>
                        </select>
                        <input type="number" name="year_quarter" class="form-control form-control-sm w-auto d-none"
                            value="{{ request('year_quarter', now()->year) }}" placeholder="Năm cho quý">

                        {{-- Theo ngày --}}
                        <input type="date" name="start_date" class="form-control form-control-sm d-none"
                            value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                        <input type="date" name="end_date" class="form-control form-control-sm d-none"
                            value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Lọc</button>
                    <a href="{{ route('admin.dashboards.index') }}"
                        class="btn btn-outline-primary btn-sm text-nowrap py-2">
                        Tháng hiện tại
                    </a>
                </form>
            </div>

            <!-- Modal hiển thị lỗi validate -->
            <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-success">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="errorModalLabel">⚠️ Lỗi</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body fw-bold text-danger" id="errorModalText">
                            <!-- Nội dung lỗi sẽ được chèn bằng JS -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tổng quan -->
            <div class="row mb-4">
                @foreach ($overview as $item)
                    <div class="col-md-3">
                        <div class="card text-center p-3">
                            <h5 class="fw-bold text-dark">{{ $item['label'] }}</h5>
                            <h5>{{ $item['value'] }}</h5>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Biểu đồ -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0">📊 Biểu đồ thống kê</h4>
                        <div class="mb-3">
                            <label for="chartSelector" class="form-label fw-bold">Chọn loại biểu đồ</label>
                            <select id="chartSelector" class="form-select w-auto d-inline-block">
                                <option value="revenue">📈 Tổng Doanh thu</option>
                                <option value="profit">📈 Doanh thu thực nhận</option>
                                <option value="users">👥 Người dùng</option>
                                <option value="products">🥇 Top sản phẩm bán chạy</option>
                                <option value="leastProducts">📉 Top sản phẩm bán chậm</option>
                                <option value="orderStatuses">📦 Trạng thái đơn hàng</option>
                            </select>

                            <!-- Select số lượng sản phẩm -->
                            <select id="topProductLimit" class="form-select w-auto d-inline-block d-none">
                                <option value="5" selected>Top 5</option>
                                <option value="10">Top 10</option>
                                <option value="15">Top 15</option>
                                <option value="20">Top 20</option>
                                <option value="50">Top 50</option>
                            </select>

                            <!-- Select số lượng sản phẩm bán chậm -->
                            <select id="leastProductLimit" class="form-select w-auto d-inline-block d-none">
                                <option value="5" selected>Top 5</option>
                                <option value="10">Top 10</option>
                                <option value="15">Top 15</option>
                                <option value="20">Top 20</option>
                                <option value="50">Top 50</option>
                            </select>
                        </div>
                    </div>
                    <!-- Thông báo khi không có dữ liệu -->
                    <div id="noChartDataMessage" class="alert alert-warning text-center d-none" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Không có dữ liệu để hiển thị cho biểu đồ này.
                    </div>
                    {{-- Canvas chart --}}
                    <div style="width: 100%; height: 70vh;"> <!-- hoặc height: 100%; tùy bạn -->
                        <canvas id="chartCanvas" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Đơn hàng gần đây -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="fw-bold">Đơn hàng mới nhất</h5>
                    <br>
                    @if ($recentOrders->count())
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Khách hàng</th>
                                        <th>Tổng tiền</th>
                                        <th>Thời gian</th>

                                        <th>Trạng thái đơn hàng</th>
                                        <th>Trạng thái thanh toán</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentOrders as $order)
                                        <tr>
                                            <td>{{ $order->order_code }}</td>
                                            <td>{{ $order->name }}</td>
                                            <td>{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $order->orderStatus->name ?? 'Không xác định' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($order->paymentStatus)
                                                    @if ($order->paymentStatus->name === 'Đã thanh toán')
                                                        <span class="badge bg-success">Đã thanh toán</span>
                                                    @elseif ($order->paymentStatus->name === 'Chưa thanh toán')
                                                        <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                                                    @else
                                                        <span
                                                            class="badge bg-secondary">{{ $order->paymentStatus->name }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Không xác định</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">Không có đơn hàng trong khoảng thời gian này.</p>
                    @endif
                </div>
            </div>

            <!-- Người dùng mới -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="fw-bold">Người dùng mới</h5>
                    <br>
                    @if ($newCustomers->count())
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>Vai trò</th>
                                        <th>Ngày đăng ký</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($newCustomers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->role === 'admin')
                                                    <span class="badge bg-primary">Admin</span>
                                                @else
                                                    <span class="badge bg-secondary">User</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if ($user->status == 1)
                                                    <span class="badge bg-success">Đang hoạt động</span>
                                                @else
                                                    <span class="badge bg-secondary">Ngừng hoạt động</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">Không có người dùng mới trong khoảng thời gian này.</p>
                    @endif
                </div>
            </div>

            <!-- Khách hàng hàng đầu -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="fw-bold">Khách hàng hàng đầu</h5>
                    <br>
                    @if ($topUsers->count())
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>Số đơn hàng</th>
                                        <th>Tổng tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topUsers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->total_orders }}</td>
                                            <td>{{ number_format($user->total_amount, 0, ',', '.') }} VND</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">Không có khách hàng nổi bật trong khoảng thời gian này.</p>
                    @endif
                </div>
            </div>

        </div>
    @endsection

    @section('scripts')
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // ================= CHART CONFIG & DATA =================
            const chartData = {
                revenue: {
                    labels: @json($labels),
                    data: @json($data),
                    label: "Doanh thu (VND)",
                    type: "line",
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    fill: true
                },
                products: {
                    labels: @json($productLabels),
                    data: @json($productData),
                    label: "Số sản phẩm bán được",
                    type: "bar",
                    backgroundColor: "rgba(54, 162, 235, 0.7)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    fill: false
                },
                profit: {
                    labels: @json($profitLabels ?? $labels),
                    data: @json($profitValues),
                    label: "Doanh thu thực nhận (VND)",
                    type: "line",
                    backgroundColor: "rgba(34, 197, 94, 0.2)",
                    borderColor: "rgba(34, 197, 94, 1)",
                    fill: true
                },
                users: {
                    labels: @json($userLabels),
                    data: @json($userData),
                    label: "Người dùng mới",
                    type: "line",
                    backgroundColor: "rgba(255, 99, 132, 0.2)",
                    borderColor: "rgba(255, 99, 132, 1)",
                    fill: true
                },
                orderStatuses: {
                    labels: @json($orderStatusLabels),
                    data: @json($orderStatusData),
                    label: "Tỷ lệ đơn hàng",
                    type: "pie",
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.7)",
                        "rgba(54, 162, 235, 0.7)",
                        "rgba(255, 206, 86, 0.7)",
                        "rgba(75, 192, 192, 0.7)",
                        "rgba(153, 102, 255, 0.7)",
                        "rgba(255, 159, 64, 0.7)",
                        "rgba(199, 199, 199, 0.7)",
                        "rgba(255, 105, 180, 0.7)",
                        "rgba(60, 179, 113, 0.7)",
                        "rgba(123, 104, 238, 0.7)"
                    ],
                    borderColor: "rgba(255, 255, 255, 1)",
                    fill: false
                },
                leastProducts: {
                    labels: @json($leastProductLabels),
                    data: @json($leastProductData),
                    label: "Số sản phẩm bán được",
                    type: "bar",
                    backgroundColor: "rgba(255, 99, 132, 0.7)",
                    borderColor: "rgba(255, 99, 132, 1)",
                    fill: false
                }
            };

            const ctx = document.getElementById('chartCanvas').getContext('2d'); // Lấy context của canvas để vẽ Chart.js
            let currentChart; // Biến lưu chart hiện tại (để hủy khi vẽ chart mới)
            let currentType = null; // Biến lưu loại chart đang hiển thị

            // Tạo formatter cho tooltip dựa theo loại chart
            const tooltipFormatters = {
                products: (label, value) => `${label}: ${value} sản phẩm`,
                leastProducts: (label, value) => `${label}: ${value} sản phẩm`,
                orderStatuses: (label, value, dataset) => { // chart pie trạng thái đơn hàng
                    const total = dataset.reduce((a, b) => a + b, 0) || 0; // tổng số đơn
                    const percent = total ? ((value / total) * 100).toFixed(1) : 0; // phần trăm
                    return `${label}: ${value} đơn (${percent}%)`;
                },
                revenue: (label, value) =>
                    `${label}: ${Number(value).toLocaleString("vi-VN", { maximumFractionDigits: 0 })} VND`,
                profit: (label, value) =>
                    `${label}: ${Number(value).toLocaleString("vi-VN", { maximumFractionDigits: 0 })} VND`,
            };

            // Hàm định dạng tooltip dựa theo loại chart
            function formatTooltip(type, context) {
                const value = context.parsed.y ?? context.parsed; // lấy giá trị y
                const label = context.label; // nhãn trục x
                const dataset = context.dataset.data; // dữ liệu dataset
                return tooltipFormatters[type]?.(label, value, dataset) ?? context.formattedValue; // trả tooltip
            }

            // Hàm định dạng trục y (nếu là tiền thì thêm VND, nếu là số thì nguyên)
            function formatYAxis(type, value) {
                if (type === 'revenue' || type === 'profit') {
                    return value.toLocaleString('vi-VN') + ' VND';
                }
                return Number.isInteger(value) ? value : null;
            }

            // Cấu hình cơ bản chung cho tất cả chart
            const baseOptions = {
                responsive: true, // responsive theo kích thước
                maintainAspectRatio: false, // không giữ tỉ lệ cố định
                layout: {
                    padding: 20
                },
                plugins: {
                    legend: {
                        display: true,
                        align: 'center',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            boxWidth: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => formatTooltip(currentType, ctx) // gọi hàm tooltip
                        }
                    }
                }
            };

            // ================= HÀM HIỂN THỊ/ẨN CHART =================
            function toggleChartVisibility(hasData) {
                const chartCanvas = document.getElementById('chartCanvas');
                const noDataMessage = document.getElementById('noChartDataMessage');
                chartCanvas.style.display = hasData ? 'block' : 'none'; // hiển thị canvas nếu có dữ liệu
                noDataMessage.classList.toggle('d-none', hasData); // hiển thị thông báo nếu không có dữ liệu
            }

            // ================= HÀM RENDER CHART =================
            function renderChart(type) {
                if (currentChart) currentChart.destroy(); // xóa chart cũ nếu tồn tại
                currentType = type; // cập nhật loại chart hiện tại

                const hasData = chartData[type].data?.some(v => v > 0); // kiểm tra có dữ liệu > 0 không
                toggleChartVisibility(hasData); // nếu không có dữ liệu thì dừng
                if (!hasData) return;

                // Cấu hình chart
                const chartConfig = {
                    type: chartData[type].type,
                    data: {
                        labels: chartData[type].labels,
                        datasets: [{
                            ...chartData[type],
                            tension: chartData[type].type === 'line' ? 0.3 : 0
                        }]
                    },
                    options: {
                        ...baseOptions,
                        plugins: {
                            ...baseOptions.plugins,
                            legend: {
                                ...baseOptions.plugins.legend,
                                position: chartData[type].type === 'pie' ? 'bottom' : 'top'
                            }
                        }
                    }
                };

                // Nếu không phải pie chart, thêm cấu hình trục x, y
                if (chartData[type].type !== 'pie') {
                    chartConfig.options.scales = {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                callback: (value) => formatYAxis(type, value)
                            }
                        },
                        x: {
                            ticks: {
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 0
                            }
                        }
                    };
                }
                currentChart = new Chart(ctx, chartConfig); // Khởi tạo chart
            }

            // ================= VALIDATE FORM =================
            function validateFilter(filterType, values) {
                if (!filterType) return "❌ Vui lòng chọn kiểu lọc.";
                const {
                    month,
                    year,
                    quarter,
                    yearQuarter,
                    start,
                    end
                } = values;
                switch (filterType) {
                    case "month":
                        return !month && "❌ Vui lòng chọn tháng.";
                    case "year":
                        return !year && "❌ Vui lòng chọn năm.";
                    case "quarter":
                        return (!quarter || !yearQuarter) && "❌ Vui lòng chọn quý và năm cho lọc theo quý.";
                    case "day":
                        if (!start || !end) return "❌ Vui lòng chọn ngày bắt đầu và kết thúc.";
                        if (start > end) return "❌ Ngày bắt đầu không được lớn hơn ngày kết thúc.";
                        return null;
                    default:
                        return null;
                }
            }

            // ================= HÀM HIỂN THỊ LỖI =================
            function showError(message) {
                document.getElementById('errorModalText').innerText = message; // chèn message vào modal
                let modal = new bootstrap.Modal(document.getElementById('errorModal'));
                modal.show(); // hiển thị modal lỗi
            }

            // ================= DOM READY =================
            document.addEventListener("DOMContentLoaded", function() {
                const chartSelector = document.getElementById("chartSelector");
                const topProductLimit = document.getElementById("topProductLimit");
                const leastProductLimit = document.getElementById("leastProductLimit");
                const filterType = document.getElementById("filter_type");

                // map loại chart với limit selector tương ứng
                const limitSelectors = {
                    products: topProductLimit,
                    leastProducts: leastProductLimit
                };

                // Thay đổi select chart: show/hide limit selectors
                chartSelector.addEventListener("change", function() {
                    Object.values(limitSelectors).forEach(el => el.classList.add("d-none"));
                    if (limitSelectors[this.value]) {
                        limitSelectors[this.value].classList.remove("d-none");
                    }
                });

                // ================= FETCH DỮ LIỆU SẢN PHẨM =================
                async function fetchProducts(limit, type = "products") {
                    let url =
                        `{{ route('admin.dashboards.index') }}?filter_type={{ request('filter_type') }}&limit=${limit}${type==="leastProducts" ? "&type=least" : ""}`;
                    try {
                        const res = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const data = await res.json();
                        chartData[type].labels = data.labels;
                        chartData[type].data = data.data;
                        renderChart(type);
                    } catch (err) {
                        console.error("Lỗi khi fetch dữ liệu:", err);
                    }
                }

                // Thay đổi limit select => fetch lại dữ liệu
                [topProductLimit, leastProductLimit].forEach(selectEl => {
                    selectEl.addEventListener("change", e => {
                        const type = e.target.id === "topProductLimit" ? "products" : "leastProducts";
                        fetchProducts(e.target.value, type);
                    });
                });

                // toggle filter inputs
                const inputs = {
                    month: document.querySelector("input[name='month']"),
                    year: document.querySelector("input[name='year']"),
                    quarter: document.querySelector("select[name='quarter']"),
                    year_quarter: document.querySelector("input[name='year_quarter']"),
                    start_date: document.querySelector("input[name='start_date']"),
                    end_date: document.querySelector("input[name='end_date']")
                };

                // Nhóm input theo loại lọc
                const inputGroups = {
                    month: ["month"],
                    year: ["year"],
                    quarter: ["quarter", "year_quarter"],
                    day: ["start_date", "end_date"],
                };

                function toggleInputs(type) {
                    const active = inputGroups[type] || [];
                    Object.entries(inputs).forEach(([key, el]) => {
                        el.classList.toggle("d-none", !active.includes(key));
                    });
                }

                toggleInputs(filterType.value);
                filterType.addEventListener("change", function() {
                    toggleInputs(this.value);
                });

                // Render chart mặc định
                renderChart(chartSelector.value);
                chartSelector.addEventListener('change', function() {
                    renderChart(this.value);
                });

                // validate form khi submit
                document.getElementById('filterForm').addEventListener('submit', function(e) {
                    const filterType = document.getElementById("filter_type").value;
                    const month = document.querySelector('input[name="month"]').value;
                    const year = document.querySelector('input[name="year"]').value;
                    const quarter = document.querySelector('select[name="quarter"]').value;
                    const yearQuarter = document.querySelector('input[name="year_quarter"]').value;
                    const start = document.querySelector('input[name="start_date"]').value;
                    const end = document.querySelector('input[name="end_date"]').value;

                    const errorMsg = validateFilter(filterType, {
                        month,
                        year,
                        quarter,
                        yearQuarter,
                        start,
                        end
                    });
                    if (errorMsg) {
                        e.preventDefault();
                        showError(errorMsg);
                        return;
                    }
                });
            });
        </script>
    @endsection
