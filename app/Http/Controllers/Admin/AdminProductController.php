<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPhotoAlbum;
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
                'description' => 'nullable|string|max:1000',
                'onpage' => 'required',
                'image' => 'required|image|max:2048',
                'album.*' => 'nullable|image|max:2048', // validate từng ảnh trong album
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
                'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
                'onpage.required' => 'Vui lòng chọn onpage.',
                'image.required' => 'Hình ảnh không được để trống.',
                'image.image' => 'Hình ảnh không hợp lệ.',
                'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
                'album.*.image' => 'Ảnh trong album không hợp lệ.',
                'album.*.max' => 'Ảnh trong album không vượt quá 2MB.',
            ]
        );          
            
        // Upload ảnh chính
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        
        // Tạo sản phẩm trước
        $product = Product::query()->create($data);

        // Upload album nếu có
        if ($request->hasFile('album')) {
            foreach ($request->file('album') as $file) {
                $path = $file->store('albums', 'public');
                ProductPhotoAlbum::create([
                    'product_id' => $product->id,
                    'image' => $path,
                ]);
            }
        }

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
            (object)['id' => 1, 'name' => 'Có'],
            (object)['id' => 0, 'name' => 'Không'],
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
                'description' => 'nullable|string|max:1000',
                'onpage' => 'required',
                'image' => 'nullable|image|max:2048',
                'album.*' => 'nullable|image|max:2048', // validate từng ảnh trong album
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
                'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
                'onpage.required' => 'Vui lòng chọn onpage.',
                'image.image' => 'Hình ảnh không hợp lệ.',
                'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
                'album.*.image' => 'Ảnh trong album không hợp lệ.',
                'album.*.max' => 'Ảnh trong album không vượt quá 2MB.',
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

    public function updateAlbum(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Nếu có file upload mới cho các ảnh cũ
        if ($request->has('existing_ids')) {
            foreach ($request->existing_ids as $albumId) {
                if ($request->hasFile("img_array_existing.$albumId")) {
                    $album = $product->photoAlbums()->find($albumId);
                    if ($album) {
                        // Xóa file cũ
                        Storage::disk('public')->delete($album->image);
                        // Upload file mới
                        $path = $request->file("img_array_existing.$albumId")->store('products/album', 'public');
                        $album->update(['image' => $path]);
                    }
                }
            }
        }

        // Nếu có thêm file mới
        if ($request->hasFile('img_array')) {
            foreach ($request->file('img_array') as $file) {
                $path = $file->store('products/album', 'public');
                $product->photoAlbums()->create(['image' => $path]);
            }
        }

        // Nếu có ảnh bị xóa
        if ($request->has('delete_ids')) {
            foreach ($request->delete_ids as $deleteId) {
                $album = $product->photoAlbums()->find($deleteId);
                if ($album) {
                    Storage::disk('public')->delete($album->image);
                    $album->delete();
                }
            }
        }

        return redirect()->back()->with('success', 'Cập nhật album thành công!');
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
