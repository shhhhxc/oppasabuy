<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display the community feed.
     */
    public function index()
    {
        // Path updated to 'feed.index' to match your folder structure
        // Added withCount and deep relationship loading for comments and replies
        $posts = Post::with(['user', 'likes', 'comments.user', 'comments.replies.user'])
            ->withCount('likes')
            ->latest()
            ->get();

        return view('feed.index', compact('posts'));
    }

    /**
     * Store a new post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->content = $request->content;

        if ($request->hasFile('image')) {
            // Stores in storage/app/public/posts
            $post->image = $request->file('image')->store('posts', 'public');
        }

        $post->save();

        return back()->with('success', 'Post shared to the community!');
    }

    /**
     * Toggle like/unlike for a post.
     */
    public function toggleLike(Post $post)
    {
        $userId = Auth::id();
        
        $existingLike = Like::where('post_id', $post->id)
                            ->where('user_id', $userId)
                            ->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            Like::create([
                'post_id' => $post->id,
                'user_id' => $userId,
            ]);
        }

        return back();
    }

    /**
     * Store a top-level comment.
     */
    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => null, 
        ]);

        return back()->with('success', 'Comment added!');
    }

    /**
     * Store a reply to a comment.
     */
    public function storeReply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        Comment::create([
            'post_id' => $comment->post_id,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $comment->id, // Links this comment as a reply to the parent
        ]);

        return back()->with('success', 'Reply added!');
    }
}