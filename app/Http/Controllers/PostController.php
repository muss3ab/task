<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class PostController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return auth()->user()->posts()
            ->with('tags')
            ->orderBy('pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'required|image',
            'pinned' => 'required|boolean',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $path = $request->file('cover_image')->store('covers', 'public');
        
        $post = auth()->user()->posts()->create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'cover_image' => $path,
            'pinned' => $validated['pinned']
        ]);

        $post->tags()->attach($validated['tags']);

        return $post->load('tags');
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return $post->load('tags');
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'nullable|image',
            'pinned' => 'required|boolean',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id'
        ]);

        if ($request->hasFile('cover_image')) {
            Storage::disk('public')->delete($post->cover_image);
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $post->update($validated);
        $post->tags()->sync($validated['tags']);

        return $post->load('tags');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function deleted()
    {
        return auth()->user()->posts()
            ->onlyTrashed()
            ->with('tags')
            ->get();
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $post);
        $post->restore();
        return $post->load('tags');
    }
}
