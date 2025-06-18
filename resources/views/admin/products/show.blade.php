@extends('layouts.app')

@section('title', 'Product')

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
                                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Product</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Show Product</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('admin.products.index') }}"
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
                <h4 class="fw-bold d-flex align-items-center">Detail Product</h4>
            </div>

            <!-- Form -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Product Name: {{ $product->name }}</h5>
                        <hr>
                        <div class="row">
                            <div class="col-12 col-sm-5">
                                <div class="col-12">
                                    <img style="width: 450px; height: 450px" class="avatar rounded" alt="user-icon" src="{{ asset('storage/' . $product->image) }}">
                                </div>
                                <div class="col-12 product-image-thumbs mt-3">
                                    <img style="width: 100px; height: 100px; margin-right: 10px;" class="avatar rounded" alt="user-icon" src="{{ asset('storage/' . $product->image) }}">
                                    <img style="width: 100px; height: 100px; margin-right: 10px;" class="avatar rounded" alt="user-icon" src="{{ asset('storage/' . $product->image) }}">
                                    <img style="width: 100px; height: 100px; margin-right: 10px;" class="avatar rounded" alt="user-icon" src="{{ asset('storage/' . $product->image) }}">
                                    <img style="width: 100px; height: 100px;" class="avatar rounded" alt="user-icon" src="{{ asset('storage/' . $product->image) }}">
                                </div>


                            </div>

                            <div class="col-12 col-sm-7">
                                <div class="col-12">
                                    <h5>Product Variant:</h5>
                                    <!-- Table -->
                                    <table class="table mb-2">
                                        <thead class="table-color-heading">
                                            <tr class="text-light">
                                                <th><label class="text-muted mb-0">Image</label></th>
                                                <th><label class="text-muted mb-0">Color</label></th>
                                                <th><label class="text-muted mb-0">Size</label></th>
                                                <th><label class="text-muted mb-0">Quantity</label></th>
                                                <th><label class="text-muted mb-0">Price</label></th>
                                                <th><label class="text-muted mb-0">Sale</label></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->variants as $variant)
                                                <tr class="white-space-no-wrap">
                                                    <td >
                                                        <img style="width: 50px; height: 50px" class="avatar rounded" alt="user-icon" src="{{ asset('storage/' . $variant->image) }}">   
                                                    </td>
                                                    <td>{{ $variant->color->name ?? 'Không có' }}</td>
                                                    <td>{{ $variant->size->name ?? 'Không có' }}</td>
                                                    <td>{{ $variant->quantity }}</td>
                                                    <td>{{ $variant->price }}</td>
                                                    <td>{{ $variant->sale }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <h5 class="mt-2">Date of entry: <small>{{ $product->created_at->format('d/m/Y') }}</small></h5>
                                    <h5 class="mt-2">Material: <small>{{ $product->material ?? 'Không có' }}</small></h5>
                                    <h5 class="mt-2">Active: <small>{{ $product->onpage ? 'Đang bán' : 'Dừng bán' }}</small></h5>
                                    <h5 class="mt-2">Category: <small>{{ $product->category->name }}</small></h5>                                                   
                                    <h5 class="mt-2">Description: <small>{{ $product->description }}</small></h5>
                                </div>                              
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <hr>
                        <h5 class="fw-bold mb-3">Review List</h5>
                        <!-- Card Table -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-block card-stretch">
                                    <div class="card-body p-0">                           

                                        <!-- Table -->
                                        <div class="table-responsive iq-product-table">
                                            <table class="table data-table mb-0">
                                                <thead class="table-color-heading">
                                                    <tr class="text-light">
                                                        <th><label class="text-muted m-0">ID</label></th>
                                                        <th><label class="text-muted mb-0">Reviewer</label></th>
                                                        <th><label class="text-muted mb-0">Rating</label></th>
                                                        <th><label class="text-muted mb-0">Content</label></th>
                                                        <th><label class="text-muted mb-0">Review Date</label></th>
                                                        <th><label class="text-muted mb-0">Status</label></th>
                                                        <th class="text-start"><span class="text-muted">Action</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($reviews as $review)
                                                        <tr class="white-space-no-wrap">
                                                            <td>{{ $review->id }}</td>
                                                            <td>{{ $review->user->name ?? 'N/A' }}</td>                                                       
                                                            <td>
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= $review->rating)
                                                                        <span style="color: gold; font-size: 20px;">&#9733;</span>
                                                                    @else
                                                                        <span style="color: #ccc; font-size: 20px;">&#9733;</span>
                                                                    @endif
                                                                @endfor
                                                            </td>
                                                            <td style="white-space: normal; word-break: break-word; max-width: 300px;">
                                                                {{ $review->content }}
                                                            </td>                                                         
                                                            <td>{{ $review->created_at->format('d/m/Y') }}</td>
                                                            <td>{{ $review->status == 1 ? 'Hiển thị' : 'Bị ẩn' }}</td>
                                                            <td>
                                                                <form action="{{ route('admin.products.toggle', $review->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit"
                                                                            onclick="return confirm('Bạn có muốn {{ $review->status == 1 ? 'ẩn' : 'bỏ ẩn' }} đánh giá này không?')"
                                                                            class="btn btn-sm {{ $review->status == 1 ? 'btn-danger' : 'btn-success' }} rounded-pill px-3 shadow-sm">
                                                                        {{ $review->status == 1 ? 'Ẩn' : 'Bỏ ẩn' }}
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.product-image-thumb').on('click', function() {
                var $image_element = $(this).find('img')
                $('.product-image').prop('src', $image_element.attr('src'))
                $('.product-image-thumb.active').removeClass('active')
                $(this).addClass('active')
            })
        })
    </script>
@endsection