<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $posts = Post::all();
        $posts = Post::with([
            'user',
            'category',
            // comments -> give only comments related data but
            // comments.user => give comments related data with the user info bcoz we have the relationship
            'comments.user',
            // 'comments',
            'tags',
        ])->get();

        return response()->json([
            'success' => true,
            'message' => 'Posts fetched successfully.',
            'data' => $posts,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            // 'cover_image' => 'nullable|string',
            'published_at' => 'nullable|date',
            'status' => 'required|in:draft,published',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        // tag id is not in post table so insert it by using relation
        // if the validated tag_ids are => [1,2,3] then $tagIds becomes [1,2,3]
        // if tag_ids = null then $tagIds = []
        $tagIds = $validated['tag_ids'] ?? [];

        // unset =:

        // Remove tag_ids from $validated

        //Now $validated changes from:

        // [
        //     'user_id' => 1,
        //     'category_id' => 2,
        //     'title' => 'Laravel Tutorial',
        //     'content' => 'Learning Laravel',
        //     'status' => 'published',
        //     'tag_ids' => [1, 3, 5]
        // ]

        // to:

        // [
        //'user_id' => 1,
        //'category_id' => 2,
        //'title' => 'Laravel Tutorial',
        //'content' => 'Learning Laravel',
        //'status' => 'published'
        //]

        unset($validated['tag_ids']);

        //after unsetting create the post i.e insert post data into post table
        $post = Post::create($validated);

        //Attach the tags to the pivot table (post_tag)

        // Suppose:

        // $post->id = 10;

        // and:

        // $tagIds = [1, 3, 5];

        // Then:

        // $post->tags()->sync($tagIds);  relationship defined in model

        // will create relationships roughly like:

        // post_tag
        // ----------------
        // post_id | tag_id
        // 10      | 1
        // 10      | 3
        // 10      | 5

        if (!empty($tagIds)) {
            $post->tags()->sync($tagIds);
        }

        // At this point our post exists, but i want to include the related data to the response .
        $post->load([
            'user',
            'category',
            'comments',
            'tags',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully.',
            'data' => $post,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with([
            'user',
            'category',
            'comments.user',
            'tags',
        ])->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Post fetched successfully.',
            'data' => $post,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not Found .',
            ], 404);
        }

        // Request
        //     │
        //     ▼
        // Is field present?
        //     │
        //     ├── NO ──→ Ignore validation for this field
        //     │
        //     └── YES ─→ Validate it

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'cover_image' => 'nullable|string',
            'published_at' => 'nullable|date',
            'status' => 'sometimes|in:draft,published',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        //     tag_ids not provided
        //     ↓
        //    null
        //     ↓
        //     Don't touch existing tags

        //     tag_ids: []
        //             ↓
        //            []
        //             ↓
        //     Remove all tags

        $tagIds = $validated['tag_ids'] ?? null;
        unset($validated['tag_ids']);

        $post->update($validated);

        if ($tagIds !== null) {
            $post->tags()->sync($tagIds);
        }

        $post->load([
            'user',
            'category',
            'comments',
            'tags',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully.',
            'data' => $post,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found.',
            ], 404);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully.',
        ], 200);
    }

    // remaining => likes and bookmarks same like comments so implement it
}
