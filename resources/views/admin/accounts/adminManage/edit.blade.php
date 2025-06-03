@extends('layouts.app')

@section('title', 'Edit Admin Account')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Breadcrumb -->
        <div class="col-lg-12 mb-2">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '>';">
                    <ol class="breadcrumb ps-0 mb-0 pb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.accounts.admin-manage.index') }}">Admin Accounts</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Admin Account</li>
                    </ol>
                </nav>
                <a href="{{ route('admin.accounts.admin-manage.index') }}" class="btn btn-primary btn-sm">Back</a>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Edit Admin Information</h5>

                    <!-- Hiển thị tất cả lỗi validation -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{!! nl2br(e($error)) !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.accounts.admin-manage.update', $user->id) }}" method="POST" enctype="multipart/form-data">
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
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted text-uppercase">Password (Leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter new password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted text-uppercase">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
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
                            <button type="submit" class="btn btn-primary">Update Admin Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
