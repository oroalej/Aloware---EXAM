<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function index(): JsonResponse
    {
        $comments = Comment::query()
            ->with('nestedChildren')
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return response()->json(CommentResource::collection($comments), Response::HTTP_OK);
    }

    public function store( PostCommentRequest $request ): JsonResponse
    {
        $comment = new Comment([
            'name'    => $request->name,
            'message' => $request->message,
            'depth'   => $request->depth + 1
        ]);

        if ($request->filled('parent_id')) {
            $comment->parent()->associate($request->parent_id);
        }

        $comment->save();

        return response()->json(new CommentResource($comment), Response::HTTP_CREATED);
    }
}
