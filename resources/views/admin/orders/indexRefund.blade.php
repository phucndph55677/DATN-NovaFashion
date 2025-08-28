@extends('admin.layouts.app')

@section('title', 'Đơn Hàng')

@section('content')
    <!-- Thông báo chung -->
    @foreach (['success', 'info', 'warning', 'danger'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endforeach

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Header -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-schedule mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="fw-bold">Đơn Hàng</h4>
                    </div>
                    <div class="create-workform">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <!-- Search -->
                            {{-- <div class="modal-product-search d-flex flex-wrap">
                                <form class="me-3 position-relative">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="exampleInputText"placeholder="Tìm kiếm đơn hàng...">
                                        <a class="search-link" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="" width="20"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </a>
                                    </div>
                                </form>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <!-- Card Table -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-block card-stretch">
                            <div class="card-body p-0">
                                <div class="d-flex justify-content-between align-items-center p-3 pb-0">
                                    <h5 class="fw-bold">Danh Sách Đơn Hoàn Tiền</h5>
                                </div>

                                <form method="GET" action="{{ route('admin.indexReturn') }}" class="px-3 py-2">
                                    <div class="row gx-3 gy-2 align-items-end">
                                        
                                        <!-- Khoảng thời gian -->
                                        <div class="col-lg-auto flex-grow-1">
                                            <label class="form-label">Khoảng Thời Gian</label>
                                            <select name="filter_by_time" class="form-select form-select-sm rounded-pill shadow-sm">
                                                <option value="">-- Lựa chọn --</option>
                                                <option value="week" {{ request('filter_by_time') == 'week' ? 'selected' : '' }}>Tuần này</option>
                                                <option value="month" {{ request('filter_by_time') == 'month' ? 'selected' : '' }}>Tháng này</option>
                                                <option value="year" {{ request('filter_by_time') == 'year' ? 'selected' : '' }}>Năm nay</option>
                                            </select>
                                        </div>

                                        <!-- Từ ngày -->
                                        <div class="col-lg-auto flex-grow-1">
                                            <label class="form-label">Từ Ngày</label>
                                            <input type="date" name="start_date" class="form-select form-select-sm rounded-pill shadow-sm"
                                                value="{{ request('start_date') }}">
                                        </div>

                                        <!-- Đến ngày -->
                                        <div class="col-lg-auto flex-grow-1">
                                            <label class="form-label">Đến Ngày</label>
                                            <input type="date" name="end_date" class="form-select form-select-sm rounded-pill shadow-sm"
                                                value="{{ request('end_date') }}">
                                        </div>

                                        <!-- Nút hành động -->
                                        <div class="col-lg-auto d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm rounded-pill shadow-sm px-3">Lọc</button>
                                            <a href="{{ route('admin.indexReturn') }}" class="btn btn-secondary btn-sm rounded-pill shadow-sm px-3">Cài Lại</a>
                                        </div>
                                    </div>
                                </form>

                                <!-- Table -->
                                <div class="table-responsive iq-order-table">
                                    <table class="table data-table mb-0">
                                        <thead class="table-color-heading">
                                            <tr class="text-light">
                                                <th><label class="text-muted m-0">ID</label></th>
                                                <th><label class="text-muted m-0">Mã Đơn Hàng</label></th>
                                                <th><label class="text-muted mb-0">Tên Khách Hàng</label></th>
                                                <th><label class="text-muted mb-0">Tổng Tiền</label></th>
                                                <th><label class="text-muted mb-0">TT Thanh Toán</label></th>
                                                <th><label class="text-muted mb-0">TT Đơn Hàng</label></th>
                                                <th><label class="text-muted mb-0">Ngày Đặt</label></th>
                                                <th class="text-start"><span class="text-muted">Hành Động</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr class="white-space-no-wrap">
                                                    <td>{{ $order->id }}</td>
                                                    <td>{{ $order->order_code }}</td>
                                                    <td>{{ $order->name }}</td>
                                                    <td>{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
                                                    <td>
                                                        <span class="badge bg-{{ $order->payment_badge_color }}">
                                                            {{ $order->paymentStatus?->name ?? 'Chưa xác định' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $order->order_badge_color }}">
                                                            {{ $order->orderStatus?->name ?? 'Chưa xác định' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-start align-items-center">

                                                        <!-- View -->
                                                        <a class="" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Xem"
                                                            href="{{ route('admin.showRefund', $order->id) }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="text-secondary me-4" width="20"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </a>

                                                        <!-- Edit -->
                                                        <a href="" data-bs-toggle="modal"
                                                            data-bs-target="#refundOrderModal{{ $order->id }}" title="Xác nhận hoàn tiền">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="text-secondary me-4" width="20"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                            </svg>
                                                        </a>   
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals moved outside table structure -->
    @foreach ($orders as $order)
        <div class="modal fade" id="refundOrderModal{{ $order->id }}" tabindex="-1" aria-labelledby="refundOrderLabel{{ $order->id }}" aria-hidden="true" data-bs-scrollbar="false">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">

                    <!-- Header -->
                    <div class="modal-header text-start">
                        <h4 class="modal-title">Đơn Hoàn Tiền - {{ $order->order_code }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body text-start">
                        <div class="mb-3">
                            <strong>Lý Do:</strong>
                            <span class="ms-2">{{ $order->return_reason ?? 'Không có lý do' }}</span>
                        </div>

                        <div class="mb-3">
                            <strong>Ngân Hàng:</strong>
                            <span class="text-muted ms-2">{{ $order->return_bank ?? 'Không có lý do' }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Số Tài Khoản:</strong>
                            <span class="text-muted ms-2">{{ $order->return_stk ?? 'Không có lý do' }}</span>
                        </div>

                        <!-- Hình ảnh minh chứng -->
                        @php
                            $rawImages = $order->return_image;
                            $returnImages = [];

                            if (!empty($rawImages)) {
                                $decoded = json_decode($rawImages, true);
                                if (is_array($decoded)) {
                                    $returnImages = $decoded;
                                } else {
                                    // Không phải JSON: tách theo dấu phẩy hoặc dùng 1 URL đơn
                                    $parts = preg_split('/\s*,\s*/', $rawImages, -1, PREG_SPLIT_NO_EMPTY);
                                    $returnImages = $parts ?: [$rawImages];
                                }
                            }
                        @endphp

                        @if(!empty($returnImages))
                            <div class="mb-3">
                                <strong>Hình Ảnh Minh Chứng:</strong>
                                <div class="d-flex flex-wrap gap-3 mt-2">
                                    @foreach($returnImages as $img)
                                        @php
                                            $isAbsolute = Str::startsWith($img, ['http://', 'https://']);
                                            $src = $isAbsolute ? $img : asset('storage/' . ltrim($img, '/'));
                                        @endphp
                                        <div class="border rounded bg-light p-1" style="width: 120px; height: 120px; overflow: hidden;">
                                            <img src="{{ $src }}" 
                                                alt="Ảnh hoàn hàng"
                                                class="img-fluid rounded" 
                                                style="width: 120px; height: 120px; object-fit: cover;" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <strong>Hình ảnh minh chứng:</strong>
                                <div class="text-muted mt-2">Không có hình ảnh minh chứng.</div>
                            </div>
                        @endif

                        @if(config('app.debug'))
                            <script>
                                console.log('returnImages-{{ $order->id }} (decoded to array):', @json($returnImages));
                                console.log('returnImages-{{ $order->id }} (raw):', @json($order->return_image));
                            </script>
                        @endif
                    </div>
                    <!-- Footer -->
                    <div class="modal-footer justify-content-center">
                        <form action="{{ route('admin.handle.refund', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">Xác Nhận Hoàn Tiền</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection