@extends('admin.layouts.app')

@section('title', 'Danh Mục')

@section('content')
    <div class="mt-3">
        <!-- Hiển thị lỗi nếu có -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Hiển thị thành công -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <li>{{ session('success') }}</li>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Header -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 my-schedule mb-4">
                    <div class="d-flex align-items-center">
                        <h4 class="fw-bold">Danh Mục</h4>
                    </div>
                    <div class="create-workform">
                        <div class="d-flex flex-wrap align-items-center">
                            <!-- Search -->
                            <div class="modal-product-search d-flex flex-wrap">
                                <form class="me-3 position-relative">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="exampleInputText" placeholder="Tìm kiếm danh mục...">
                                        <a class="search-link" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="" width="20"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </a>
                                    </div>
                                </form>

                                <!-- Add Button -->
                                <a href="{{ route('admin.categories.create') }}"
                                    class="btn btn-primary d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="20" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Thêm Danh Mục
                                </a>
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
                                    <h5 class="fw-bold">Danh Sách Danh Mục</h5>
                                    <div class="d-flex gap-2">
                                        <!-- Nút Toggle mở/đóng tất cả -->
                                        <button id="toggle-all" class="btn btn-success btn-sm">Mở tất cả</button>

                                        {{-- Xóa mềm --}}
                                        {{-- <a href=""
                                            class="btn btn-danger btn-sm d-flex align-items-center gap-1"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Danh Mục Đã Xoá">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4 a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Thùng Rác</span>
                                        </a> --}}
                                    </div>
                                </div>

                                <!-- Category Tree -->
                                <div class="category-tree p-3">
                                    @if($categories->count() > 0)
                                        @foreach($categories as $category)
                                            @include('admin.categories.categoryTree', ['category' => $category, 'level' => 0])
                                        @endforeach
                                    @else
                                        <div class="text-center py-4">
                                            <p class="text-muted">Không có danh mục nào.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle expand/collapse
            document.querySelector('.category-tree').addEventListener('click', function (e) {
                const toggleBtn = e.target.closest('.toggle-children');
                if (!toggleBtn) return;

                const id = toggleBtn.dataset.id;
                const childrenContainer = document.getElementById('children-' + id);
                const icon = toggleBtn.querySelector('.toggle-icon');

                if (childrenContainer) {
                    const isVisible = childrenContainer.style.display === 'block';
                    childrenContainer.style.display = isVisible ? 'none' : 'block';
                    icon.style.transform = isVisible ? 'rotate(0deg)' : 'rotate(90deg)';
                }
            });

            // JS xử lý Nút Toggle mở/đóng tất cả
            let allExpanded = false; // trạng thái ban đầu: chưa mở

            document.getElementById('toggle-all').addEventListener('click', function () {
                const containers = document.querySelectorAll('.children-container');
                const icons = document.querySelectorAll('.toggle-icon');

                if (allExpanded) {
                    // Đóng tất cả
                    containers.forEach(c => c.style.display = 'none');
                    icons.forEach(i => i.style.transform = 'rotate(0deg)');
                    this.textContent = 'Mở tất cả';
                    this.classList.remove('btn-secondary');
                    this.classList.add('btn-success');
                } else {
                    // Mở tất cả
                    containers.forEach(c => c.style.display = 'block');
                    icons.forEach(i => i.style.transform = 'rotate(90deg)');
                    this.textContent = 'Đóng tất cả';
                    this.classList.remove('btn-success');
                    this.classList.add('btn-secondary');
                }

                allExpanded = !allExpanded; // Đổi trạng thái
            });

            // Inject CSS dynamically
            const style = document.createElement('style');
            style.innerHTML = `
                .category-tree { max-height: 600px; overflow-y: auto; }
                .category-item { border-left: 2px solid #e9ecef; margin-left: 10px; }
                .category-item:hover { background-color: #f8f9fa; }
                .toggle-children { transition: transform 0.2s; cursor: pointer; }
                .toggle-children.expanded { transform: rotate(90deg); }
                .children-container { margin-left: 20px; border-left: 1px solid #dee2e6; }

                /* Level colors */
                .category-item[data-level="0"] { border-left: none; margin-left: 0; }
                .category-item[data-level="1"] { border-left-color: #ff0000; } /* Bright Red */
                .category-item[data-level="2"] { border-left-color: #ffff00; } /* Yellow */
                .category-item[data-level="3"] { border-left-color: #00ff00; } /* Bright Green */
                .category-item[data-level="4"] { border-left-color: #00ffff; } /* Cyan */
                .category-item[data-level="5"] { border-left-color: #0000ff; } /* Bright Blue */
                .category-item[data-level="6"] { border-left-color: #8b00ff; } /* Violet */
                .category-item[data-level="7"] { border-left-color: #ff7f00; } /* Orange */
                .category-item[data-level="8"] { border-left-color: #ff1493; } /* Deep Pink */
                .category-item[data-level="9"] { border-left-color: #00ff7f; } /* Spring Green */
                .category-item[data-level="10"] { border-left-color: #ff4500; } /* Orange Red */
            `;
            document.head.appendChild(style);
        });
    </script>
@endsection