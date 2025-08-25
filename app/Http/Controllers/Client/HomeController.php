<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::where('status', 1) // chỉ lấy voucher đang hiện
            ->whereDate('start_date', '<=', now()) // đã tới ngày bắt đầu
            ->whereDate('end_date', '>=', now())   // chưa hết hạn
            ->orderBy('created_at', 'desc')        // voucher mới nhất hiển thị trước
            ->get();

        // Lấy category Nữ
        $nuCategory = Category::where('name', 'Nữ')->first();

        // Sản phẩm thuộc danh mục Nữ (bao gồm con)
        $productsNu = collect(); // mặc định rỗng
        if ($nuCategory) {
            $productsNu = Product::with(['variants.color', 'variants.size'])
                ->whereHas('category', function ($q) use ($nuCategory) {
                    $q->where('id', $nuCategory->id)
                    ->orWhere('parent_id', $nuCategory->id);
                })
                ->where('onpage', 1)
                ->latest()
                ->get();
        }

        // Nút xem tất cả Danh mục Nữ
        $parentNu = Category::where('name', 'Nữ')->first();

        // Lấy category Nam
        $namCategory = Category::where('name', 'Nam')->first();

        // Sản phẩm thuộc danh mục Nam (bao gồm con)
        $productsNam = collect(); // mặc định rỗng
        if ($namCategory) {
            $productsNam = Product::with(['variants.color', 'variants.size'])
                ->whereHas('category', function ($q) use ($namCategory) {
                    $q->where('id', $namCategory->id)
                    ->orWhere('parent_id', $namCategory->id);
                })
                ->where('onpage', 1)
                ->latest()
                ->get();
        }

        // Nút xem tất cả Danh mục Nam
        $parentNam = Category::where('name', 'Nam')->first();

        // Lấy category Tự Hào Việt Nam Ơi
        $vnCategory = Category::where('name', 'Tự Hào Việt Nam Ơi')->first();  

        // Sản phẩm thuộc danh mục Tự Hào Việt Nam Ơi (bao gồm con)
        $productsVN = collect(); // mặc định rỗng
        if ($vnCategory) {
            $productsVN = Product::with(['variants.color', 'variants.size'])
                ->whereHas('category', function ($q) use ($vnCategory) {
                    $q->where('id', $vnCategory->id)
                    ->orWhere('parent_id', $vnCategory->id);
                })
                ->where('onpage', 1)
                ->latest()
                ->get();
        }

        // Nút xem tất cả Danh mục Tự Hào Việt Nam Ơi
        $parentVN = Category::where('name', 'Tự Hào Việt Nam Ơi')->first();

        // Lấy category Bộ Sưu Tập Gia Đình
        $FMLCategory = Category::where('name', 'Bộ Sưu Tập Gia Đình')->first();  

        // Sản phẩm thuộc danh mục Bộ Sưu Tập Gia Đình (bao gồm con)
        $productsFML = collect(); // mặc định rỗng
        if ($FMLCategory) {
            $productsFML = Product::with(['variants.color', 'variants.size'])
                ->whereHas('category', function ($q) use ($FMLCategory) {
                    $q->where('id', $FMLCategory->id)
                    ->orWhere('parent_id', $FMLCategory->id);
                })
                ->where('onpage', 1)
                ->latest()
                ->get();
        }

        // Nút xem tất cả Danh mục Bộ Sưu Tập Gia Đình
        $parentFML = Category::where('name', 'Bộ Sưu Tập Gia Đình')->first();

        // Lấy banner theo từng vị trí
        $banners_top_home = Banner::where('status', 1)
            ->where('location_id', 5) // Trang chủ - Đầu trang
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->latest()
            ->get();

        // Lấy banner theo từng vị trí
        $banners_top_home = Banner::where('status', 1)
            ->where('location_id', 1) // Trang chủ - Đầu trang
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

        $banners_mid_home = Banner::where('status', 1)
            ->where('location_id', 2) // Trang chủ - Giữa trang
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

        $banners_bottom_home = Banner::where('status', 1)
            ->where('location_id', 3) // Trang chủ - Cuối trang
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

        return view('home', compact(
            'vouchers', 
            'productsNu', 'parentNu', 
            'productsNam', 'parentNam', 
            'productsVN', 'parentVN', 
            'productsFML', 'parentFML', 
            'banners_top_home', 'banners_mid_home', 'banners_bottom_home'
        ));
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
