<?php


namespace App\Http\Requests\Api;



use App\Traits\Http\Request\AlwaysExpectsJsonAndWantsJson;
use Illuminate\Foundation\Http\FormRequest;


class ApiFormRequest extends FormRequest
{
    public function wantsJson()
    {
        return true;
    }
}
