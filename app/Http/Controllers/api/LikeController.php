<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $likes = Like::with(['post', 'user'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Likes fetched successfully.',
            'data' => $likes,
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

        $like = Like::create([
            'post_id' => $validated['post_id'],
            'user_id' => auth()->id(),
        ]);

        // load the relation of like with user and post before sending response
        $like->load(['user', 'post']);

        return response()->json([
            'success' => true,
            'message' => 'Like created Successfully .',
            'data' => $like,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $like = Like::with(['post', 'user'])->find($id);

        if (!$like) {
            return response()->json([
                'success' => false,
                'message' => 'Like not Found .',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Like fetched successfully',
            'data' => $like,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $like = Like::find($id);

        $like = Like::where('id' , $id)->where('user_id' , auth()->id())->first();

        if (!$like) {
            return response()->json([
                'success' => false,
                'message' => 'Like not Found .',
            ], 404);
        }

        $like -> delete();

        return response()->json([
            'success' => true,
            'message' => 'Like deleted successfully',
        ], 200);
    }
}
