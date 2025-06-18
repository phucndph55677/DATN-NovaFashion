@extends('layouts.app')

@section('title', 'Orders')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-schedule mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="fw-bold">All Orders</h4>
                    </div>


                    <div class="create-workform">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="modal-product-search d-flex flex-wrap">
                                <form class="me-3 position-relative">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="exampleInputText"
                                            placeholder="Search Order" fdprocessedid="ev99ir">
                                        <a class="search-link" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="" width="20" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-block card-stretch">
                            <div class="card-body p-0">
                                <div class="d-flex justify-content-between align-items-center p-3 pb-0">
                                    <h5 class="fw-bold">Orders List</h5>

                                </div>

                                <div class="table-responsive iq-order-table">
                                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                        <div class="row dt-row">
                                            <div class="col-sm-12">
                                                <form method="GET" action="{{ route('admin.orders.index') }}">
                                                    <div class="row g-2 align-items-end px-3 py-2">

                                                        <!-- Trạng thái -->
                                                        <div class="col-md-2">
                                                            <label class="form-label ">Trạng thái</label>
                                                            <select name="status"
                                                                class="form-select form-select-sm rounded-pill shadow-sm"
                                                                onchange="this.form.submit()">
                                                                <option value="">-- Tất cả --</option>
                                                                @foreach ($statuses as $status)
                                                                    <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                                                                        {{ $status->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <!-- Lọc theo thời gian -->
                                                        <div class="col-md-2">
                                                            <label class="form-label ">Khoảng thời gian</label>
                                                            <select name="filter_by_time"
                                                                class="form-select form-select-sm rounded-pill shadow-sm"
                                                                onchange="this.form.submit()">
                                                                <option value="">-- Lựa chọn --</option>
                                                                <option value="week" {{ request('filter_by_time') == 'week' ? 'selected' : '' }}>Tuần này</option>
                                                                <option value="month" {{ request('filter_by_time') == 'month' ? 'selected' : '' }}>Tháng này</option>
                                                                <option value="year" {{ request('filter_by_time') == 'year' ? 'selected' : '' }}>Năm nay</option>
                                                            </select>
                                                        </div>

                                                        <!-- Ngày bắt đầu -->
                                                        <div class="col-md-2">
                                                            <label class="form-label">Từ ngày</label>
                                                            <input type="date" name="start_date"
                                                                class="form-control form-control-sm rounded-pill shadow-sm"
                                                                value="{{ request('start_date') }}">
                                                        </div>

                                                        <!-- Ngày kết thúc -->
                                                        <div class="col-md-2">
                                                            <label class="form-label">Đến ngày</label>
                                                            <input type="date" name="end_date"
                                                                class="form-control form-control-sm rounded-pill shadow-sm"
                                                                value="{{ request('end_date') }}">
                                                        </div>


                                                        <div class="col-md-2">
                                                            <label class="form-label invisible"
                                                                style="min-height: 20px;">Lọc</label>
                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary w-100 rounded-pill shadow-sm fw-semibold">
                                                                Lọc
                                                            </button>
                                                        </div>

                                                        <!-- Nút reset -->
                                                        <div class="col-md-2">
                                                            <label class="form-label invisible"
                                                                style="min-height: 20px;">Cài lại</label>
                                                            <a href="{{ route('admin.orders.index') }}"
                                                                class="btn btn-sm btn-secondary w-100 rounded-pill shadow-sm fw-semibold">
                                                                Cài lại
                                                            </a>
                                                        </div>
                                                    </div>
                                                </form>

                                                <table class="table data-table mb-0 dataTable no-footer"
                                                    id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">

                                                    <thead class="table-color-heading">
                                                        <tr class="text-light">
                                                            <th><label class="text-muted m-0">ID</label></th>
                                                            <th><label class="text-muted m-0">Order_Code</label></th>
                                                            <th><label class="text-muted mb-0">Name</label></th>
                                                            <th><label class="text-muted mb-0">Total</label></th>
                                                            <th><label class="text-muted mb-0">Date</label></th>
                                                            <th><label class="text-muted mb-0">Status</label></th>
                                                            <th class="text-start"><span class="text-muted">Action</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($orders as $order)
                                                            <tr class="white-space-no-wrap odd">
                                                                <td>{{ $order->id }}</td>
                                                                <td>{{ $order->order_code }}</td>
                                                                <td>{{ $order->name }}</td>
                                                                <td>{{ $order->total }}</td>
                                                                <td>{{ $order->created_at->format('d/m/Y')}}</td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-primary">{{ $order->orderStatus->name  }}</span>
                                                                </td>
                                                                <td style="text-align: center;">

                                                                    <a class="" data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="View"
                                                                        href="{{ route('admin.orders.show', $order->id) }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="text-secondary me-4" width="20" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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
            </div>
        </div>
    </div>
@endsection