@extends('layouts.app')

@section('title', 'Product')
@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-schedule mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="fw-bold">Products</h4>
        </div>
        <div class="create-workform">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="modal-product-search d-flex flex-wrap">
                    <form class="me-3 position-relative">
                        <div class="form-group mb-0">
                            <input type="text" class="form-control" id="exampleInputText" placeholder="Search Customer"
                                fdprocessedid="a9ntm">
                            <a class="search-link" href="#">
                                <svg xmlns="" class="" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </form>
                    <a href="{{ route('admin.products.create') }}"
                        class="btn btn-primary position-relative d-flex align-items-center justify-content-between">
                        <svg xmlns="" class="me-2" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Product
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-block card-stretch">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-3 pb-md-0">
                        <h5 class="fw-bold">Product List</h5>
                        <button class="btn btn-secondary btn-sm">

                            <svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="20" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export
                        </button>
                    </div>
                    <div class="table-responsive iq-customer-table " data-ordering="false">
                        <div id="DataTables_Table_0_wrapper " class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12 col-md-6"></div>
                            </div>
                        </div>
                        <div class="row dt-row">
                            <div class="col-sm-12">
                                <table class="table data-table mb-0 dataTable no-footer" id="DataTables_Table_0"
                                    aria-describedby="DataTables_Table_0_info">

                                    <thead class="table-color-heading">
                                        <tr>
                                            <th><label class="text-muted m-0">ID</label></th>
                                            <th><label class="text-muted mb-0">Product Name</label></th>
                                            <th><label class="text-muted mb-0">Category</label></th>
                                            <th><label class="text-muted mb-0">Material </label></th>
                                            <th><label class="text-muted mb-0">Description</label></th>
                                            <th><label class="text-muted mb-0">Created_At</label></th>
                                            <th><label class="text-muted mb-0">Updated_At</label></th>
                                            <th class="text-start"><span class="text-muted">Action</span></th>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>

                                                <td>{{ $product->id }}</td>
                                                <td class="">
                                                    <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                        <div class="h-avatar is-medium">

                                                            <img class="avatar rounded" alt="user-icon"
                                                                src="{{ asset('storage/' . $product->image) }}">

                                                        </div>
                                                        <div class="data-content">
                                                            <div>
                                                                <span class="fw-bold">{{ $product->name }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $product->category->name  }}</td>
                                                <td>{{ $product->material }}</td>
                                                <td>{{ $product->description }}</td>
                                                <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                                <td>{{ $product->updated_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-start align-items-center gap-2">
                                                        <a href=""
                                                            class="btn btn-sm btn-icon text-warning" data-bs-toggle="tooltip"
                                                            title="Xem biến thể">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                                            </svg>
                                                        </a>
                                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                                            class="btn btn-sm btn-icon text-primary" data-bs-toggle="tooltip"
                                                            title="Edit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                            </svg>
                                                        </a>
                                                        <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn xoá sản phẩm này không?');"
                                                            style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-icon text-danger"
                                                                data-bs-toggle="tooltip" title="Delete">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>

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

@endsection