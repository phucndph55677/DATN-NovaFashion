<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\Product;
use Illuminate\Http\Request;

class ClientCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $slug, $subslug = null, $childslug = null)
    {
        // Ghép slug đầy đủ theo cấu trúc lưu trong DB (parent/child/...)
        $pathSlug = implode('/', array_filter([$slug, $subslug, $childslug]));

        // Tìm category theo slug đầy đủ
        $category = Category::where('slug', $pathSlug)->firstOrFail();

        // Lấy luôn category con (nếu có)
        $categoryIds = array_merge([$category->id], $category->getAllDescendantIds());

        // Lấy filters từ request
        $filters = [
            'sizes'      => array_filter((array) $request->input('att_size', [])),
            'colors'     => array_filter((array) $request->input('att_color', [])),
            'price_from' => $request->input('product_price_from'),
            'price_to'   => $request->input('product_price_to'),
            'sort'       => $request->input('sort')
        ];

        // Query sản phẩm
        $products = Product::whereIn('category_id', $categoryIds)
            ->with('variants.size') // load size để lọc theo size_code
            ->when(!empty($filters['sizes']), function ($query) use ($filters) {
                $query->whereHas('variants.size', function ($q) use ($filters) {
                    $q->whereIn('size_code', $filters['sizes']);   // lọc theo size_code
                });
            })
            ->when(!empty($filters['colors']), function ($query) use ($filters) {
                $query->whereHas('variants', function ($q) use ($filters) {
                    $q->whereIn('color_id', $filters['colors']);
                });
            })
            ->when($filters['price_from'], function ($query) use ($filters) {
                $query->where('price', '>=', (int) str_replace('.', '', $filters['price_from']));
            })
            ->when($filters['price_to'], function ($query) use ($filters) {
                $query->where('price', '<=', (int) str_replace('.', '', $filters['price_to']));
            })
            ->paginate(12)
            ->appends($request->query());

        // Breadcrumb: danh sách danh mục từ gốc đến hiện tại
        $breadcrumbs = [];
        $currentCategory = $category;
        while ($currentCategory) {
            array_unshift($breadcrumbs, $currentCategory);
            $currentCategory = $currentCategory->parent;
        }

        // Brand Danh mục - Cuối trang
        $banners_bottom_category = Banner::where('status', 1)
            ->where('location_id', 4) // Danh mục - Cuối trang
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orderByRaw("CASE WHEN start_date IS NULL THEN 1 ELSE 0 END") // banner có thời gian -> ưu tiên
            ->orderBy('start_date', 'asc') // banner có ngày bắt đầu thì sort tăng dần
            ->orderBy('created_at', 'desc') // fallback sort mới nhất
            ->get();

        return view('client.categories.index', [
            'slug' => $slug,
            'subslug' => $subslug,
            'childslug' => $childslug,
            'category' => $category,
            'products' => $products,
            'sizes' => Size::all(),
            'colors' => Color::all(),
            'banners_bottom_category' => $banners_bottom_category,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
