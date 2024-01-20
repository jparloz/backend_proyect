<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;


class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(Comment::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $newCom = Comment::create($request->all());
        return new JsonResponse($newCom);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment): JsonResponse
    {
        return new JsonResponse($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $comment->update($request->only(['comment', 'rating']));

        return new JsonResponse($comment);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        return new JsonResponse(['msg'=>$comment->delete()]);
    }

    public function getCommentsByUser($userId): JsonResponse
    {
        $comments = Comment::where('user_id', $userId)
            ->with('game:id,name')
            ->get(['id as comment_id', 'comment', 'rating', 'created_at', 'updated_at', 'game_id'])
            ->makeHidden('game_id')
            ->map(function ($comment) {
                $comment->game_Title = $comment->game->name;
                unset($comment->game);
                return $comment;
            });

        return response()->json($comments);
    }
}
