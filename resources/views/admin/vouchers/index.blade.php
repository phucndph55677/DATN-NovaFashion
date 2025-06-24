@extends('layouts.app')

@section('title', 'Voucher')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Header -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-schedule mb-4">
                    <div class="d-flex align-items-center">
                        <h4 class="fw-bold">Voucher</h4>
                    </div>
                    <div class="create-workform">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <!-- Search -->
                            <div class="modal-product-search d-flex flex-wrap">
                                <form class="me-3 position-relative">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="exampleInputText" placeholder="Search Voucher">
                                        <a class="search-link" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="" width="20"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </a>
                                    </div>
                                </form>

                                <!-- Add Button -->
                                <a href="{{ route('admin.vouchers.create') }}"
                                    class="btn btn-primary d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="20" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Voucher
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Table -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-block card-stretch">
                            <div class="card-body p-0">
                                <div class="d-flex justify-content-between align-items-center p-3 pb-md-0">
                                    <h5 class="fw-bold">Voucher List</h5>
                                    <button class="btn btn-secondary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="20" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Export
                                    </button>
                                </div>

                                <!-- Table -->
                                <div class="table-responsive iq-product-table">
                                    <table class="table data-table mb-0">
                                        <thead class="table-color-heading">
                                            <tr class="text-light">
                                                <th><label class="text-muted m-0">ID</label></th>
                                                <th><label class="text-muted mb-0">Voucher Code</label></th>
                                                <th><label class="text-muted mb-0">Sale Price(%)</label></th>
                                                <th><label class="text-muted mb-0">Max Discount(VND)</label></th>
                                                <th><label class="text-muted mb-0">Quantity</label></th>
                                                <th><label class="text-muted mb-0">Total User</label></th>
                                                <th><label class="text-muted mb-0">Status</label></th>
                                                <th class="text-start"><span class="text-muted">Action</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($vouchers as $voucher)
                                                <tr class="white-space-no-wrap">
                                                    <td>{{ $voucher->id }}</td>
                                                    <td>{{ $voucher->voucher_code }}</td>
                                                    <td>{{ $voucher->sale_price }}</td>
                                                    <td>{{ $voucher->max_discount }}</td>
                                                    <td>{{ $voucher->quantity }}</td>
                                                    <td>{{ $voucher->total_used }}</td>
                                                    <td>
                                                        <div>
                                                            <input type="checkbox" id="tr_fal_switch_{{ $voucher->id }}" class="checkboxs" {{ $voucher->status ? 'checked' : '' }} disabled />
                                                            <label for="tr_fal_switch_{{ $voucher->id }}" class="toggles text-white bg-success border-success">
                                                                <p class="texts ps-1">On &nbsp;&nbsp;Off</p>
                                                            </label>
                                                            {{-- <span class="badge {{ $voucher->status ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $voucher->status ? 'Đang hoạt động' : 'Không hoạt động' }}
                                                            </span> --}}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-start align-items-center">

                                                            <!-- Edit -->
                                                            <a class="" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Edit" href="{{ route('admin.vouchers.edit', $voucher->id) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="text-secondary me-4" width="20"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                </svg>
                                                            </a>

                                                            <!-- Delete -->
                                                            <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Bạn có chắc chắn muốn xoá voucher này không?');"
                                                                style="display: inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-icon text-danger"
                                                                    data-bs-toggle="tooltip" title="Delete">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            </form>
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