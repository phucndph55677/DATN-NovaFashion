@extends('layouts.app')

@section('title', 'Banner')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Breadcrumb -->
            <div class="col-lg-12 mb-2">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="d-flex align-items-center justify-content-between">
                        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
                            aria-label="breadcrumb">
                            <ol class="breadcrumb ps-0 mb-0 pb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.banners.index') }}">Banner</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Banner</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('admin.banners.index') }}"
                        class="btn btn-primary btn-sm d-flex align-items-center justify-content-between ms-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ms-2">Back</span>
                    </a>
                </div>
                
                <!-- Title -->
                <div class="col-lg-12 mb-3 d-flex justify-content-between">
                    <h4 class="fw-bold d-flex align-items-center">New Banner</h4>
                </div>

                <!-- Form -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Basic Information</h5>
                                <form class="row g-3" action="{{ route('admin.banners.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf

                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-bold text-muted text-uppercase">Banner Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Banner Name" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="product_link" class="form-label fw-bold text-muted text-uppercase">Product Link</label>
                                        <input type="text" class="form-control" id="product_link" name="product_link" placeholder="https://example.com/san-pham/abc"
                                            value="{{ old('product_link') }}">
                                        @error('product_link')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="location_id" class="form-label fw-bold text-muted text-uppercase">Location</label>
                                        <select id="location_id" name="location_id" class="form-select form-control choicesjs">
                                            <option value="">Select Location</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('location_id')
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
                                        <label for="image" class="form-label fw-bold text-muted text-uppercase">Banner Image</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div> 

                                    <!-- Submit -->
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary">Create Banner</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 
