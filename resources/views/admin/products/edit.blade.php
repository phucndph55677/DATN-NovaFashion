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
                                <li class="breadcrumb-item active" aria-current="page">Sửa Sản Phẩm</li>
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
                    <h4 class="fw-bold d-flex align-items-center">Cập Nhật Sản Phẩm</h4>
                </div>

                <!-- Form -->
                <div class="row">
                    <!-- Form bên trái -->
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Thông Tin Cơ Bản</h5>
                                <form class="row g-3" method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="product_code" class="form-label fw-bold text-muted text-uppercase">Mã Sản Phẩm</label>
                                        <input type="text" class="form-control" id="product_code" name="product_code" placeholder="Nhập Mã Sản Phẩm" value="{{ $product->product_code }}">
                                        @error('product_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-bold text-muted text-uppercase">Tên Sản Phẩm</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Nhập Tên Sản Phẩm" value="{{ $product->name }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="category_id" class="form-label fw-bold text-muted text-uppercase">Danh Mục</label>
                                        <select id="category_id" name="category_id" class="form-select form-control choicesjs">
                                            <option value="">Chọn Danh Mục</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    @selected($category->id == $product->category_id)>
                                                    {{ $category->name }}</option>                        
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="material" class="form-label fw-bold text-muted text-uppercase">Chất Liệu</label>
                                        <input type="text" class="form-control" id="material" name="material" placeholder="Nhập Chất Liệu" value="{{ $product->material }}">
                                        @error('material')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-bold text-muted text-uppercase">Mô Tả</label>
                                        <textarea class="form-control" name="description" id="description" rows="4" placeholder="Nhập Mô Tả">{{ $product->description }}</textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="onpage" class="form-label fw-bold text-muted text-uppercase">Trên Trang</label>
                                        <select id="onpage" name="onpage" class="form-select form-control choicesjs">
                                            <option value="">Chọn Trên Trang</option>
                                            @foreach ($onpages as $onpage)
                                                <option value="{{ $onpage->id }}"
                                                    @selected($onpage->id == $product->onpage)>
                                                    {{ $onpage->name }}</option>                        
                                            @endforeach
                                        </select>
                                        @error('onpage')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label fw-bold text-muted text-uppercase">Hình Ảnh Sản Phẩm</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="Hình Ảnh Sản Phẩm"
                                                style="width: 120px; margin-top: 10px;">
                                        @endif
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Submit -->
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary">Cập Nhật Sản Phẩm</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin phụ hoặc album bên phải -->
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h5 class="fw-bold mb-3">Album Ảnh Sản Phẩm</h5>
                                </div>
                                <span class="float-end mb-3 me-2">
                                    <button onclick="addfaqs();" type="button" class="btn btn-sm bg-primary"><i
                                            class="ri-add-fill">
                                            <span class="pl-1">Thêm Mới</span></i>
                                    </button>
                                </span>
                            </div>
                            <div class="card-body">
                                <div id="faqs" class="table-editable">
                                    <table class="table table-bordered table-responsive-md table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>Hình Ảnh</th>
                                                <th>Tệp</th>
                                                <th>Xóa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="hide">
                                                {{-- <td contenteditable="true">Oregon</td>
                                                <td contenteditable="true">Oregon</td>
                                                <td>
                                                    <span class="">
                                                        <button type="button" class="btn btn-danger-subtle rounded btn-sm my-0">Remove</button>
                                                    </span>
                                                </td> --}}
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Submit -->
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary">Cập Nhật Album</button>
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
        let faqs_row = 0;

        function addfaqs() {
            html = '<tr id="faqs-row-' + faqs_row + '">';
            html +=
                '<td><img src="https://i.pinimg.com/564x/59/36/69/5936698bace4c5852463a2581e890bec.jpg" style="width: 50px; height: 50px;" alt=""></td>';
            html += '<td><input type="file" name="img_array[]" class="form-control"></td>';
            // html += '<td class="mt-10"><button type="button" class="badge badge-danger" onclick="removeRow('+ faqs_row + ', null);">Delete</button></td>';
            html +=
                '<td class="mt-10"><button type="button" class="btn btn-danger-subtle rounded btn-sm my-0">Remove</button></td>';

            html += '</tr>';

            $('#faqs tbody').append(html);

            faqs_row++;
        }
    </script>
@endsection
