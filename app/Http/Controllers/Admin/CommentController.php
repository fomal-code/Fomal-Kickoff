<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Comment::class);
        
        $status = $request->get('status', 'all');
        
        $comments = Comment::with(['post', 'user'])
                          ->when($status !== 'all', function($query) use ($status) {
                              return $query->where('status', $status);
                          })
                          ->latest()
                          ->paginate(20);
        
        return view('admin.comments.index', compact('comments', 'status'));
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        
        $data = $request->validate([
            'status' => 'required|in:approved,pending,spam,rejected',
        ]);
        
        $comment->update($data);
        
        return back()->with('success', 'Comment status updated!');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        
        $comment->delete();
        
        return back()->with('success', 'Comment deleted successfully!');
    }
}