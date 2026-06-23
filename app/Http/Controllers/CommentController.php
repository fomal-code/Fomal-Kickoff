<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['post', 'user'])->latest()->paginate(20);
        return view('admin.comments.index', compact('comments'));
    }

    public function update(Request $request, Comment $comment)
    {
        $comment->update(['status' => $request->status]);
        return back()->with('success', 'Comment status updated!');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment deleted!');
    }
}