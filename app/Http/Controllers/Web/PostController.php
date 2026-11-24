<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\View\View;

class PostController extends Controller
{
    public function show(Post $post): View
    {
        abort_unless(Post::published()->whereKey($post->id)->exists(), 404);

        $related = Post::published()->where('id', '!=', $post->id)->latest('published_at')->take(3)->get();

        return view('web.posts.show', compact('post', 'related'));
    }
}
