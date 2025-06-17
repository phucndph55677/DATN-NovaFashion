@extends('layouts.app')

@section('title', 'Seller Manage')

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
                                <li class="breadcrumb-item"><a href="{{ route('admin.accounts.seller-manage.index') }}">Seller</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Seller</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('admin.accounts.seller-manage.index') }}"
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
                <h4 class="fw-bold d-flex align-items-center">Update Seller</h4>
            </div>

            <!-- Form -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Basic Information</h5>
                        <form class="row g-3" action="{{ route('admin.accounts.seller-manage.update', $seller->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                                                        
                           <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold text-muted text-uppercase">Seller Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Seller Name" value="{{ $seller->name }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold text-muted text-uppercase">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{ $seller->email }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-bold text-muted text-uppercase">Phone</label>
                                <input type="number" class="form-control" id="phone" name="phone" placeholder="Enter Phone" value="{{ $seller->phone }}">
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold text-muted text-uppercase">Password</label>
                                <input type="text" class="form-control" id="password" name="password" placeholder="Enter Password">
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold text-muted text-uppercase">Status</label>
                                <select id="status" name="status" class="form-select form-control choicesjs">
                                    <option value="">Select Status</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}"
                                            @selected($status->id == $seller->status)>
                                            {{ $status->name }}</option>                        
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label fw-bold text-muted text-uppercase">`Seller Image</label>
                                <input type="file" class="form-control" id="image" name="image">
                                @if($seller->image)
                                    <img src="{{ asset('storage/' . $seller->image) }}" alt="Seller Image"
                                        style="width: 120px; margin-top: 10px;">
                                @endif
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label fw-bold text-muted text-uppercase">Address</label>
                                <textarea class="form-control" name="address" id="address" rows="4" placeholder="Enter Address">{{ $seller->address }}</textarea>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">Update Seller</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
