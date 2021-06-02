<?php

namespace App\Http\Controllers\Api\Vi;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\Vi\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends ApiController
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, string $id)
    {
        $data =[];
        $movie = Http::get("http://www.omdbapi.com/?i=${id}&apikey=7b96b558");

        if($movie->status()==200){
            $movieData = $movie->json();
            if($movieData['Response']=='True'){
                $data['data'] = $movieData;
                $data['comments'] = CommentResource::collection(
                    Comment::where('title','=',$id)
                        ->orderBy('updated_at','DESC')
                        ->take(5)
                        ->get()
                );
            } else {
                switch ($movieData['Error']){
                    case 'Error getting data.':
                        $this->setNotFoundResponse();
                        break;
                    case 'Incorrect IMDb ID.':
                        $this->setInvalidInputResponse();
                        $this->message = 'incorrect ID format.';
                        break;
                    default:
                        $this->setInternalErrorResponse();
                        break;
                }
            }
        } else {
            $this->setInternalErrorResponse();
        }

        return $this->apiResponse($data);
    }

    public function indexComments(Request $request, string $id){
        $data = [];

        try {
            $comments = Comment::where('title','=',$id)->paginate(5);
            $data = CommentResource::collection($comments)->additional(
                ['code'=>$this->code,'message'=>'message']
            );
        } catch (\Exception $e){
            $this->setInvalidInputResponse();
        }

        return $this->apiResponse($data);
    }
}
