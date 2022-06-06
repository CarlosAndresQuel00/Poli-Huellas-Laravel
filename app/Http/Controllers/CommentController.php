<?php

namespace App\Http\Controllers;

use App\Mail\NewComment;
use App\Models\Articl;
use App\Models\Comment;
use App\Http\Resources\Comment as CommentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    /**
     * @param Articl $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Articl $article)
    {
        $comment = $article->comments;
        return response()->json(CommentResource::collection($comment), 200);
    }

    /**
     * @param Articl $article
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Articl $article, Comment $comment)
    {
        $comments = $article->comments()->where('id', $comment->id)->firstOrFail();
        return response()->json(new CommentResource($comments), 200);
    }

    /**
     * @param Request $request
     * @param Articl $articl
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Articl $article)
    {
        $request->validate([
            'text' => 'required|string'
        ]);
        $comments = $article->comments()->save(new Comment($request->all())); // Comment of article
        Mail::to($article->user)->send(new NewComment($comments)); // Send email each time to create a new comment
        return response()->json(new CommentResource($comments), 201);
    }

    public function update(Request $request, Comment $comment)
    {
        /*
        $article->update($request->all()); // It's updating, directly without looking for in DB
        return response()->json($article, 200);
        */
    }

    public function delete(Comment $comment)
    {
        /*
        $article->delete(); // It's deleting
        return response()->json(null, 204); // Empty content or nothing, all okay.
        */
    }
}
