<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductFavorite;
use Illuminate\Support\Facades\Auth;

class ClientFavoriteController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Lấy danh sách sản phẩm yêu thích (mới nhất nằm trên cùng)
        $favorites = ProductFavorite::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        // Lấy danh sách ID sản phẩm theo thứ tự đã sắp xếp
        $favoriteIds = $favorites->pluck('product_id')->toArray();

        // Lấy tất cả sản phẩm tương ứng và kèm các quan hệ cần thiết
        $products = Product::with('variants')
            ->whereIn('id', $favoriteIds)
            ->get()
            ->sortBy(fn($product) => array_search($product->id, $favoriteIds));

        // Sắp xếp nếu có yêu cầu
        $sort = $request->input('sort', 'newest');

        switch ($sort) {
            case 'price_asc':
                $products = $products->sortBy(fn($p) =>
                    optional($p->variants->first())->sale ?? optional($p->variants->first())->price ?? 0
                );
                break;
            case 'price_desc':
                $products = $products->sortByDesc(fn($p) =>
                    optional($p->variants->first())->sale ?? optional($p->variants->first())->price ?? 0
                );
                break;
            case 'name_asc':
                $products = $products->sortBy('name');
                break;
            case 'name_desc':
                $products = $products->sortByDesc('name');
                break;
            default: // newest
                $products = $products->sortBy(fn($p) => array_search($p->id, $favoriteIds));
                break;
        }

        return view('client.favorites.index', [
            'products' => $products,
            'favoriteIds' => $favoriteIds,
            'sort' => $sort,
        ]);
    }

    public function toggleFavorite(Request $request)
    {
        $productId = $request->input('product_id');
        $userId = Auth::id();

        $favorites = session('favorites', []);

        if (in_array($productId, $favorites)) {
            // Xóa
            $favorites = array_diff($favorites, [$productId]);

            ProductFavorite::where('user_id', $userId)
                ->where('product_id', $productId)
                ->delete();

            $status = 'removed';
        } else {
            $favorites[] = $productId;

            // Tìm giá hiện tại từ variant đầu tiên (nếu có)
            $product = Product::with('variants')->find($productId);
            $price = optional($product->variants->first())->sale
                ?? optional($product->variants->first())->price
                ?? 0;

            ProductFavorite::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'price' => $price,
            ]);

            $status = 'added';
        }

        session(['favorites' => $favorites]);

        return response()->json(['status' => $status]);
    }
}
