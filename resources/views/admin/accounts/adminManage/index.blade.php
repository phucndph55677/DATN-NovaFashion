@extends('layouts.app') {{-- Hoặc layout admin của bạn, ví dụ: layouts.admin_app --}}

@section('title', 'Admin Management')

@section('content')
    <div class="container-fluid">
        {{-- Session Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-schedule mb-4">
                    <div class="d-flex align-items-center">
                        <h4 class="fw-bold">Admin Account Management</h4>
                    </div>
                    <div class="create-workform">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="modal-product-search d-flex flex-wrap">
                                <form class="me-3 position-relative" method="GET"
                                    action="{{ route('admin.accounts.admin-manage.index') }}">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" placeholder="Search Admin (Name, Email)"
                                            aria-label="Search Admin" name="search" value="{{ request('search') }}">
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

                                <a href="{{ route('admin.accounts.admin-manage.create') }}"
                                    class="btn btn-primary d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="20" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add New Admin
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 pb-md-0">
                            <h5 class="fw-bold">Admin List</h5>
                        </div>

                        <div class="table-responsive iq-product-table">
                            <table class="table data-table mb-0">
                                <thead class="table-color-heading">
                                    <tr class="text-light">
                                        <th><label class="text-muted m-0">ID</label></th>
                                        <th><label class="text-muted mb-0">Avatar</label></th>
                                        <th><label class="text-muted mb-0">Full Name</label></th>
                                        <th><label class="text-muted mb-0">Email</label></th>
                                        <th><label class="text-muted mb-0">Phone</label></th>
                                        <th><label class="text-muted mb-0">Verified</label></th>
                                        <th><label class="text-muted mb-0">Created At</label></th>
                                        <th class="text-center"><span class="text-muted">Action</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($admins as $admin)
                                        @if($admin->role_id == 3)
                                        <tr>
                                            <td>{{ $admin->id }}</td>
                                            <td>
                                                @php
                                                    $imagePath = null;
                                                    if ($admin->image) {
                                                        $imagePath = preg_replace('#^/?storage/#', '', $admin->image);
                                                    }
                                                @endphp
                                                <img
                                                    src="{{ $imagePath && file_exists(public_path('storage/' . $imagePath))
                                                        ? asset('storage/' . $imagePath)
                                                        : asset('images/default-avatar.png') }}"
                                                    alt="Avatar"
                                                    class="img-fluid rounded-circle"
                                                    style="width: 40px; height: 40px; object-fit: cover;"
                                                    onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';"
                                                >
                                            </td>
                                            <td>{{ $admin->fullname }}</td>
                                            <td>{{ $admin->email }}</td>
                                            <td>{{ $admin->phone ?: 'N/A' }}</td>
                                            <td>
                                                @if ($admin->is_verified)
                                                    <span class="badge bg-success">Verified</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Not Verified</span>
                                                @endif
                                            </td>
                                            <td>{{ $admin->created_at ? $admin->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <a href="{{ route('admin.accounts.admin-manage.show', $admin->id) }}"
                                                        class="btn btn-sm btn-icon text-info" data-bs-toggle="tooltip"
                                                        title="View Details">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.294 1.006-.792 1.93-1.422 2.745M12 19c-4.478 0-8.268-2.943-9.542-7 .294-1.006.792-1.93 1.422-2.745" />
                                                        </svg>
                                                    </a>

                                                    <a href="{{ route('admin.accounts.admin-manage.edit', $admin->id) }}"
                                                        class="btn btn-sm btn-icon text-primary" data-bs-toggle="tooltip"
                                                        title="Edit Admin">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </a>

                                                    <form
                                                        action="{{ route('admin.accounts.admin-manage.destroy', $admin->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản quản trị này không? Hành động này không thể hoàn tác.');"
                                                        style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-icon text-danger"
                                                            data-bs-toggle="tooltip" title="Delete Admin">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">No admin accounts found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{-- @if ($admins->hasPages())
                            <div class="d-flex justify-content-center mt-4 px-3">
                                {{ $admins->links() }}
                            </div>
                        @endif --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush
