<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\Size;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $product = Product::with(['variants.color', 'variants.size'])->where('id', $id)->first();

        return view('admin.products.variants.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $product = Product::with(['variants.color', 'variants.size'])->where('id', $id)->first();
        $colors = Color::all();
        $sizes = Size::all();
        $statuses = [
            (object)['id' => 1, 'name' => 'Cho Phép Kinh Doanh'],
            (object)['id' => 0, 'name' => 'Ngừng Kinh Doanh'],
        ];

        return view('admin.products.variants.create', compact('product', 'colors', 'sizes', 'id', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // 1. Validate cơ bản (Laravel tự trả lỗi nếu có)
        $data = $request->validate(
            [
                'product_id' => 'required|exists:products,id',
                'color_id' => [
                    'required',
                    'exists:colors,id',
                    Rule::unique('product_variants')
                        ->where('product_id', $request->product_id)
                        ->where('size_id', $request->size_id),
                ],
                'size_id'    => [
                    'required',
                    'exists:sizes,id',
                    Rule::unique('product_variants')
                        ->where('product_id', $request->product_id)
                        ->where('color_id', $request->color_id),
                ],
                'price' => 'required|numeric|min:0',
                'sale' => 'nullable|numeric|min:0|lt:price',
                'quantity' => 'required|numeric|min:0',
                'status' => 'required',
                'image' => 'required|image|max:2048',
            ],
            [
                'color_id.required' => 'Vui lòng chọn màu.',
                'color_id.unique' => 'Biến thể với màu & size này đã tồn tại.',
                'size_id.required' => 'Vui lòng chọn size.',
                'size_id.unique'    => 'Biến thể với màu & size này đã tồn tại.',
                'price.required' => 'Giá không được để trống.',
                'price.numeric' => 'Giá phải là số.',
                'price.min' => 'Giá phải >= 0.',
                'sale.numeric' => 'Giá khuyến mãi phải là số.',
                'sale.min' => 'Giá khuyến mãi phải >= 0.',
                'sale.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
                'quantity.required' => 'Số lượng không được để trống.',
                'quantity.numeric' => 'Số lượng phải là số.',
                'quantity.min' => 'Số lượng phải >= 0.',
                'status.required' => 'Vui lòng chọn trạng thái.',
                'image.required' => 'Hình ảnh không được để trống.',
                'image.image' => 'Hình ảnh không hợp lệ.',
                'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            ]
        );

        // 2. Xử lý ảnh
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('variants', 'public');
        }

        ProductVariant::query()->create($data);

        return redirect()->route('admin.variants.index', $request->product_id);
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
        $variant = ProductVariant::findOrFail($id);
        $colors = Color::all();
        $sizes = Size::all();
        $statuses = [
            (object)['id' => 1, 'name' => 'Cho Phép Kinh Doanh'],
            (object)['id' => 0, 'name' => 'Ngừng Kinh Doanh'],
        ];

        return view('admin.products.variants.edit', compact('variant', 'colors', 'sizes', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $variant = ProductVariant::findOrFail($id);

        // 1. Validate cơ bản
        $validator = Validator::make($request->all(),
            [
                'color_id' => 'required|exists:colors,id',
                'size_id' => 'required|exists:sizes,id',
                'price' => 'required|numeric|min:0',
                'sale' => 'nullable|numeric|min:0|lt:price',
                'quantity' => 'required|numeric|min:0',
                'status' => 'required',
                'image' => 'nullable|image|max:2048',
            ],
            [
                'color_id.required' => 'Vui lòng chọn màu.',
                'size_id.required' => 'Vui lòng chọn size.',
                'price.required' => 'Giá không được để trống.',
                'price.numeric' => 'Giá phải là số.',
                'price.min' => 'Giá phải >= 0.',
                'sale.numeric' => 'Giá khuyến mãi phải là số.',
                'sale.min' => 'Giá khuyến mãi phải >= 0.',
                'sale.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
                'quantity.required' => 'Số lượng không được để trống.',
                'quantity.numeric' => 'Số lượng phải là số.',
                'quantity.min' => 'Số lượng phải >= 0.',
                'status.required' => 'Vui lòng chọn trạng thái.',
                'image.image' => 'Hình ảnh không hợp lệ.',
                'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            ]);

        // 2. Check trùng biến thể (ngoại trừ chính nó)
        $validator->after(function ($validator) use ($request, $variant) {
            $exists = ProductVariant::where('product_id', $variant->product_id)
                ->where('color_id', $request->color_id)
                ->where('size_id', $request->size_id)
                ->where('id', '!=', $variant->id) // bỏ qua chính nó
                ->exists();

            if ($exists) {
                $validator->errors()->add('color_id', 'Biến thể với màu & size này đã tồn tại.');
                $validator->errors()->add('size_id', 'Biến thể với màu & size này đã tồn tại.');
            }
        });

        // 3. Nếu có lỗi thì ném ra ValidationException
        $data = $validator->validate();

        // 4. Upload ảnh mới nếu có
        if ($request->hasFile('image')) {
            $path_image = $request->file('image')->store('variants', 'public');
            $data['image'] = $path_image;

            // Xóa ảnh cũ
            if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                Storage::disk('public')->delete($variant->image);
            }
        }

         // 5. Cập nhật
        $variant->update($data);

        return redirect()->route('admin.variants.index', $variant->product_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $variant = ProductVariant::find($id);

        if (isset($path_image)) {
            if ($variant->image != null) {
                if (Storage::fileExists($variant->image)) {
                    Storage::delete($variant->image);
                }
            }
        }

        $variant->delete();

        return redirect()->route('admin.variants.index', $variant->product_id);
    }
}
