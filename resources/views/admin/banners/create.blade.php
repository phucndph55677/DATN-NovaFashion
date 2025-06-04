@extends('layouts.app')
@section('title','Add Banner')
@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between">
    <div class="d-flex align-items-center justify-content-between">
        <nav style="--bs-breadcrumb-divider: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&quot;);" aria-label="breadcrumb">
            <ol class="breadcrumb ps-0 mb-0 pb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.banners.index') }}">Banners</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Banner</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.banners.index') }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-between ms-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
        </svg>
        <span class="ms-2">Back</span>
    </a>
</div>

<div class="col-lg-12 mb-3 d-flex justify-content-between">
    <h4 class="fw-bold d-flex align-items-center">New Banner</h4>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Banner Information</h5>
            <form class="row g-3" method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="col-md-6 mb-3">
                    <label for="description" class="form-label fw-bold text-muted text-uppercase">Description</label>
                    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Enter banner description" value="{{ old('description') }}" required>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label fw-bold text-muted text-uppercase">Banner Image</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="image" required>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label fw-bold text-muted text-uppercase">Start Date & Time</label>
                    <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label fw-bold text-muted text-uppercase">End Date & Time</label>
                    <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" value="{{ old('end_date') }}" required>
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label fw-bold text-muted text-uppercase">Status</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">Create Banner</button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
