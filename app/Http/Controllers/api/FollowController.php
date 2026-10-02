<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $follows = Follow::with([
            'follower',
            'following',
        ])->get();

        return response()->json([
            'status' => true,
            'message' => 'Follows fetched successfully.',
            'data' => $follows,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'following_id' => 'required|exists:users,id',
        ]);

        if (auth()->id() == $validated['following_id']) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot follow yourself.',
            ], 422);
        }

        $existingFollow = Follow::where('follower_id', auth()->id())
            ->where('following_id', $validated['following_id'])
            ->first();

        if ($existingFollow) {
            return response()->json([
                'status' => false,
                'message' => 'You are already following this user.',
            ], 409);
        }

        $follow = Follow::create([
            'follower_id' => auth()->id(),
            'following_id' => $validated['following_id'],
        ]);

        $follow->load([
            'follower',
            'following',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User followed successfully.',
            'data' => $follow,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $follow = Follow::with([
            'follower',
            'following',
        ])->find($id);

        if (!$follow) {
            return response()->json([
                'status' => false,
                'message' => 'Follow record not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Follow fetched successfully.',
            'data' => $follow,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $follow = Follow::find($id);

        if (!$follow) {
            return response()->json([
                'status' => false,
                'message' => 'Follow record not found.',
            ], 404);
        }

        $validated = $request->validate([
            'following_id' => 'required|exists:users,id',
        ]);

        $follow->update([
            'following_id' => $validated['following_id'],
        ]);

        $follow->load([
            'follower',
            'following',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Follow updated successfully.',
            'data' => $follow,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $follow = Follow::find($id);

        if (!$follow) {
            return response()->json([
                'status' => false,
                'message' => 'Follow record not found.',
            ], 404);
        }

        $follow->delete();

        return response()->json([
            'status' => true,
            'message' => 'User unfollowed successfully.',
        ], 200);
    }
}
