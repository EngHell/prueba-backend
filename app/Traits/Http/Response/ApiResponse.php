<?php


namespace App\Traits\Http\Response;


use App\Http\Controllers\Api\ErrorCodes;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponse
{
    protected $code = 0;
    protected $message = '';
    protected $httpCode = '200';

    public function setCode($code){
        $this->code =$code;
    }

    public function setMessage($message){
        $this->message = $message;
    }

    public function setHttpCode($httpCode){
        $this->httpCode = $httpCode;
    }

    public function setResponseParams($code, $message, $httpCode){
        $this->code = $code;
        $this->message = $message;
        $this->httpCode = $httpCode;

    }

    public function setNotFoundResponse(){
        $this->setResponseParams(ErrorCodes::NOT_FOUND,'not found',404);
    }

    public function setUnauthenticatedResponse(){
        $this->setResponseParams(ErrorCodes::UNAUTHENTICATED, 'unauthenticated',401);
    }

    public function setUnauthorizedResponse(){
        $this->setResponseParams(ErrorCodes::UNAUTHORIZED,'unauthorized',403);
    }

    public function setInvalidInputResponse(){
        $this->setResponseParams(ErrorCodes::INVALID_INPUT,'invalid input', 422);
    }

    public function setInvalidAssignationResponse() {
        $this->setResponseParams(ErrorCodes::INVALID_ASSIGNATION, 'invalid assignation', 422);
    }

    public function setInternalErrorResponse(){
        $this->setResponseParams(ErrorCodes::INTERNAL_ERROR, 'internal error', 500);
    }

    /**
     * @param array $data
     * @return \Illuminate\Http\JsonResponse|AnonymousResourceCollection
     */
    public function apiResponse($data=[])
    {
        if($data instanceof AnonymousResourceCollection | $data instanceof ResourceCollection){
            return $data;
        }

        $data["code"]= $this->code;
        $data["message"]= $this->message;
        return response()->json($data,$this->httpCode);
    }

}
