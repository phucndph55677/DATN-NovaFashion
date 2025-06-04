@extends('layouts.app')
@section('title', 'Banner')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-schedule mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="fw-bold">Banner</h4>
                    </div>
                    <div class="create-workform">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="modal-product-search d-flex flex-wrap">
                                <form class="me-3 position-relative">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="exampleInputText"
                                            placeholder="Search Banner">
                                        <a class="search-link" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="" width="20"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </form>
                                <a href="{{ route('admin.banners.create') }}"
                                    class="btn btn-primary position-relative d-flex align-items-center justify-content-between">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="20" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Banner
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card car card-stretch">
                            <div class="card-body p-0">
                                <div class="d-flex justify-content-between align-items-center p-3 pb-md-0">
                                    <h5 class="fw-bold">Banner List</h5>
                                    <button class="btn btn-secondary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="20" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Export
                                    </button>
                                </div>
                                <div class="table-responsive iq-product-table">
                                    <table class="table data-table mb-0">
                                        <thead class="table-color-heading">
                                            <tr class="text-light">
                                                <th><label class="text-muted m-0">ID</label></th>
                                                <th><label class="text-muted mb-0">Image</label></th>
                                                <th><label class="text-muted mb-0">Description</label></th>
                                                <th><label class="text-muted mb-0">Status</label></th>
                                                <th><label class="text-muted mb-0">Start Date</label></th>
                                                <th><label class="text-muted mb-0">End Date</label></th>
                                                <th class="text-start"><span class="text-muted">Action</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($banners as $index => $banner)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        @if ($banner->image)
                                                            <img src="{{ asset('storage/' . $banner->image) }}"
                                                                alt="Banner Image" width="150" height="100">
                                                        @else
                                                            <span class="text-muted">No Image</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $banner->description }}</td>
                                                    <td>
                                                        @if ($banner->status)
                                                            <span class="badge bg-success">Visible</span>
                                                        @else
                                                            <span class="badge bg-secondary">Hidden</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($banner->start_date)->format('d/m/Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($banner->end_date)->format('d/m/Y') }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-start align-items-center gap-2">

                                                            <!-- Edit -->
                                                            <a href="{{ route('admin.banners.edit', $banner->id) }}"
                                                                class="btn btn-sm btn-icon text-primary"
                                                                data-bs-toggle="tooltip" title="Edit">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                </svg>
                                                            </a>

                                                            <!-- Delete -->
                                                            <form action="{{ route('admin.banners.destroy', $banner->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to delete this banner?');"
                                                                style="display: inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-icon text-danger"
                                                                    data-bs-toggle="tooltip" title="Delete">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
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
                                </div> {{-- End table --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
