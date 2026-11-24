<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function index(Request $request): View
    {
        $posts = Post::query()
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . addcslashes($request->input('q'), '%_\\') . '%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->latest('id')->paginate(12)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.posts.form', ['post' => new Post(['status' => 'draft'])]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('cover_image');
        $data['slug'] = Post::makeSlug($data['title']);
        $data['created_by'] = $request->user()->id;
        $data['published_at'] = $this->publishedAt($data);

        if ($file = $request->file('cover_image')) {
            $data['cover_image'] = $this->images->store($file, 'berita');
        }

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Berita ditambahkan.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $data = $request->safe()->except('cover_image');

        // Alamat (slug) sengaja tidak berubah setelah terbit agar tautan lama tidak mati.
        if ($post->status !== 'published' && $data['title'] !== $post->title) {
            $data['slug'] = Post::makeSlug($data['title'], $post->id);
        }

        $data['published_at'] = $this->publishedAt($data, $post);
        $data['cover_image'] = $this->images->replace($request->file('cover_image'), $post->cover_image, 'berita');
        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Berita diperbarui.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete-record', $post);
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Berita dihapus.');
    }

    /** Tanggal terbit: pakai isian pengelola, jika kosong dan status terbit pakai sekarang. */
    private function publishedAt(array $data, ?Post $post = null): ?string
    {
        if (! empty($data['published_at'])) {
            return $data['published_at'];
        }

        if ($data['status'] === 'published') {
            return ($post?->published_at ?? now())->toDateTimeString();
        }

        return null;
    }
}
