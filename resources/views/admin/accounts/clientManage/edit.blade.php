@extends('layouts.app')

@section('title', 'Client Manage')

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
                                <li class="breadcrumb-item"><a href="{{ route('admin.accounts.client-manage.index') }}">Client</a></li>
                                <li class="breadcrumb-item active" aria-current="page"> Edit Client</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('admin.accounts.client-manage.index') }}"
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
                <h4 class="fw-bold d-flex align-items-center">Update Client</h4>
            </div>

            <!-- Form -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Basic Information</h5>
                        <form class="row g-3" action="{{ route('admin.accounts.client-manage.update', $client->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold text-muted text-uppercase">Client Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Client Name" value="{{ $client->name }}" readonly>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold text-muted text-uppercase">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{ $client->email }}" readonly>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-bold text-muted text-uppercase">Phone</label>
                                <input type="number" class="form-control" id="phone" name="phone" placeholder="Enter Phone" value="{{ $client->phone }}" readonly>
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="ranking_id" class="form-label fw-bold text-muted text-uppercase">Ranking</label>
                                <select id="ranking_id" name="ranking_id" class="form-select form-control choicesjs">
                                    <option value="">Select Ranking</option>
                                    @foreach ($rankings as $ranking)
                                        <option value="{{ $ranking->id }}"
                                            @selected($ranking->id == $client->ranking_id)>
                                            {{ $ranking->name }}</option>                
                                    @endforeach
                                </select>
                                @error('ranking_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold text-muted text-uppercase">Status</label>
                                <select id="status" name="status" class="form-select form-control choicesjs">
                                    <option value="">Select Status</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}"
                                            @selected($status->id == $client->status)>
                                            {{ $status->name }}</option>                        
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label fw-bold text-muted text-uppercase">Address</label>                                    
                                <textarea class="form-control" name="address" id="address" rows="4" placeholder="Enter address" readonly>{{ $client->address }}</textarea>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- <div class="col-md-6 mb-3">
                                <label for="image" class="form-label fw-bold text-muted text-uppercase">Client Image</label>
                                <input type="file" class="form-control" id="image" name="image" readonly>
                                @if($client->image)
                                    <img src="{{ asset('storage/' . $client->image) }}" alt="Client Image"
                                        style="width: 120px; margin-top: 10px;">
                                @endif
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">Update Client</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
