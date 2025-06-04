@extends('layouts.app') {{-- Hoặc layout admin của bạn, ví dụ: layouts.admin_app --}}

@section('title', 'Client Account Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-schedule mb-4">
                <div class="d-flex align-items-center">
                    <h4 class="fw-bold">Client Account Details: {{ $user->fullname }}</h4>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.accounts.client-manage.edit', ['user' => $user->id]) }}" class="btn btn-primary d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit Client
                    </a>
                    <a href="{{ route('admin.accounts.client-manage.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>

            @php
                $imagePath = null;
                if ($user->image) {
                    $imagePath = preg_replace('#^/?storage/#', '', $user->image);
                }
                $roles = [
                    3 => 'Admin',
                    1 => 'Seller',
                    2 => 'Client',
                ];
            @endphp

            <div class="card card-block card-stretch">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <img
                                src="{{ $imagePath && file_exists(public_path('storage/' . $imagePath))
                                    ? asset('storage/' . $imagePath)
                                    : asset('images/default-avatar.png') }}"
                                alt="Client Avatar"
                                class="img-fluid rounded-circle"
                                style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #dee2e6;"
                                onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';"
                            >
                            <h5 class="mt-3 fw-bold">{{ $user->fullname }}</h5>
                            <p class="text-muted">
                                {{ $roles[$user->role_id] ?? "Unknown Role (ID: {$user->role_id})" }}
                            </p>
                        </div>

                        <div class="col-md-9">
                            <h5 class="mb-3 fw-bold border-bottom pb-2">Account Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong class="d-block text-muted">Full Name:</strong>
                                    <span>{{ $user->fullname }}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong class="d-block text-muted">Email Address:</strong>
                                    <span>{{ $user->email }}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong class="d-block text-muted">Phone Number:</strong>
                                    <span>{{ $user->phone ?: 'N/A' }}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong class="d-block text-muted">Account Status:</strong>
                                    @if ($user->is_verified)
                                        <span class="badge bg-success">Verified</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Not Verified</span>
                                    @endif
                                </div>
                                <div class="col-md-12 mb-3">
                                    <strong class="d-block text-muted">Address:</strong>
                                    <span>{{ $user->address ?: 'N/A' }}</span>
                                </div>
                                {{-- <div class="col-md-6 mb-3">
                                    <strong class="d-block text-muted">Ranking:</strong>
                                    <span>{{ $user->ranking_id ?: 'N/A' }}</span>
                                </div> --}}
                                <div class="col-md-6 mb-3">
                                    <strong class="d-block text-muted">Account Created:</strong>
                                    <span>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i:s') : 'N/A' }}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong class="d-block text-muted">Last Updated:</strong>
                                    <span>{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</span>
                                </div>
                                {{-- Thêm các trường khác nếu cần --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
