<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::now()->startOfDay();
        $vouchers = Voucher::where('status', 1) // chỉ lấy voucher đang hiện
        ->whereDate('end_date', '>=', $today) // còn hạn
        ->orderBy('created_at', 'desc') // ưu tiên mới nhất
        ->get();

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

        $products = Product::with(['variants.color', 'variants.size'])->latest()->get();

        return view('home', compact('products', 'vouchers', 'banners_top_home', 'banners_mid_home', 'banners_bottom_home'));
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
