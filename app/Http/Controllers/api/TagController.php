<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::with('posts')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Tags fetched successfully.',
            'data' => $tags,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $tag = Tag::create($validated);

        $tag->load('posts');
        return response()->json([
            'success' => true,
            'message' => 'Tag created Successfully .',
            'data' => $tag,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tag = Tag::with('posts')->find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found.',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Tag fetched successfully',
            'data' => $tag,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found.',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string'
        ]);

        $tag->update($validated);

        $tag->load('posts');

        return response()->json([
            'success' => true,
            'message' => 'Tag updated successfully',
            'data' => $tag,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found.',
            ], 404);
        }

        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag Deleted successfully',
            'data' => $tag,
        ], 200);

    }
}
