<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Validation\Rule;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('childrenRecursive')->whereNull('parent_id')->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with('childrenRecursive')->whereNull('parent_id')->get();

        return view('admin.categories.create', compact('categories'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    Rule::unique('categories')->where(function ($query) use ($request) {
                        return $query->where('parent_id', $request->parent_id);
                    }),
                ],
                'description' => 'nullable|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
            ],
            [
                'name.required' => 'Tên danh mục không được để trống.',
                'name.string' => 'Tên danh mục phải là chuỗi.',
                'name.unique' => $request->parent_id
                    ? 'Tên danh mục đã tồn tại trong danh mục cha này.'
                    : 'Danh mục đã tồn tại.',
                'description.string' => 'Mô tả phải là chuỗi.',
                'description.max' => 'Mô tả không được vượt quá 255 ký tự.',
                'parent_id.exists' => 'Danh mục cha không tồn tại hoặc đã bị xóa.',
            ]
        );

        Category::query()->create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm mới danh mục thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Hàm lấy toàn bộ ID không hợp lệ (chính nó + con cháu)
     */
    private function getInvalidParentIds($category)
    {
        $ids = [$category->id];

        foreach ($category->childrenRecursive as $child) {
            $ids = array_merge($ids, $this->getInvalidParentIds($child));
        }

        return $ids;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::with('childrenRecursive')->findOrFail($id);

        // Lấy danh sách ID không hợp lệ (chính nó + con cháu)
        $invalidIds = $this->getInvalidParentIds($category);

        // Lấy toàn bộ danh mục cha (bao gồm cả những cái sẽ bị disabled)
        $categories = Category::with('childrenRecursive')->whereNull('parent_id')->get();

        return view('admin.categories.edit', compact('category', 'categories', 'invalidIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::with('childrenRecursive')->findOrFail($id);

        // Lấy danh sách ID không hợp lệ
        $invalidIds = $this->getInvalidParentIds($category);

        // Lấy các danh mục cùng cấp (anh/chị/em) của category hiện tại
        $siblings = Category::where('parent_id', $category->parent_id)->where('id', '!=', $category->id)->pluck('id')->toArray();

        // Cấm chọn parent_id là chính nó, con cháu, hoặc danh mục cùng cấp
        $invalidIds = array_merge($invalidIds, $siblings);

        // Cấm việc chuyển danh mục cha có con → thành con
        if (
            $category->childrenRecursive->count() > 0 && 
            $request->parent_id != $category->parent_id && 
            $request->parent_id !== null
        ) {
            return back()->withErrors(['parent_id' => 'Không thể chuyển danh mục cha thành danh mục con khi nó đang có danh mục con.']);
        }

        if (in_array($request->parent_id, $siblings)) {
            return back()->withErrors(['parent_id' => 'Không thể chọn danh mục cùng cấp làm danh mục cha.']);
        }

        $data = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    Rule::unique('categories', 'name')
                    ->ignore($category->id)
                    ->where(function ($query) use ($request) {
                        return $query->where('parent_id', $request->parent_id);
                    }),
                ],
                'description' => 'nullable|string|max:255',
                'parent_id' => [
                    'nullable',
                    'integer',
                    Rule::notIn($invalidIds), // vẫn phải validate backend
                    Rule::exists('categories', 'id'),
                ],
            ],
            [
                'name.required' => 'Tên danh mục không được để trống.',
                'name.string' => 'Tên danh mục phải là chuỗi.',
                'name.unique' => $request->parent_id
                    ? 'Tên danh mục đã tồn tại trong danh mục cha này.'
                    : 'Danh mục đã tồn tại.',
                'description.string' => 'Mô tả phải là chuỗi.',
                'description.max' => 'Mô tả không được vượt quá 255 ký tự.',
                'parent_id.not_in' => 'Không thể chọn chính nó hoặc danh mục con làm cha.',
                'parent_id.exists' => 'Danh mục cha không hợp lệ.',
            ]
        );

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::with('childrenRecursive')->findOrFail($id);

        // Kiểm tra xem danh mục hoặc con cháu có sản phẩm không
        if ($category->hasProductsInTree()) {
            return redirect()->back()->withErrors('Không thể xóa vì danh mục hoặc danh mục con đang chứa sản phẩm!');
        }

        // Kiểm tra xem danh mục có con không (tuỳ bạn có cho phép xóa danh mục cha có con hay không)
        if ($category->childrenRecursive->count() > 0) {
            return redirect()->back()->withErrors('Không thể xóa danh mục cha khi còn danh mục con!');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}
