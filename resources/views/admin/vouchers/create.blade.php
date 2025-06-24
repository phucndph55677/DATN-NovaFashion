@extends('layouts.app')

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
                                <li class="breadcrumb-item active" aria-current="page">Add Voucher</li>
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
                        <span class="ms-2">Back</span>
                    </a>
                </div>
            </div>

            <!-- Title -->
            <div class="col-lg-12 mb-3 d-flex justify-content-between">
                <h4 class="fw-bold d-flex align-items-center">New Voucher</h4>
            </div>

            <!-- Form -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Basic Information</h5>
                        <form class="row g-3" action="{{ route('admin.vouchers.store') }}" method="POST">
                            @csrf
                         
                            <div class="col-md-6 mb-3">
                                <label for="voucher_code" class="form-label fw-bold text-muted text-uppercase">Voucher Code</label>
                                <input type="text" class="form-control" id="voucher_code" name="voucher_code" placeholder="Enter Voucher Code" value="{{ old('voucher_code') }}">
                                @error('voucher_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sale_price" class="form-label fw-bold text-muted text-uppercase">Sale Price (%)</label>
                                <input type="number" class="form-control" id="sale_price" name="sale_price" placeholder="Enter Sale Price" value="{{ old('sale_price') }}">
                                @error('sale_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_discount" class="form-label fw-bold text-muted text-uppercase">Max Discount (VND)</label>
                                <input type="number" class="form-control" id="max_discount" name="max_discount" placeholder="Enter Max Discount" VNDalue="{{ old('max_discount') }}">
                                @error('max_discount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="min_price" class="form-label fw-bold text-muted text-uppercase">Min Price (VNĐ)</label>
                                <input type="number" class="form-control" id="min_price" name="min_price" placeholder="Enter Min Price" value="{{ old('min_price') }}">
                                @error('min_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label fw-bold text-muted text-uppercase">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter Quantity" value="{{ old('quantity') }}" min="1">
                                @error('quantity')
                                       <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="user_limit" class="form-label fw-bold text-muted text-uppercase">User Limit</label>
                                <input type="number" class="form-control" id="user_limit" name="user_limit" placeholder="Enter User Limit" value="{{ old('user_limit') }}" min="1">
                                @error('user_limit')
                                       <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
 
                           <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label fw-bold text-muted text-uppercase">Start Date</label>
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date" placeholder="Enter Start Date" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label fw-bold text-muted text-uppercase">End Date</label>
                                <input type="datetime-local" class="form-control" id="end_date" name="end_date" placeholder="Enter End Date" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label fw-bold text-muted text-uppercase">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="4" placeholder="Enter Description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold text-muted text-uppercase">Status</label>
                                <select id="status" name="status" class="form-select form-control choicesjs">
                                    <option value="">Select status</option>
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>On</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Off
                                    </option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <!-- Submit -->
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">Create Voucher</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
