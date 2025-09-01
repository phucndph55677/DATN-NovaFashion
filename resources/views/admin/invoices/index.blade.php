@extends('admin.layouts.app')

@section('title', 'Hóa Đơn')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Header -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-schedule mb-4">
                    <div class="d-flex align-items-center">
                        <h4 class="fw-bold">Hóa Đơn</h4>
                    </div>
                    <div class="create-workform">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <!-- Search -->
                            {{-- <div class="modal-product-search d-flex flex-wrap">
                                <form class="me-3 position-relative">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="exampleInputText" placeholder="Tìm kiếm voucher...">
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
                                <div class="d-flex justify-content-between align-items-center p-3 pb-md-0">
                                    <h5 class="fw-bold">Danh Sách Hóa Đơn</h5>
                                </div>

                                <!-- Table -->
                                <div class="table-responsive iq-product-table">
                                    <table class="table data-table mb-0">
                                        <thead class="table-color-heading">
                                            <tr class="text-light">
                                                <th><label class="text-muted m-0">ID</label></th>
                                                <th><label class="text-muted mb-0">Mã Hóa Đơn</label></th>
                                                <th><label class="text-muted mb-0">Tên Khách Hàng</label></th>
                                                <th><label class="text-muted mb-0">Tổng Tiền</label></th>
                                                <th><label class="text-muted mb-0">Ngày Tạo</label></th>
                                                <th><label class="text-muted mb-0">Ngày Xuất</label></th>
                                                <th class="text-start"><span class="text-muted">Hành Động</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoices as $invoice)
                                                <tr class="white-space-no-wrap">
                                                    <td>{{ $invoice->id }}</td>
                                                    <td>{{ $invoice->invoice_code }}</td>
                                                    <td>{{ $invoice->order->name ?? 'N/A' }}</td>
                                                    <td>{{ number_format($invoice->order->total_amount, 0, ',', '.') }} VND</td>
                                                    <td>{{ $invoice->created_at->format('d/m/Y H:i:s') }}</td>
                                                    <td>{{ $invoice->issue_date ? $invoice->issue_date->format('d/m/Y H:i:s') : 'Chưa xuất' }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-start align-items-center">

                                                            <!-- Xem -->
                                                            <a class="" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Xem" href="{{ route('admin.invoices.show', $invoice->id) }}">
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

                                                            <!-- In -->
                                                            <a class="" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="In" href="{{ route('admin.invoices.print', $invoice->id) }}" target="_blank">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="text-secondary" width="20" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div> <!-- End table -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection