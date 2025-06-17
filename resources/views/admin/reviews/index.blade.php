@extends('layouts.app')

@section('title', 'Review')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Header -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-schedule mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="fw-bold">Review</h4>
                    </div>
                    <div class="create-workform">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <!-- Search -->
                            <div class="modal-product-search d-flex flex-wrap">
                                <form class="me-3 position-relative">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="exampleInputText"
                                            placeholder="Search Review">
                                        <a class="search-link" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="" width="20"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Table -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-block card-stretch">
                            <div class="card-body p-0">
                                <div class="d-flex justify-content-between align-items-center p-3 pb-md-0">
                                    <h5 class="fw-bold">Review List</h5>
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
                                                <th><label class="text-muted mb-0">Product Name</label></th>
                                                <th><label class="text-muted mb-0">Reviewer</label></th>
                                                <th><label class="text-muted mb-0">Rating</label></th>
                                                <th><label class="text-muted mb-0">Content</label></th>
                                                <th><label class="text-muted mb-0">Review Date</label></th>
                                                <th><label class="text-muted mb-0">Status</label></th>
                                                <th class="text-start"><span class="text-muted">Action</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             @foreach ($reviews as $review)
                                                <tr class="white-space-no-wrap">
                                                    
                                                    
                                                    <td>{{ $review->id }}</td>
                                                    <td style="white-space: normal; word-break: break-word;">
                                                        {{ $review->product->name }}
                                                    </td>                                                         
                                                    <td>{{ $review->user->name ?? 'N/A' }}</td> 
                                                    <td>
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $review->rating)
                                                                <span style="color: gold; font-size: 20px;">&#9733;</span>
                                                            @else
                                                                <span style="color: #ccc; font-size: 20px;">&#9733;</span>
                                                            @endif
                                                        @endfor
                                                    </td>
                                                    <td style="max-width: 70px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer;"
                                                        data-bs-toggle="modal" data-bs-target="#contentModal{{ $review->id }}">
                                                        {{ $review->content }}
                                                    </td>
                                                    <td>{{ $review->created_at->format('d/m/Y') }}</td>
                                                    <td>{{ $review->status == 1 ? 'Hiển thị' : 'Bị ẩn' }}</td>
                                                    <td>
                                                        <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST">
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

                                                <!-- Modal hiển thị nội dung đầy đủ -->
                                                    <div class="modal fade" id="contentModal{{ $review->id }}" tabindex="-1" aria-labelledby="contentModalLabel{{ $review->id }}" aria-hidden="true" data-bs-scrollbar="false">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="contentModalLabel{{ $review->id }}">Content Reviewer</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>{{ $review->content }}</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>

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
@endsection
