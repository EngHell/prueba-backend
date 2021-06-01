<?php


namespace App\Http\Requests\Api;



use App\Traits\Http\Request\AlwaysExpectsJsonAndWantsJson;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidatesWhenResolvedTrait;
use Illuminate\Validation\ValidationException;

class ApiFormRequest extends FormRequest
{
    use AlwaysExpectsJsonAndWantsJson;
}
