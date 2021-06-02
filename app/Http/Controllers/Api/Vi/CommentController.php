<?php

namespace App\Http\Controllers\Api\Vi;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Vi\StoreCommentRequest;
use App\Http\Resources\Api\Vi\CommentResource;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CommentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCommentRequest $request)
    {
        $data = [];

        $input = $request->validated();
        $comment = new Comment($input);
        try {
            $a = \DB::transaction(function() use($comment){
                $comment->save();
            });

            $data["id"] = $comment->id;
            $data["url"] = route("api.v1.comment.show",$comment);
        } catch (\Exception $e){
            $this->setInternalErrorResponse();
        }

        return $this->apiResponse($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $data = [];
        $comment = Comment::find($id);
        if($comment){
            $data['data'] = new CommentResource($comment);
        } else {
            $this->setResponseParams(1,'Not found',404);
        }

        return $this->apiResponse($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCommentRequest $request, int $id)
    {
        $data = [];
        //to avoid a current issue with wantsjson i will avoid dependency injection.
        $comment = Comment::find($id);

        if($comment){
            $validated = $request->validated();
            $comment->author = $validated['author'];
            $comment->email = $validated['email'];
            $comment->comment = $validated['comment'];
            try {
                $a = \DB::transaction(function() use($comment){
                    $comment->save();
                });

                $data['data'] = new CommentResource($comment);
            } catch (\Exception $e){
                $this->setInternalErrorResponse();
            }
        } else {
            $this->setNotFoundResponse();
        }

        return $this->apiResponse($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $data = [];
        //to avoid a current issue with wantsjson i will avoid dependency injection.
        $comment = Comment::find($id);

        if($comment){
            try {
                $a = \DB::transaction(function() use($comment){
                    $comment->delete();
                });
            } catch (\Exception $e){
                $this->setInternalErrorResponse();
            }
        } else {
            $this->setNotFoundResponse();
        }

        return $this->apiResponse($data);
    }
}
