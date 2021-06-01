<?php

namespace App\Http\Controllers\Api\Vi;

use App\Http\Controllers\Api\ApiController;
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
    public function show($id)
    {
        $data =[];
        $movie = Http::get("http://www.omdbapi.com/?i=${id}&apikey=7b96b558");

        if($movie->status()==200){
            $movieData = $movie->json();
            if($movieData['Response']=='True'){
                $data['data'] = $movieData;
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
