<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::all();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'product_code' => 'required|string|max:10|unique:products,product_code',
                'name' => 'required|string|unique:products,name',
                'category_id' => 'required|exists:categories,id',
                'material' => 'nullable|string',
                'description' => 'nullable|string|max:255',
                'onpage' => 'required',
                'image' => 'required|image|max:2048',
            ],
            [
                'product_code.required' => 'Mã sản phẩm không được để trống.',
                'product_code.string' => 'Mã sản phẩm phải là chuỗi.',
                'product_code.max' => 'Mã sản phẩm không được vượt quá 10 ký tự.',
                'product_code.unique' => 'Mã sản phẩm đã tồn tại.',
                'name.required' => 'Tên sản phẩm không được để trống.',
                'name.string' => 'Tên sản phẩm phải là chuỗi.',
                'name.unique' => 'Tên sản phẩm đã tồn tại.',
                'category_id.required' => 'Vui lòng chọn danh mục.',
                'material.string' => 'Chất liệu sản phẩm phải là chuỗi.',
                'description.string' => 'Mô tả phải là chuỗi.',
                'description.max' => 'Mô tả không được vượt quá 255 ký tự.',
                'onpage.required' => 'Vui lòng chọn onpage.',
                'image.required' => 'Hình ảnh không được để trống.',
                'image.image' => 'Hình ảnh không hợp lệ.',
                'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            ]
        );          
            

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        
        Product::query()->create($data);

        return redirect()->route('admin.products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        $reviews = $product->reviews()->with('user')->get();

        return view('admin.products.show', compact('product', 'reviews'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $onpages = [
            (object)['id' => 1, 'name' => 'On'],
            (object)['id' => 0, 'name' => 'Off'],
        ];
        return view('admin.products.edit', compact('product', 'categories', 'onpages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate(
            [
                'product_code' => [
                    'required',
                    'string',
                    'max:10',
                    Rule::unique('products', 'product_code')->ignore($product->id),
                ],
                'name' => [
                    'required',
                    'string',
                    Rule::unique('products', 'name')->ignore($product->id),
                ],
                'category_id' => 'required|exists:categories,id',
                'material' => 'nullable|string',
                'description' => 'nullable|string|max:255',
                'onpage' => 'required',
                'image' => 'nullable|image|max:2048',
            ],
            [
                'product_code.required' => 'Mã sản phẩm không được để trống.',
                'product_code.string' => 'Mã sản phẩm phải là chuỗi.',
                'product_code.max' => 'Mã sản phẩm không được vượt quá 10 ký tự.',
                'product_code.unique' => 'Mã sản phẩm đã tồn tại.',
                'name.required' => 'Tên sản phẩm không được để trống.',
                'name.string' => 'Tên sản phẩm phải là chuỗi.',
                'name.unique' => 'Tên sản phẩm đã tồn tại.',
                'category_id.required' => 'Vui lòng chọn danh mục.',
                'material.string' => 'Chất liệu sản phẩm phải là chuỗi.',
                'description.string' => 'Mô tả phải là chuỗi.',
                'description.max' => 'Mô tả không được vượt quá 255 ký tự.',
                'onpage.required' => 'Vui lòng chọn onpage.',
                'image.image' => 'Hình ảnh không hợp lệ.',
                'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            ]
        );          

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
           
        }
        
        $product->update($data);

        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if($product ->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete(); 

        return redirect()->route('admin.products.index');
    }

    /**
     * Hiển thị bình luận tương ứng với sản phẩm.
     */
    public function toggle(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $review->status = $review->status == 1 ? 0 : 1; // Chuyển đổi trạng thái
        $review->save();

        $productId = $review->product_id;
        return redirect()->route('admin.products.show', $productId);
    }
}
