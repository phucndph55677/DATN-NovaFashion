@extends('admin.layouts.app')

@section('title', 'Voucher')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Breadcrumb -->
            <div class="col-lg-12 mb-2">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="d-flex align-items-center justify-content-between">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb ps-0 mb-0 pb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Voucher</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Thêm Voucher</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('admin.vouchers.index') }}"
                        class="btn btn-primary btn-sm d-flex align-items-center justify-content-between ms-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ms-2">Quay Lại</span>
                    </a>
                </div>
            </div>

            <!-- Title -->
            <div class="col-lg-12 mb-3 d-flex justify-content-between">
                <h4 class="fw-bold d-flex align-items-center">Voucher Mới</h4>
            </div>

            <!-- Form -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Thông Tin Cơ Bản</h5>
                        <form class="row g-3" action="{{ route('admin.vouchers.store') }}" method="POST">
                            @csrf
                         
                            <div class="col-md-6 mb-3">
                                <label for="voucher_code" class="form-label fw-bold text-muted text-uppercase">Mã Voucher</label>
                                <input type="text" class="form-control" id="voucher_code" name="voucher_code" placeholder="Nhập Mã Voucher" value="{{ old('voucher_code') }}">
                                @error('voucher_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sale_price" class="form-label fw-bold text-muted text-uppercase">Giảm Giá (VND)</label>
                                <input type="number" class="form-control" id="sale_price" name="sale_price" placeholder="Nhập Giảm Giá (VND)" value="{{ old('sale_price') }}">
                                @error('sale_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="min_order_value" class="form-label fw-bold text-muted text-uppercase">Giá Trị Đơn Hàng Tối Thiểu (VND)</label>
                                <input type="number" class="form-control" id="min_order_value" name="min_order_value" placeholder="Giá Trị Đơn Hàng Tối Thiểu (VND)" value="{{ old('min_order_value') }}">
                                @error('min_order_value')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label fw-bold text-muted text-uppercase">Số Lượng</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Nhập Số Lượng" value="{{ old('quantity') }}" min="1">
                                @error('quantity')
                                       <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="user_limit" class="form-label fw-bold text-muted text-uppercase">Số Lượt Mỗi Người Dùng</label>
                                <input type="number" class="form-control" id="user_limit" name="user_limit" placeholder="Nhập Số Lượt Mỗi Người Dùng" value="{{ old('user_limit') }}" min="1">
                                @error('user_limit')
                                       <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold text-muted text-uppercase">Trạng Thái</label>
                                <select id="status" name="status" class="form-select form-control choicesjs">
                                    <option value="">Chọn Trạng Thái</option>
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hiện</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn
                                    </option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
 
                           <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label fw-bold text-muted text-uppercase">Bắt Đầu</label>
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date" placeholder="Chọn Bắt Đầu" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label fw-bold text-muted text-uppercase">Kết Thúc</label>
                                <input type="datetime-local" class="form-control" id="end_date" name="end_date" placeholder="Chọn Kết Thúc" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label fw-bold text-muted text-uppercase">Mô Tả</label>
                                <textarea class="form-control" name="description" id="description" rows="4" placeholder="Nhập Mô Tả">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <!-- Submit -->
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">Thêm Voucher</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
