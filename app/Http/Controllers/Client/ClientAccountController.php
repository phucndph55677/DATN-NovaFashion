<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Color;
use App\Models\Size;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductFavorite;
use App\Models\Review;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ClientAccountController extends Controller
{
    /**
     * Hàm xử lý tìm kiếm sản phẩm
     */
    public function search(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        // Lấy filters từ request
        $filters = [
            'sizes'       => array_filter((array) $request->input('att_size', [])),
            'colors'      => array_filter((array) $request->input('att_color', [])),
            'price_range' => $request->input('price_range'),
            'price_from'  => $request->input('product_price_from'),
            'price_to'    => $request->input('product_price_to'),
            'sort'        => $request->input('sort'),
        ];

        $products = Product::with('variants.size', 'variants.color')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->when(!empty($filters['sizes']), function ($query) use ($filters) {
                $query->whereHas('variants.size', function ($q) use ($filters) {
                    $q->whereIn('size_code', $filters['sizes']);
                });
            })
            ->when(!empty($filters['colors']), function ($query) use ($filters) {
                $query->whereHas('variants', function ($q) use ($filters) {
                    $q->whereIn('color_id', $filters['colors']);
                });
            })
            ->when($filters['price_range'] ?? null, function ($query) use ($filters) {
                [$min, $max] = explode('-', $filters['price_range']);
                $query->whereHas('variants', function ($q) use ($min, $max) {
                    $q->whereRaw("
                        CASE 
                            WHEN sale IS NOT NULL AND sale > 0 
                            THEN sale 
                            ELSE price 
                        END >= ?", [(int)$min]);

                    if (!empty($max)) {
                        $q->whereRaw("
                            CASE 
                                WHEN sale IS NOT NULL AND sale > 0 
                                THEN sale 
                                ELSE price 
                            END <= ?", [(int)$max]);
                    }
                });
            })
            ->when($filters['price_from'] || $filters['price_to'], function ($query) use ($filters) {
                $query->whereHas('variants', function ($q) use ($filters) {
                    if (!empty($filters['price_from'])) {
                        $q->whereRaw("
                            CASE 
                                WHEN sale IS NOT NULL AND sale > 0 
                                THEN sale 
                                ELSE price 
                            END >= ?", [(int)$filters['price_from']]);
                    }
                    if (!empty($filters['price_to'])) {
                        $q->whereRaw("
                            CASE 
                                WHEN sale IS NOT NULL AND sale > 0 
                                THEN sale 
                                ELSE price 
                            END <= ?", [(int)$filters['price_to']]);
                    }
                });
            })
            ->when($filters['sort'], function ($query) use ($filters) {
                switch ($filters['sort']) {
                    case 'price_asc':
                        $query->orderByRaw("COALESCE(NULLIF(sale,0), price) ASC");
                        break;
                    case 'price_desc':
                        $query->orderByRaw("COALESCE(NULLIF(sale,0), price) DESC");
                        break;
                    default:
                        $query->orderByDesc('id');
                }
            }, function ($query) {
                $query->orderByDesc('id');
            })
            ->paginate(20)
            ->appends($request->query());

        // category giả để view không lỗi
        $category = (object)[
            'name' => $q ? ('Kết quả tìm kiếm theo "' . $q . '"') : 'Kết quả tìm kiếm',
            'slug' => 'search',
        ];

        return view('client.categories.index', [
            'q'          => $q,
            'products'   => $products,
            'slug'       => 'search',
            'subslug'    => null,
            'childslug'  => null,
            'category'   => $category,
            'breadcrumbs'=> [],
            'sizes'      => Size::all(),
            'colors'     => Color::all(),
            'banners_bottom_category' => collect(),
        ]);
    }

    /**
     * Hàm hiển thị thông tin khách hàng
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

    /**
     * Hàm xử hiện thị các đơn hàng
     */
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
     * Hàm hiển thị Theo dõi đơn hàng
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
     * Hàm hiển thị Chi tiết từng đơn hàng
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
     * Hàm xử lý Hủy đơn hàng
     */
    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        // Chỉ cho phép hủy khi trạng thái là 1 (Chưa xác nhận) hoặc 2 (Đã xác nhận) hoặc 3 là (Chuẩn bị hàng)
        if (!in_array($order->order_status_id, [1, 2, 3])) {
            return redirect()->back()->with('error', 'Đơn hàng ' . $order->order_code . ' đang được giao, không thể hủy đơn.');
        }

        DB::transaction(function () use ($order) {
            // Cập nhật trạng thái hủy
            $order->update(['order_status_id' => 9]);

            // Hoàn lại quantity trong product_variants khi hủy đơn hàng
            $orderDetails = $order->orderDetails()->with('productVariant')->get();
            foreach ($orderDetails as $orderDetail) {
                DB::table('product_variants')
                    ->where('id', $orderDetail->product_variant_id)
                    ->increment('quantity', $orderDetail->quantity);
            }

            // Hoàn voucher nếu có dùng
            if ($order->voucher_id && $order->discount > 0) {
                DB::table('vouchers')
                    ->where('id', $order->voucher_id)
                    ->where('total_used', '>', 0)
                    ->decrement('total_used');

                DB::table('order_vouchers')
                    ->where('order_id', $order->id)
                    ->where('voucher_id', $order->voucher_id)
                    ->delete();
            }
        });

        return redirect()->back()->with('success', 'Đơn hàng ' . $order->order_code . ' đã được hủy.');
    }

    /**
     * Hàm xử lý Gửi yêu cầu hoàn hàng
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

        // Chỉ được hoàn hàng 1 lần (Nếu đơn hàng bị từ chuối rồi thì kh hiện form nữa)
        if ($order->return_rejected) {
            return redirect()->back()->with('error', 'Đơn hàng đã bị từ chối yêu cầu hoàn hàng trước đó.');
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
            'return_rejected' => true, // mặc định khi mới gửi yêu cầu
        ]);

        return redirect()->back()->with('success', 'Yêu cầu hoàn hàng của đơn hàng ' . $order->order_code . ' đã được gửi!');
    }

    /**
     * Hàm hiển thị Sản phẩm yêu thích
     */
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

    /**
     * Hàm xử lý nút Thêm/Bỏ sản phẩm yêu thích
     */
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

    /**
     * Hàm hiển thị Danh sách đơn hàng cần đánh giá
     */
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
            ->where('updated_at', '>=', now()->subDays(7)) // chỉ lấy đơn trong vòng 7 ngày
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

    /**
     * Hàm hiển thị xử lý Đánh giá đơn hàng
     */
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
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $authId = Auth::id();
        if (!$authId) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = User::findOrFail($authId);

        // Chuẩn hoá dữ liệu (loại khoảng trắng 2 đầu)
        $request->merge([
            'name'  => trim((string) $request->input('name')),
            'phone' => trim((string) $request->input('phone')),
            'email' => trim((string) $request->input('email')),
        ]);

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'regex:/^(0|\+?84)(\d{9,10})$/',
                Rule::unique('users', 'phone')->ignore($user->id), // <-- THÊM UNIQUE PHONE
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'name.required'   => 'Vui lòng nhập họ tên.',
            'name.max'        => 'Họ tên tối đa 255 ký tự.',
            'phone.required'  => 'Vui lòng nhập số điện thoại.',
            'phone.regex'     => 'Số điện thoại không đúng định dạng Việt Nam.',
            'phone.unique'    => 'Số điện thoại đã được sử dụng.', // <-- THÔNG BÁO MONG MUỐN
            'email.required'  => 'Vui lòng nhập email.',
            'email.email'     => 'Email không đúng định dạng.',
            'email.unique'    => 'Email đã được sử dụng.',
            'image.image'     => 'Ảnh đại diện phải là tệp hình ảnh.',
            'image.mimes'     => 'Chỉ chấp nhận JPG/JPEG/PNG/WebP.',
            'image.max'       => 'Kích thước ảnh tối đa 2MB.',
        ]);

        $data = [
            'name'  => $validated['name'],
            'phone' => $validated['phone'],   // luôn có vì 'required'
            'email' => $validated['email'],
        ];

        // Ảnh đại diện (lưu thư mục 'users')
        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        // Cập nhật
        User::whereKey($user->id)->update($data);

        return redirect()->route('account.info')->with('success', 'Cập nhập thông tin thành công!');
    }

    /**
     * Đổi mật khẩu
     */
    public function updatePassword(Request $request)
    {
        // Bảo vệ: bắt buộc đăng nhập
        $authId = Auth::id();
        if (!$authId) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = User::findOrFail($authId);

        // Validate
        $validated = $request->validate([
            'customer_pass_old' => ['required'],
            'customer_pass_new1' => ['required', 'min:8'],
            'customer_pass_new2' => ['required', 'same:customer_pass_new1'],
        ], [
            'customer_pass_old.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'customer_pass_new1.required' => 'Vui lòng nhập mật khẩu mới.',
            'customer_pass_new1.min' => 'Mật khẩu mới tối thiểu 8 ký tự.',
            'customer_pass_new2.required' => 'Vui lòng nhập lại mật khẩu mới.',
            'customer_pass_new2.same' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($validated['customer_pass_old'], $user->password)) {
            return back()
                ->withErrors(['customer_pass_old' => 'Mật khẩu hiện tại không đúng.'])
                ->with('open_change_password', true);
        }

        // Không cho dùng lại mật khẩu cũ
        if (Hash::check($validated['customer_pass_new1'], $user->password)) {
            return back()
                ->withErrors(['customer_pass_new1' => 'Mật khẩu mới phải khác mật khẩu hiện tại.'])
                ->with('open_change_password', true);
        }

        // Cập nhật mật khẩu (không dùng $user->save())
        User::whereKey($user->id)->update([
            'password' => Hash::make($validated['customer_pass_new1']),
        ]);

        return redirect()->route('account.info')->with('success', 'Đổi mật khẩu thành công!');
    }
}
