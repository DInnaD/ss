<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentCollection;
use App\Comment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Comment::class);

        $comment = Comment::orderBy('created_at', 'desc')->get();

        //return $this->success(CommentResourceCollection::make($comment));
        return responce()->json(CommentResourceCollection::make($comment));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CommentStoreRequest $request
     * @return JsonResponse
     */
    public function store(CommentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::guard('api')->id();

        $comment = Comment::create($data);

        return $this->created(CommentResource::make($comment));
    }

    /**
     * Display the specified resource.
     *
     * @param Comment $comment
     * @return JsonResponse
     */
    public function show(Comment $comment): JsonResponse
    {
        return $this->success(CommentResource::make($comment));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CommentUpdateRequest $request
     * @param Comment $comment
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(CommentRequest $request, Comment $comment): JsonResponse
    {
        $this->authorize('update', $comment);

        $comment->update($request->validated());

        return $this->success(CommentResource::make($comment));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Comment $comment
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(['success' => true], 200);

        //return $this->success(CommentResource::make($comment));
    }
}
