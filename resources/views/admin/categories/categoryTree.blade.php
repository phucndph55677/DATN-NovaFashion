@php
    $hasChildren = $category->childrenRecursive->count() > 0;
@endphp

<div class="category-item" data-id="{{ $category->id }}" data-level="{{ $level }}" data-parent="{{ $category->parent_id }}">
    <div class="d-flex align-items-center justify-content-between p-2 border-bottom" style="padding-left: {{ $level * 20 }}px;">
        <div class="d-flex align-items-center">
            {{-- Nút mở/đóng --}}
            @if($hasChildren)
                <button class="btn btn-sm btn-outline-secondary me-2 toggle-children" data-id="{{ $category->id }}">
                    <svg class="toggle-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            @else
                <div class="me-4"></div>
            @endif

            {{-- Tên danh mục/ Mô tả --}}
            <span class="fw-bold">{{ $category->name }}</span>
            @if (!empty($category->description))
                <span class="text-muted ms-2">- {{ $category->description }}</span>
            @endif
        </div>

        <div class="d-flex gap-1">
            <!-- Sửa -->
            <a class="" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Sửa" href="{{ route('admin.categories.edit', $category->id) }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="text-secondary me-4" width="20"
                    fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </a>

            <!-- Xoá -->
            <form action="{{ route('admin.categories.destroy', $category->id) }}"
                method="POST"
                onsubmit="return confirm('Bạn có chắc chắn muốn xoá danh mục này không?');"
                style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-icon text-danger"
                    data-bs-toggle="tooltip" title="Xóa">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>     
        </div>
    </div>

    {{-- Danh mục con --}}
    @if($hasChildren)
        <div class="children-container collapse-tree" id="children-{{ $category->id }}" style="display: none;">
            @foreach($category->childrenRecursive as $child)
                @include('admin.categories.categoryTree', ['category' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>