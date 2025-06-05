@extends('layouts.app')

@section('title', 'Add New Seller Account')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Breadcrumb -->
            <div class="col-lg-12 mb-2">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <nav style="--bs-breadcrumb-divider: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%278%27 height=%278%27%3E%3Cpath d=%27M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z%27 fill=%27currentColor%27/%3E%3C/svg%3E');"
                        aria-label="breadcrumb">
                        <ol class="breadcrumb ps-0 mb-0 pb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.accounts.seller-manage.index') }}">Seller Accounts</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Seller Account</li>
                        </ol>
                    </nav>
                    <a href="{{ route('admin.accounts.seller-manage.index') }}"
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
                <h4 class="fw-bold d-flex align-items-center">New Seller Account</h4>
            </div>

            <!-- Error alert (hiện tất cả lỗi validation) -->
            @if ($errors->any())
                <div class="col-lg-12">
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{!! nl2br(e($error)) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Seller Information</h5>

                        <form class="row g-3" action="{{ route('admin.accounts.seller-manage.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Fullname -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted text-uppercase">Full Name</label>
                                <input type="text" name="fullname"
                                    class="form-control @error('fullname') is-invalid @enderror"
                                    placeholder="Enter full name" value="{{ old('fullname') }}">
                                @error('fullname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted text-uppercase">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Enter email"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted text-uppercase">Phone</label>
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="Enter phone number" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="col-md-6 mb-3 position-relative">
                                <label class="form-label fw-bold text-muted text-uppercase">Password</label>
                                <input id="password" type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter password">
                                <button type="button"
                                    class="eye-toggle-btn btn btn-outline-secondary btn-sm position-absolute top-50 end-0 translate-middle-y me-2"
                                    onclick="togglePassword('password', this)" tabindex="-1"
                                    style="z-index:10; border:none; background:transparent; padding:0; display:none;">
                                    <!-- Mắt mở -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-eye" width="20" height="20"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <!-- Mắt gạch chéo -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-eye-off d-none" width="20"
                                        height="20" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M17.94 17.94a10 10 0 0 1-5.94 2.06c-7 0-11-8-11-8a17.3 17.3 0 0 1 3.31-4.62" />
                                        <path d="M1 1l22 22" />
                                    </svg>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Confirmation -->
                            <div class="col-md-6 mb-3 position-relative">
                                <label class="form-label fw-bold text-muted text-uppercase">Confirm Password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    class="form-control" placeholder="Confirm password">
                                <button type="button"
                                    class="eye-toggle-btn btn btn-outline-secondary btn-sm position-absolute top-50 end-0 translate-middle-y me-2"
                                    onclick="togglePassword('password_confirmation', this)" tabindex="-1"
                                    style="z-index:10; border:none; background:transparent; padding:0; display:none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-eye" width="20"
                                        height="20" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-eye-off d-none" width="20"
                                        height="20" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M17.94 17.94a10 10 0 0 1-5.94 2.06c-7 0-11-8-11-8a17.3 17.3 0 0 1 3.31-4.62" />
                                        <path d="M1 1l22 22" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Address -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold text-muted text-uppercase">Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3"
                                    placeholder="Enter address">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted text-uppercase">Profile Image</label>
                                <input type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Is Verified -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted text-uppercase">Verified</label>
                                <select name="is_verified" class="form-select @error('is_verified') is-invalid @enderror">
                                    <option value="1" {{ old('is_verified') == '1' ? 'selected' : '' }}>Verified
                                    </option>
                                    <option value="0" {{ old('is_verified') == '0' ? 'selected' : '' }}>Not Verified
                                    </option>
                                </select>
                                @error('is_verified')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    Create Admin Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const iconEye = btn.querySelector('.icon-eye');
        const iconEyeOff = btn.querySelector('.icon-eye-off');

        if (input.type === "password") {
            input.type = "text";
            iconEye.classList.add('d-none');
            iconEyeOff.classList.remove('d-none');
        } else {
            input.type = "password";
            iconEye.classList.remove('d-none');
            iconEyeOff.classList.add('d-none');
        }
    }

    function handleToggleBtnVisibility(inputId) {
        const input = document.getElementById(inputId);
        const btn = input.parentElement.querySelector('.eye-toggle-btn');

        if (input.value.length > 0) {
            btn.style.display = 'inline-block';
        } else {
            btn.style.display = 'none';

            // Reset icon về mắt mở khi ẩn nút
            const iconEye = btn.querySelector('.icon-eye');
            const iconEyeOff = btn.querySelector('.icon-eye-off');
            iconEye.classList.remove('d-none');
            iconEyeOff.classList.add('d-none');

            // Reset input type về password
            input.type = 'password';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['password', 'password_confirmation'].forEach(function(id) {
            const input = document.getElementById(id);
            if (input) {
                handleToggleBtnVisibility(id);
                input.addEventListener('input', function() {
                    handleToggleBtnVisibility(id);
                });
            }
        });

        // Ẩn lỗi validation khi nhập lại input
        document.querySelectorAll('input.is-invalid, textarea.is-invalid, select.is-invalid').forEach(function(
            input) {
            input.addEventListener('input', function() {
                input.classList.remove('is-invalid');
                const feedback = input.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'none';
                }
            });
        });
    });
</script>

<style>
    /* Bỏ hover (background, border, màu) trên nút toggle mắt */
    .eye-toggle-btn:hover,
    .eye-toggle-btn:focus {
        background-color: transparent !important;
        border-color: transparent !important;
        box-shadow: none !important;
        color: inherit !important;
    }
</style>
