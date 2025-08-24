<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductFavorite;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClientAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function info()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        $user = Auth::user();

        return view('client.account.info', compact('user'));
    }

    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        $user = Auth::user(); // Lấy user đang đăng nhập

        $ordersQuery = Order::with('orderDetails.productVariant.product', 'orderStatus')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $ordersQuery->where('order_status_id', $request->status);
        }

        $orders = $ordersQuery->paginate(10);

        return view('client.account.orders.index', compact('orders'));
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
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     */
    public function track(string $id)
    {
        if (!Auth::check()) {
                return redirect()->route('login');
            }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        // Lấy đơn hàng theo ID
        $order = Order::with(['orderStatus', 'payment.paymentMethod']) // load quan hệ trạng thái đơn, phương thức thanh toán
            ->where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Lấy chi tiết đơn hàng, kèm thông tin biến thể, màu, size
        $orderDetail = $order->orderDetails()->with([
            'productVariant.product',
            'productVariant.color',
            'productVariant.size',
        ])->get();

        // Tính tổng số lượng
        $order->total_quantity = $orderDetail->sum('quantity');

        // Nếu chưa có shipping_fee trong DB, gán cố định
        if (is_null($order->shipping_fee)) {
            $order->shipping_fee = 30000;
        }

        return view('client.account.orders.track', compact('order', 'orderDetail'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!Auth::check()) {
                return redirect()->route('login');
            }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        // Lấy đơn hàng theo ID
        $order = Order::with(['orderStatus', 'payment.paymentMethod']) // load quan hệ trạng thái đơn, phương thức thanh toán
            ->where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Lấy chi tiết đơn hàng, kèm thông tin biến thể, màu, size
        $orderDetail = $order->orderDetails()->with([
            'productVariant.product',
            'productVariant.color',
            'productVariant.size',
        ])->get();

        // Tính tổng số lượng
        $order->total_quantity = $orderDetail->sum('quantity');

        // Nếu chưa có shipping_fee trong DB, gán cố định
        if (is_null($order->shipping_fee)) {
            $order->shipping_fee = 30000;
        }

        return view('client.account.orders.show', compact('order', 'orderDetail'));
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        // Chỉ cho phép hủy khi trạng thái là 1 (Chưa xác nhận) hoặc 2 (Đã xác nhận) hoặc 3 là (Chuẩn bị hàng)
        if (in_array($order->order_status_id, [1, 2, 3])) {
            $order->order_status_id = 9; // 8 = Trạng thái Hủy đơn
            $order->save();
        }

        return redirect()->back();
    }

    /**
     * Gửi yêu cầu hoàn hàng
     */
    public function return($id, Request $request)
    {
        $order = Order::with('orderDetails.productVariant')->findOrFail($id);

        // Kiểm tra đơn có thuộc user hiện tại không
        $userId = Auth::id();
        if ($order->user_id !== $userId) {
            abort(403, 'Bạn không có quyền hoàn đơn này.');
        }

        // Chỉ cho phép hoàn khi đơn đã Thành công (6)
        if ($order->order_status_id != 6) {
            return redirect()->back()->with('error', 'Đơn hàng chưa hoàn tất, không thể yêu cầu hoàn hàng.');
        }

        // Validate dữ liệu
        $validated = $request->validate([
            'return_reason' => 'required|string|max:1000',
            'other_reason' => 'required_if:return_reason,other|nullable|string|max:1000',
            'return_bank' => 'required|string|max:100',
            'return_stk' => 'required|regex:/^[0-9]+$/|max:100',
            'return_images' => 'required|max:2048',
        ], [
            'return_reason.required' => 'Bạn cần chọn hoặc nhập lý do hoàn hàng.',
            'return_reason.max' => 'Lý do hoàn hàng không được vượt quá 1000 ký tự.',
            'other_reason.required_if' => 'Bạn phải nhập lý do khác khi chọn "Khác..."',
            'other_reason.max' => 'Lý do không được vượt quá 1000 ký tự.',
            'return_bank.required' => 'Bạn cần nhập tên ngân hàng.',
            'return_bank.max' => 'Tên ngân hàng không được vượt quá 100 ký tự.',
            'return_stk.required' => 'Bạn cần nhập số tài khoản.',
            'return_stk.regex' => 'Số tài khoản phải là số.',
            'return_stk.max' => 'Số tài khoản không được vượt quá 100 ký tự.',
            'return_images.required' => 'Vui lòng tải ảnh minh chứng.',
            'return_images.max' => 'Mỗi ảnh không được quá 2MB.',
        ]);

        // Xử lý lý do
        $reason = $request->return_reason === 'other' && $request->filled('other_reason')
            ? $request->other_reason
            : $request->return_reason;

        // Upload ảnh (nếu có)
        $imageLinks = [];
        if ($request->hasFile('return_images')) {
            foreach ($request->file('return_images') as $file) {
                $path = $file->store('returns', 'public'); // storage/app/public/returns
                $imageLinks[] = asset('storage/' . $path);
            }
        }

        // Cập nhật đơn hàng
        $order->update([
            'order_status_id' => 7, // 7 = Chờ hoàn hàng
            'return_reason' => $reason,
            'return_bank'     => $request->return_bank,
            'return_stk'      => $request->return_stk,
            'return_image' => $imageLinks ? implode(',', $imageLinks) : null,
        ]);

        return redirect()->back()->with('success', 'Yêu cầu hoàn hàng đã được gửi!');
    }

    public function favorite(Request $request)
    {
        if (!Auth::check()) {
                return redirect()->route('login');
            }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        // Lấy danh sách sản phẩm yêu thích của user
        $favorites = ProductFavorite::with([
            'product.variants.color',
            'product.variants.size'
        ])->where('user_id', $userId)->get();

        // Lấy giá trị sắp xếp từ query string
        $sort = $request->query('sort', 'newest');

        // Sắp xếp theo yêu cầu
        $favorites = match ($sort) {
            'price_asc' => $favorites->sortBy(fn($f) => optional($f->product->variants->first())->sale ?? optional($f->product->variants->first())->price ?? 0),
            'price_desc' => $favorites->sortByDesc(fn($f) => optional($f->product->variants->first())->sale ?? optional($f->product->variants->first())->price ?? 0),
            'name_asc' => $favorites->sortBy(fn($f) => $f->product->name),
            'name_desc' => $favorites->sortByDesc(fn($f) => $f->product->name),
            default => $favorites->sortByDesc('created_at'),
        };

        return view('client.account.favorite', compact('favorites', 'sort'));
    }

    public function toggleFavorite(Request $request)
    {
        $userId = Auth::id();
        $productId = $request->input('product_id');

        if (!$userId) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập để thêm sản phẩm yêu thích.');
        }

        if (!$productId) {
            return redirect()->back()->with('error', 'Thông tin sản phẩm không hợp lệ.');
        }

        $favorite = ProductFavorite::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($favorite) {
            $favorite->delete();
            return redirect()->back()->with('success', 'Đã bỏ thích sản phẩm.');
        }

        ProductFavorite::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào yêu thích.');
    }

    // Reviews
    public function review(Request $request)
    {
        if (!Auth::check()) {
                return redirect()->route('login');
            }
        $userId = Auth::id(); // Lấy ID người dùng hiện tại

        // Lấy các đơn hàng đã hoàn thành
        $reviews = Order::with(['orderDetails.productVariant.product', 'orderStatus'])
            ->where('user_id', $userId)
            ->where('order_status_id', 6)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Lấy tất cả order_id và product_id mà user đã review 
        $reviewedProducts = Review::whereIn('order_id', function($query) use ($userId) {
            $query->select('id')
                ->from('orders')
                ->where('user_id', $userId);
        })->get()->mapWithKeys(function($review) {
            return [$review->order_id.'-'.$review->product_id => true];
        });

        return view('client.account.review', compact('reviews', 'reviewedProducts'));
    }

    public function store(Request $request)
    {
        $orderDetailIds = $request->input('order_detail_id', []);
        $ratings = $request->input('rating', []);
        $contents = $request->input('content', []);

        foreach ($orderDetailIds as $index => $orderDetailId) {
            $orderDetail = OrderDetail::with('order.reviews', 'productVariant.product')->find($orderDetailId);

            if (!$orderDetail) continue;

            $productId = $orderDetail->productVariant->product_id;

            // Kiểm tra sản phẩm này trong đơn này đã đánh giá chưa
            $hasReviewed = $orderDetail->order->reviews->contains(function($review) use ($productId) {
                return $review->product_id == $productId;
            });

            // Nếu đã đánh giá thì bỏ qua
            if ($hasReviewed) continue;

            // Nếu người dùng đã chọn số sao (rating) mới lưu
            if (isset($ratings[$index]) && $ratings[$index] != '') {
                Review::create([
                    'order_id' => $orderDetail->order_id,
                    'product_id' => $productId,
                    'rating' => $ratings[$index],
                    'content' => $contents[$index] ?? '',
                    'status' => 1,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Đánh giá đã được gửi!');
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
