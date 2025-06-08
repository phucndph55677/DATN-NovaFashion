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
    public function index(Request $request)
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string|unique:categories,name',
                'description' => 'nullable|string|max:255',
            ],
            [
                'name.required' => 'Tên danh mục không được để trống.',
                'name.string' => 'Tên danh mục phải là chuỗi.',
                'name.unique' => 'Tên danh mục đã tồn tại.',
                'description.string' => 'Mô tả phải là chuỗi.',
                'description.max' => 'Mô tả không được vượt quá 255 ký tự.',
            ]
        );

        Category::query()->create($data);

        return redirect()->route('admin.categories.index');
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
        $category = Category::findOrFail($id);

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    Rule::unique('categories', 'name')->ignore($category->id),
                ],
                'description' => 'nullable|string|max:255',
            ],
            [
                'name.required' => 'Tên danh mục không được để trống.',
                'name.string' => 'Tên danh mục phải là chuỗi.',
                'name.unique' => 'Tên danh mục đã tồn tại.',
                'description.string' => 'Mô tả phải là chuỗi.',
                'description.max' => 'Mô tả không được vượt quá 255 ký tự.',
            ]
        );

        $category->update($data);

        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index');
    }
}
