<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ClientProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $product = Product::with(['variants.color', 'variants.size'])->findOrFail($id);

        // Lấy reviews đã duyệt, đơn hàng thành công
        $reviews = Review::with('user')
            ->where('product_id', $id)
            ->where('status', 1)
            ->whereHas('order', function($q) {
                $q->where('order_status_id', 6); // trạng thái "Thành công"
            })
            ->latest()
            ->get();

        // Lấy category chính của product
        $category = $product->category;

        // Breadcrumb từ category
        $breadcrumbs = [];
        $currentCategory = $category;
        while ($currentCategory) {
            array_unshift($breadcrumbs, $currentCategory);
            $currentCategory = $currentCategory->parent;
        }

        $averageRating = $reviews->avg('rating');
        $totalReviews  = $reviews->count();

        return view('client.products.show', compact('product', 'reviews', 'averageRating', 'breadcrumbs', 'totalReviews'));
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
