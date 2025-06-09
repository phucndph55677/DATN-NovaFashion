<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;


class AdminCommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::latest();

        // Tìm kiếm theo từ khóa
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('content', 'like', "%$keyword%")
                    ->orWhere('user_id', 'like', "%$keyword%") // nếu muốn tìm theo user
                    ->orWhere('product_id', 'like', "%$keyword%");
            });
        }

        // Lọc theo product_id nếu có
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }


        // Phân trang
        $comments = $query->paginate(10)->withQueryString();

        return view('admin.comments.index', compact('comments'));
    }
    public function toggleStatus(Comment $comment)
    {
        $comment->status = ($comment->status + 1) % 3;
        $comment->save();

        return back()->with('success', 'Trạng thái bình luận đã được cập nhật.');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.comments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request)
    {
        $validated = $request->validated();

        Comment::create($validated);

        return redirect()->route('admin.comments.index')->with('success', 'Bình luận đã được thêm thành công.');
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    public function edit(string $id)
    {
        $comment = Comment::findOrFail($id);
        return view('admin.comments.edit', compact('comment'));
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(CategoryRequest $request, string $id)
    // {
    //     $category = Category::findOrFail($id);
    //     $validated = $request->validated();

    //     $category->update($validated);

    //     return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công.');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return redirect()->route('admin.comments.index')->with('success', 'Xóa bình luận thành công.');
    }
}
