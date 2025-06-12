<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function toggle(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->status = $comment->status == 1 ? 0 : 1; // Chuyển đổi trạng thái
        $comment->save();

        $productId = $comment->product_id;
        return redirect()->route('admin.products.show', $productId);
    }
}