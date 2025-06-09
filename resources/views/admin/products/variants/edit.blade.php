@extends('layouts.app')

@section('title', 'Product Variants')

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
                                <li class="breadcrumb-item"><a href="{{ route('admin.variants.index', $variant->product_id) }}">Product Variants</a></li>
                                <li class="breadcrumb-item active" aria-current="page"> Edit Product Variant</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('admin.variants.index', $variant->product_id) }}"
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
                <h4 class="fw-bold d-flex align-items-center">Update Product Variant</h4>
            </div>

            <!-- Form -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Basic Information</h5>
                        <form class="row g-3" action="{{ route('admin.variants.update', $variant->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="col-md-6 mb-3">
                                <label for="color_id" class="form-label fw-bold text-muted text-uppercase">Color</label>
                                <select id="color_id" name="color_id" class="form-select form-control choicesjs">
                                    <option value="">Select Color</option>
                                    @foreach ($colors as $color)
                                        <option value="{{ $color->id }}"
                                            @selected($color->id == $variant->color_id)>
                                            {{ $color->name }}</option>                
                                    @endforeach
                                </select>
                                @error('color_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="size_id" class="form-label fw-bold text-muted text-uppercase">Size</label>
                                <select id="size_id" name="size_id" class="form-select form-control choicesjs">
                                    <option value="">Select Size</option>
                                    @foreach ($sizes as $size)
                                        <option value="{{ $size->id }}"
                                            @selected($size->id == $variant->size_id)>
                                            {{ $size->name }}</option>                        
                                    @endforeach
                                </select>
                                @error('size_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label fw-bold text-muted text-uppercase">Price</label>
                                <input type="number" class="form-control" id="price" name="price" placeholder="Enter Price" value="{{ $variant->price }}">
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sale" class="form-label fw-bold text-muted text-uppercase">Sale</label>
                                <input type="number" class="form-control" id="sale" name="sale" placeholder="Enter Sale" value="{{ $variant->sale }}">
                                @error('sale')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label fw-bold text-muted text-uppercase">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter Quantity" value="{{ $variant->quantity }}">
                                @error('quantity')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="is_active" class="form-label fw-bold text-muted text-uppercase">Is_active</label>
                                <select id="is_active" name="is_active" class="form-select form-control choicesjs">
                                    <option value="">Select Active</option>
                                    @foreach ($is_actives as $is_active)
                                        <option value="{{ $is_active->id }}"
                                            @selected($is_active->id == $variant->is_active)>
                                            {{ $is_active->name }}</option>                        
                                    @endforeach
                                </select>
                                @error('is_active')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label fw-bold text-muted text-uppercase">Variant Image</label>
                                <input type="file" class="form-control" id="image" name="image">
                                @if($variant->image)
                                    <img src="{{ asset('storage/' . $variant->image) }}" alt="Product Variant Image"
                                        style="width: 120px; margin-top: 10px;">
                                @endif
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">Update Product Variant</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
