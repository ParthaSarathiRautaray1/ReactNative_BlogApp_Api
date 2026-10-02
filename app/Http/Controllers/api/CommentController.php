<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::with(['user', 'post'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Comments fetched successfully.',
            'data' => $comments,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ]);

        $comment = Comment::create([
            'post_id' => $validated['post_id'],
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        // load its user  , post related data to the response
        $comment->load(['user', 'post']);

        return response()->json([
            'status' => true,
            'message' => 'Comment created Successfully .',
            'data' => $comment,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = Comment::with(['user', 'post'])->find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Comment fetched successfully',
            'data' => $comment,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not Found .',
            ], 404);
        }
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update([
            'content' => $validated['content'],
        ]);

        $comment->load(['user', 'post']);

        return response()->json([
            'status' => true,
            'message' => 'Comment updated successfully',
            'data' => $comment,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not Found .',
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'status' => true,
            'message' => 'Comment deleted successfully',
        ], 200);
    }
}
