<?php

namespace App\Http\Controllers;

use App\Mail\NewComment;
use App\Models\Pet;
use App\Models\Comment;
use App\Http\Resources\Comment as CommentResource;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    /**
     * @param Pet $pet
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Pet $pet)
    {
        $comment = $pet->comments;
        return response()->json(CommentResource::collection($comment), 200);
    }

    /**
     * @param Pet $pet
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Pet $pet, Comment $comment)
    {
        $comments = $pet->comments()->where('id', $comment->id)->firstOrFail();
        return response()->json(new CommentResource($comments), 200);
    }

    /**
     * @param Request $request
     * @param Pet $pet
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Pet $pet)
    {
        $request->validate([
            'text' => 'required|string',
        ]);
        $comments = $pet->comments()->save(new Comment($request->all())); // Comment of article
        $pet->user->notify(new NewCommentNotification($pet));
        return response()->json(new CommentResource($comments), 201);
    }

    public function update(Request $request, Pet $pet)
    {
        $pet->comments()->update($request->all()); // It's updating, directly without looking for in DB
        return response()->json($pet, 200);
    }

    public function delete(Pet $pet)
    {
        $pet->comments()->delete(); // It's deleting
        return response()->json(null, 204); // Empty content or nothing, all okay.
    }
}
