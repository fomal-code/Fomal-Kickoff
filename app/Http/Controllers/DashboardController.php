<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function index()
{
    $stats = [
        'total_posts' => Post::count(),
        'published_posts' => Post::where('status', 'published')->count(),
        'draft_posts' => Post::where('status', 'draft')->count(),
        'total_comments' => Comment::count(),
        'pending_comments' => Comment::where('status', 'pending')->count(),
        'total_users' => User::count(),
        'admin_users' => User::where('role', 'admin')->count(),
        'subscriber_users' => User::where('role', 'subscriber')->count(),
    ];

    $recent_posts = Post::with('user')->latest()->take(5)->get();
    $recent_comments = Comment::with(['post', 'user'])->latest()->take(5)->get();
    $recent_users = User::latest()->take(5)->get();
    
    $monthly_posts = Post::select(
        DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
        DB::raw('count(*) as count')
    )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    $popular_posts = Post::withCount('comments')
        ->orderBy('views', 'desc')
        ->take(5)
        ->get();

    return view('admin.dashboard', compact(
        'stats',
        'recent_posts',
        'recent_comments',
        'recent_users',
        'monthly_posts',
        'popular_posts'
    ));
}
}