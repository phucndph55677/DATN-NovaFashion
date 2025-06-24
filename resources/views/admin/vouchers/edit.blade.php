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
                                <li class="breadcrumb-item active" aria-current="page">Edit Voucher</li>
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
                <h4 class="fw-bold d-flex align-items-center">Update Voucher</h4>
            </div>

            <!-- Form -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Basic Information</h5>
                            <form class="row g-3" action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                         
                            <div class="col-md-6 mb-3">
                                <label for="voucher_code" class="form-label fw-bold text-muted text-uppercase">Voucher Code</label>
                                <input type="text" class="form-control" id="voucher_code" name="voucher_code" placeholder="Enter Voucher Code" value="{{ $voucher->voucher_code }}">
                                @error('voucher_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sale_price" class="form-label fw-bold text-muted text-uppercase">Sale Price (%)</label>
                                <input type="number" class="form-control" id="sale_price" name="sale_price" placeholder="Enter Sale Price" value="{{ $voucher->sale_price }}">
                                @error('sale_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_discount" class="form-label fw-bold text-muted text-uppercase">Max Discount (VND)</label>
                                <input type="number" class="form-control" id="max_discount" name="max_discount" placeholder="Enter Max Discount" Value="{{ $voucher->max_discount }}">
                                @error('max_discount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="min_price" class="form-label fw-bold text-muted text-uppercase">Min Price (VNĐ)</label>
                                <input type="number" class="form-control" id="min_price" name="min_price" placeholder="Enter Min Price" value="{{ $voucher->min_price }}">
                                @error('min_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label fw-bold text-muted text-uppercase">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter Quantity" value="{{ $voucher->quantity }}">
                                @error('quantity')
                                       <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="user_limit" class="form-label fw-bold text-muted text-uppercase">User Limit</label>
                                <input type="number" class="form-control" id="user_limit" name="user_limit" placeholder="Enter User Limit" value="{{ $voucher->user_limit }}">
                                @error('user_limit')
                                       <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
 
                           <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label fw-bold text-muted text-uppercase">Start Date</label>
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date" placeholder="Enter Start Date" value="{{ $voucher->start_date }}">
                                @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label fw-bold text-muted text-uppercase">End Date</label>
                                <input type="datetime-local" class="form-control" id="end_date" name="end_date" placeholder="Enter End Date" value="{{ $voucher->end_date }}">
                                @error('end_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label fw-bold text-muted text-uppercase">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="4" placeholder="Enter Description">{{ $voucher->description }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold text-muted text-uppercase">Status</label>
                                <select id="status" name="status" class="form-select form-control choicesjs">
                                    <option value="">Select Status</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}"
                                                @selected($status->id == $voucher->status)>
                                                {{ $status->name }}</option>      
                                        @endforeach
                                    </option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <!-- Submit -->
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">Update Voucher</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
