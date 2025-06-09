@extends('layouts.app')

@section('title', 'Edit Client Account')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Breadcrumb -->
        <div class="col-lg-12 mb-2">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '>';">
                    <ol class="breadcrumb ps-0 mb-0 pb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.accounts.client-manage.index') }}">Client Accounts</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Client Account</li>
                    </ol>
                </nav>
                <a href="{{ route('admin.accounts.client-manage.index') }}" class="btn btn-primary btn-sm">Back</a>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Edit Client Information</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{!! nl2br(e($error)) !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.accounts.client-manage.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Fullname -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted text-uppercase">Full Name</label>
                            <input type="text" name="fullname" class="form-control @error('fullname') is-invalid @enderror" placeholder="Enter full name" value="{{ old('fullname', $user->fullname) }}">
                            @error('fullname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted text-uppercase">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email" value="{{ old('email', $user->email) }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted text-uppercase">Phone</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Enter phone number" value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3 position-relative">
                            <label class="form-label fw-bold text-muted text-uppercase">Password (Leave blank to keep current)</label>
                            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter new password">
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
                        <div class="mb-3 position-relative">
                            <label class="form-label fw-bold text-muted text-uppercase">Confirm Password</label>
                            <input id="password_confirmation" type="password"  name="password_confirmation" class="form-control" placeholder="Confirm new password">
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
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted text-uppercase">Address</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Enter address">{{ old('address', $user->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Current Image -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted text-uppercase">Current Profile Image</label><br>
                            @php
                                $imagePath = $user->image ? preg_replace('#^/?storage/#', '', $user->image) : null;
                            @endphp
                            <img src="{{ $imagePath && file_exists(public_path('storage/' . $imagePath)) ? asset('storage/' . $imagePath) : asset('images/default-avatar.png') }}"
                                alt="Current Image" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%;">
                        </div>

                        <!-- New Image -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted text-uppercase">Change Profile Image</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Verified -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted text-uppercase">Verified</label>
                            <select name="is_verified" class="form-select @error('is_verified') is-invalid @enderror">
                                <option value="1" {{ old('is_verified', $user->is_verified) == '1' ? 'selected' : '' }}>Verified</option>
                                <option value="0" {{ old('is_verified', $user->is_verified) == '0' ? 'selected' : '' }}>Not Verified</option>
                            </select>
                            @error('is_verified')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Client Account</button>
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

      /* Giới hạn chiều dài input password */
    #password,
    #password_confirmation {
        max-width: 300px;
    }
</style>
