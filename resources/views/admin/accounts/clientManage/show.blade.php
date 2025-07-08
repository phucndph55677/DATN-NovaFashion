@extends('admin.layouts.app')

@section('title', 'Quản Lý Khách Hàng')

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
                                <li class="breadcrumb-item"><a href="{{ route('admin.accounts.client-manage.index') }}">Danh Sách Khách Hàng</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Xem Khách Hàng</li>
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
                        <span class="ms-2">Quay Lại</span>
                    </a>
                </div>
            </div>

            <!-- Title -->
            <div class="col-lg-12 mb-3 d-flex justify-content-between">
                <h4 class="fw-bold d-flex align-items-center">Chi Tiết Khách Hàng</h4>
            </div>

            <!-- Form -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            {{-- Avatar bên trái --}}
                            <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
                                <img
                                    src="{{ asset('storage' . $client->image) }}"
                                    class="img-fluid rounded-circle border border-3 border-primary shadow-sm"
                                    style="width: 200px; height: 200px; object-fit: cover"
                                    alt="Client Avatar"
                                    onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png?20200919003010'"
                                >
                                <h5 class="mt-3 fw-semibold">{{ $client->name }}</h5>
                                <span class="badge bg-{{ $client->status ? 'success' : 'danger' }}">
                                    {{ $client->status ? 'Hoạt Động' : 'Không Hoạt Động' }}
                                </span>
                            </div>

                            {{-- Thông tin bên phải --}}
                            <div class="col-12 col-md-8">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0">
                                        <strong>Email:</strong> {{ $client->email }}
                                    </li>
                                    <li class="list-group-item px-0">
                                        <strong>Số Điện Thoại:</strong> {{ $client->phone }}
                                    </li>
                                    <li class="list-group-item px-0">
                                        <strong>Xếp Hạng:</strong> {{ $client->ranking->name }}
                                    </li>
                                    {{-- <li class="list-group-item px-0">
                                        <strong>Address:</strong> {{ $client->address }}
                                    </li> --}}
                                    <li class="list-group-item px-0 border-bottom">
                                        <strong>Địa Chỉ:</strong> {{ $client->address }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <hr>
                        <h5 class="fw-bold mb-3">Danh Sách Đơn Hàng</h5>
                        <!-- Card Table -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-block card-stretch">
                                    <div class="card-body p-0">                           

                                        <!-- Table -->
                                        <div class="table-responsive iq-product-table">
                                            <table class="table data-table mb-0">
                                                <thead class="table-color-heading">
                                                    <tr class="text-light">
                                                        <th><label class="text-muted m-0">ID</label></th>
                                                        <th><label class="text-muted mb-0">Người Đánh Giá</label></th>
                                                        <th><label class="text-muted mb-0">Nội Dung</label></th>
                                                        <th><label class="text-muted mb-0">Ngày Đánh Giá</label></th>
                                                        <th><label class="text-muted mb-0">Trạng Thái</label></th>
                                                        <th class="text-start"><span class="text-muted">Hành Động</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <hr>
                        <h5 class="fw-bold mb-3">Danh Sách Đánh Giá</h5>
                        <!-- Card Table -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-block card-stretch">
                                    <div class="card-body p-0">                           

                                        <!-- Table -->
                                        <div class="table-responsive iq-product-table">
                                            <table class="table data-table mb-0">
                                                <thead class="table-color-heading">
                                                    <tr class="text-light">
                                                        <th><label class="text-muted m-0">ID</label></th>
                                                        <th><label class="text-muted mb-0">Tên Sản Phẩm</label></th>
                                                        <th><label class="text-muted mb-0">Xếp Hạng</label></th>
                                                        <th><label class="text-muted mb-0">Nội Dung</label></th>
                                                        <th><label class="text-muted mb-0">Ngày Đánh Giá</label></th>
                                                        <th><label class="text-muted mb-0">Trạng Thái</label></th>
                                                        <th class="text-start"><span class="text-muted">Hành Động</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($reviews as $review)
                                                        <tr class="white-space-no-wrap">
                                                            <td>{{ $review->id }}</td>
                                                            <td>{{ $review->product->name }}</td>
                                                            <td>
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= $review->rating)
                                                                        <span style="color: gold; font-size: 20px;">&#9733;</span>
                                                                    @else
                                                                        <span style="color: #ccc; font-size: 20px;">&#9733;</span>
                                                                    @endif
                                                                @endfor
                                                            </td>
                                                            <td style="white-space: normal; word-break: break-word; max-width: 300px;">
                                                                {{ $review->content }}
                                                            </td>  
                                                            <td>{{ $review->created_at->format('d/m/Y') }}</td>
                                                            <td>{{ $review->status == 1 ? 'Hiển thị' : 'Bị ẩn' }}</td>
                                                            <td>
                                                                <form action="{{ route('admin.comment.toggle', $review->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit"
                                                                            onclick="return confirm('Bạn có muốn {{ $review->status == 1 ? 'ẩn' : 'bỏ ẩn' }} đánh giá này không?')"
                                                                            class="btn btn-sm {{ $review->status == 1 ? 'btn-danger' : 'btn-success' }} rounded-pill px-3 shadow-sm">
                                                                        {{ $review->status == 1 ? 'Ẩn' : 'Bỏ ẩn' }}
                                                                    </button>
                                                                </form>
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
                </div>
            </div>
        </div>
    </div>
@endsection