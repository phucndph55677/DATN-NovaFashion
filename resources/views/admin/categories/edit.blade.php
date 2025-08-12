@extends('admin.layouts.app')

@section('title', 'Danh Mục')

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
                                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Danh Mục</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Sửa Danh Mục</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('admin.categories.index') }}"
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
                <h4 class="fw-bold d-flex align-items-center">Cập Nhật Danh Mục</h4>
            </div>

            <!-- Form -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Thông Tin Cơ Bản</h5>
                        <form class="row g-3" action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Category Name -->
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label fw-bold text-muted text-uppercase">Tên Danh Mục</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nhập Tên Danh Mục" value="{{ $category->name }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Parent Category -->
                            <div class="mb-3">
                                <label for="parent_id" class="form-label fw-bold text-muted text-uppercase">Danh Mục Cha</label>
                                <select id="parent_id" name="parent_id" class="form-select form-control">
                                    <option value="">— — Chọn Danh Mục Cha — —</option>

                                    @php
                                        // Hàm đệ quy hiển thị option
                                        $invalidIds = $invalidIds ?? [];

                                        $renderCategoryOptions = function($cat, $prefix = '') use (&$renderCategoryOptions, $category, $invalidIds) {
                                            $disabled = in_array($cat->id, $invalidIds) ? 'disabled' : '';
                                            $selected = old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '';

                                            echo "<option value='{$cat->id}' {$disabled} {$selected}>";
                                            echo $prefix . e($cat->name);
                                            echo '</option>';

                                            if ($cat->childrenRecursive && $cat->childrenRecursive->count()) {
                                                foreach ($cat->childrenRecursive as $child) {
                                                    $renderCategoryOptions($child, $prefix . '— ');
                                                }
                                            }
                                        };
                                    @endphp

                                    @foreach ($categories as $cat)
                                        {!! $renderCategoryOptions($cat) !!}
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label fw-bold text-muted text-uppercase">Mô Tả</label>
                                <textarea class="form-control" name="description" id="description" rows="4" placeholder="Nhập Mô Tả">{{ $category->description }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-primary">Cập Nhật Danh Mục</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection