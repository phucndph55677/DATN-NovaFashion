@extends('admin.layouts.app')

@section('title', 'Biến Thể')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Header -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-schedule mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="fw-bold">Biến Thể</h4>
                    </div>
                    <div class="create-workform">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <!-- Search -->
                            <div class="modal-product-search d-flex flex-wrap">
                                <!-- Add Button -->
                                <a href="{{ route('admin.variants.create', $product->id) }}"
                                    class="btn btn-primary position-relative d-flex align-items-center justify-content-between">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="20" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Thêm Biến Thể
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Table -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-block card-stretch">
                            <div class="card-body p-0">
                                <div class="d-flex justify-content-between align-items-center p-3 pb-md-0">
                                    <ol class="breadcrumb ps-0 mb-0 pb-0">
                                        <h5><a href="{{ route('admin.products.index') }}">Danh Sách Sản Phẩm</a> > </h5>
                                        <h5 class="fw-bold">Biến Thể Của: {{ $product->name }}</h5>
                                    </ol>
                                </div>

                                <!-- Table -->
                                <div class="table-responsive iq-product-table">
                                    <table class="table data-table mb-0">
                                        <thead class="table-color-heading">
                                            <tr class="text-light">
                                                <th><label class="text-muted m-0">ID</label></th>
                                                <th><label class="text-muted m-0">Hình Ảnh</label></th>
                                                <th><label class="text-muted m-0">Giá</label></th>
                                                <th><label class="text-muted m-0">Khuyến Mãi</label></th>
                                                <th><label class="text-muted m-0">Màu sắc</label></th>
                                                <th><label class="text-muted m-0">Kích Cỡ</label></th>
                                                <th><label class="text-muted m-0">Số Lượng</label></th>
                                                <th><label class="text-muted m-0">Trạng Thái</label></th>
                                                <th class="text-start"><span class="text-muted">Hành Động</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->variants as $variant)
                                                <tr class="white-space-no-wrap">
                                                    <td>{{ $variant->id }}</td>
                                                    <td class="">
                                                        <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                            <div class="h-avatar is-medium">
                                                                <img class="avatar rounded" alt="user-icon"
                                                                    src="{{ asset('storage/' . $variant->image) }}">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $variant->price }}</td>
                                                    <td>{{ $variant->sale }}</td>
                                                    <td>{{ $variant->color->name }}</td>
                                                    <td>{{ $variant->size->name }}</td>
                                                    <td>{{ $variant->quantity }}</td>
                                                    <td>
                                                        <div>
                                                            <input type="checkbox" id="tr_fal_switch_{{ $variant->id }}" class="checkboxs" {{ $variant->status ? 'checked' : '' }} disabled />
                                                            <label for="tr_fal_switch_{{ $variant->id }}" class="toggles text-white bg-success border-success"> 
                                                                <p class="texts ps-1">On &nbsp;&nbsp;Off</p>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-start align-items-center">

                                                            <!-- Edit -->
                                                            <a class="" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Sửa" href="{{ route('admin.variants.edit', $variant->id) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="text-secondary me-4" width="20"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                </svg>
                                                            </a>

                                                            <!-- Delete -->
                                                            {{-- <form action="{{ route('admin.variants.destroy', $variant->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Bạn có chắc chắn muốn xoá biến thể này không?');"
                                                                style="display: inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-icon text-danger"
                                                                    data-bs-toggle="tooltip" title="Xóa">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            </form> --}}
                                                        </div>
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
@endsection
