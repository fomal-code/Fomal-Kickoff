@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Comments Management</h1>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Author</th>
                            <th>Comment</th>
                            <th>Post Title</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                        <tr>
                            <td><strong>{{ $comment->user->name }}</strong></td>
                            <td>{{ Str::limit($comment->content, 60) }}</td>
                            <td>
                                <a href="{{ route('post.show', $comment->post->slug) }}" target="_blank" class="text-decoration-none">
                                    {{ Str::limit($comment->post->title, 40) }}
                                    <i class="fas fa-external-link-alt ms-1 small"></i>
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-{{ $comment->status == 'approved' ? 'success' : ($comment->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($comment->status) }}
                                </span>
                            </td>
                            <td>{{ $comment->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if($comment->status != 'approved')
                                    <form action="{{ route('admin.comments.update', $comment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    @if($comment->status != 'rejected')
                                    <form action="{{ route('admin.comments.update', $comment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-warning" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this comment permanently?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No comments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $comments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection