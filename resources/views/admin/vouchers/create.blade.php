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
                        <form action="{{ route('admin.vouchers.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <!-- Name -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-bold text-muted text-uppercase">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Voucher Name" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Voucher Code -->
                                    <div class="mb-3">
                                        <label for="voucher_code" class="form-label fw-bold text-muted text-uppercase">Voucher Code</label>
                                        <input type="text" class="form-control" id="voucher_code" name="voucher_code" placeholder="Enter Voucher Code" value="{{ old('voucher_code') }}">
                                        @error('voucher_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Quantity -->
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label fw-bold text-muted text-uppercase">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter Quantity" value="{{ old('quantity') }}" min="1">
                                        @error('quantity')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Sale Price -->
                                    <div class="mb-3">
                                        <label for="sale_price" class="form-label fw-bold text-muted text-uppercase">Sale Price (%)</label>
                                        <input type="number" class="form-control" id="sale_price" name="sale_price" placeholder="Enter Sale Price" value="{{ old('sale_price') }}">
                                        @error('sale_price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Min Price -->
                                    <div class="mb-3">
                                        <label for="min_price" class="form-label fw-bold text-muted text-uppercase">Min Price (VNĐ)</label>
                                        <input type="text" class="form-control" id="min_price" name="min_price" placeholder="Enter Min Price" value="{{ old('min_price') ? number_format(old('min_price'), 0, ',', '.') : '' }}" onkeyup="formatCurrency(this)">
                                        @error('min_price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <!-- Max Price -->
                                    <div class="mb-3">
                                        <label for="max_price" class="form-label fw-bold text-muted text-uppercase">Max Price (VNĐ)</label>
                                        <input type="text" class="form-control" id="max_price" name="max_price" placeholder="Enter Max Price" value="{{ old('max_price') ? number_format(old('max_price'), 0, ',', '.') : '' }}" onkeyup="formatCurrency(this)">
                                        @error('max_price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Start Date --> 
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label fw-bold text-muted text-uppercase">Start Date</label>
                                        <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                                        @error('start_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- End Date -->
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label fw-bold text-muted text-uppercase">End Date</label>
                                        <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                                        @error('end_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Apply to Role -->
                                    <div class="mb-3">
                                        <label for="role_id" class="form-label fw-bold text-muted text-uppercase">Apply to Role</label>
                                        <select class="form-select" id="role_id" name="role_id">
                                            <option value="">Select Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
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

<script>
function formatCurrency(input) {
    // Remove all non-digit characters
    let value = input.value.replace(/\D/g, '');
    
    // Format the number with thousand separators
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    
    // Update the input value
    input.value = value;
}

// Add event listeners when the document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Format initial values for min_price and max_price
    const inputsToFormat = ['min_price', 'max_price'];
    inputsToFormat.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            formatCurrency(input);
        }
    });
});
</script>
