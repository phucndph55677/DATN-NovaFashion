@extends('layouts.app')

@section('title', 'Comment')

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
            <!-- Header -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-schedule mb-4">
                <div class="d-flex align-items-center">
                    <h4 class="fw-bold">Comment</h4>
                </div>
                <div class="create-workform">
                    <div class="d-flex flex-wrap align-items-center">
                        <!-- Search -->
                        <div class="modal-product-search d-flex flex-wrap">
                            <form class="me-3 position-relative" method="GET"
                                action="{{ route('admin.comments.index') }}">
                                <div class="form-group mb-0">
                                    <input type="text" class="form-control" placeholder="Search Comment"
                                        aria-label="Search Comment" name="search" value="{{ request('search') }}">
                                    <button type="submit" class="search-link btn btn-link position-absolute"
                                        style="top: 50%; right: 10px; transform: translateY(-50%);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </form>

                            <!-- Add Button -->
                            <a href="{{ route('admin.comments.create') }}"
                                class="btn btn-primary d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="20" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Comment
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <form method="GET" action="{{ route('admin.comments.index') }}">
                <input type="number" name="product_id" placeholder="ID sản phẩm" value="{{ request('product_id') }}">
                <button type="submit">Lọc</button>
            </form>

            <!-- Card Table -->
            <div class="card card-block card-stretch">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-3 pb-md-0">
                        <h5 class="fw-bold">Comment List</h5>
                        <button class="btn btn-secondary btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive iq-product-table">
                        <table class="table data-table mb-0">
                            <thead class="table-color-heading">
                                <tr class="text-light">
                                    <th><label class="text-muted m-0">ID</label></th>
                                    <th><label class="text-muted mb-0">User</label></th>
                                    <th><label class="text-muted mb-0">Comment</label></th>
                                    <th><label class="text-muted mb-0">Status</label></th>
                                    <th><label class="text-muted mb-0">Created At</label></th>
                                    <th><label class="text-muted mb-0">Updated At</label></th>
                                    <th class="text-start"><label class="text-muted mb-0">Action</label></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($comments as $comment)
                                    <tr>
                                        <td>{{ $comment->id }}</td>
                                        <td>{{ $comment->user->name ?? 'N/A' }}</td>
                                        <td>{{ $comment->content }}</td>
                                        <td>
                                            <form action="{{ route('admin.comments.toggle-status', $comment->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm 
                                                    @if ($comment->status == 0) btn-warning~~````````````````````````````````````````
                                                    @elseif ($comment->status == 1) btn-success
                                                    @else btn-secondary @endif">
                                                    {{ $comment->status_label }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ $comment->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $comment->updated_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center gap-2">
                                                <a href="{{ route('admin.comments.edit', $comment->id) }}"
                                                    class="btn btn-sm btn-icon text-primary" data-bs-toggle="tooltip" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536M16.732 3.732A2.5 2.5 0 1120.268 7.268L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.comments.destroy', $comment->id) }}"
                                                    method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xoá không?');"
                                                    style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-icon text-danger" data-bs-toggle="tooltip" title="Delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- Pagination --}}
                        {{-- <div class="mt-3 d-flex justify-content-center">
                            {{ $comments->links() }}
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
