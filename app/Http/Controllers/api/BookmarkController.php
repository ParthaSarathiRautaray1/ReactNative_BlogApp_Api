<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // same as like features

    public function index()
    {
        $bookmarks = Bookmark::with([
            'user',
            'post',
        ])->get();

        return response()->json([
            'status' => true,
            'message' => 'Bookmarks fetched successfully.',
            'data' => $bookmarks,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $bookmark = Bookmark::create([
            'post_id' => $validated['post_id'],
            'user_id' => auth()->id(),
        ]);

        // load the relation of bookmark with user and post before sending response
        $bookmark->load(['user', 'post']);

        return response()->json([
            'success' => true,
            'message' => 'Bookmark created Successfully .',
            'data' => $bookmark,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bookmark = Bookmark::with([
            'user',
            'post',
        ])->find($id);

        if (!$bookmark) {
            return response()->json([
                'status' => false,
                'message' => 'Bookmark not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Bookmark fetched successfully.',
            'data' => $bookmark,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bookmark = Bookmark::find($id);

        if (!$bookmark) {
            return response()->json([
                'status' => false,
                'message' => 'Bookmark not found.',
            ], 404);
        }

        $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $bookmark->update([
            'post_id' => $request->post_id,
        ]);

        // bookmarks got then its have its user and post by its relation so load it before sending response 
        $bookmark->load(['user' ,'post']);

        return response()->json([
            'status' => true,
            'message' => 'Bookmark updated successfully.',
            'data' => $bookmark,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bookmark = Bookmark::find($id);

        if (!$bookmark) {
            return response()->json([
                'status' => false,
                'message' => 'Bookmark not found.',
            ], 404);
        }

        $bookmark->delete();

        return response()->json([
            'status' => true,
            'message' => 'Bookmark deleted successfully.',
        ], 200);
    }
}
