@extends('admin.layouts.app')

@section('title', 'Sản Phẩm')

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
                                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản Phẩm</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Thêm Sản Phẩm</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('admin.products.index') }}"
                        class="btn btn-primary btn-sm d-flex align-items-center justify-content-between ms-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ms-2">Quay Lại</span>
                    </a>
                </div>
                
                <!-- Title -->
                <div class="col-lg-12 mb-3 d-flex justify-content-between">
                    <h4 class="fw-bold d-flex align-items-center">Sản Phẩm Mới</h4>
                </div>

                <!-- Form -->
                <div class="row">
                    <!-- Form bên trái -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Thông Tin Cơ Bản</h5>
                                <form class="row g-3" action="{{ route('admin.products.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf

                                    @php
                                        if (!function_exists('renderCategoryOptions')) {
                                            function renderCategoryOptions($categories, $parent_id = null, $prefix = '', $selected = null) {
                                                foreach ($categories->where('parent_id', $parent_id) as $category) {
                                                    $hasChildren = $categories->where('parent_id', $category->id)->count() > 0;

                                                    echo '<option value="'.$category->id.'" '
                                                        .($hasChildren ? 'disabled' : '').' '
                                                        .($selected == $category->id ? 'selected' : '').'>'
                                                        .$prefix.$category->name
                                                        .'</option>';

                                                    // đệ quy cho danh mục con
                                                    renderCategoryOptions($categories, $category->id, $prefix.'— ', $selected);
                                                }
                                            }
                                        }
                                    @endphp

                                    <div class="col-md-6 mb-3">
                                        <label for="product_code" class="form-label fw-bold text-muted text-uppercase">Mã Sản Phẩm</label>
                                        <input type="text" class="form-control" id="product_code" name="product_code" placeholder="Nhập Mã Sản Phẩm" value="{{ old('product_code') }}">
                                        @error('product_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-bold text-muted text-uppercase">Tên Sản Phẩm</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Nhập Tên Sản Phẩm" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="category_id" class="form-label fw-bold text-muted text-uppercase">Danh Mục</label>
                                        <select id="category_id" name="category_id" class="form-select form-control">
                                            <option value="">— — Chọn Danh Mục — —</option>
                                            @php
                                                renderCategoryOptions($categories, null, '', old('category_id'));
                                            @endphp
                                        </select>
                                        @error('category_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="material" class="form-label fw-bold text-muted text-uppercase">Chất Liệu</label>
                                        <input type="text" class="form-control" id="material" name="material" placeholder="Nhập Chất Liệu" value="{{ old('material') }}">
                                        @error('material')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="album" class="form-label fw-bold text-muted text-uppercase">Album Ảnh</label>
                                        <input type="file" class="form-control" id="album" name="album[]" multiple>
                                        @error('album.*')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="onpage" class="form-label fw-bold text-muted text-uppercase">Hiển Thị</label>
                                        <select id="onpage" name="onpage" class="form-select form-control choicesjs">
                                            <option value="">Chọn Hiển Thị</option>
                                                <option value="1" {{ old('onpage') == '1' ? 'selected' : '' }}>Có</option>
                                                <option value="0" {{ old('onpage') == '0' ? 'selected' : '' }}>Không
                                            </option>
                                        </select>
                                        @error('onpage')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="image" class="form-label fw-bold text-muted text-uppercase">Hình Ảnh Sản Phẩm</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                        @error('image')
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
                                        <button type="submit" class="btn btn-primary">Thêm Sản Phẩm</button>
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
