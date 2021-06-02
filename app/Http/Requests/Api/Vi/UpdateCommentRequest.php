<?php

namespace App\Http\Requests\Api\Vi;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'=>[
                'required',
                'email',
                'max:255'
            ],
            'author'=>[
                'required',
                'max:255'
            ],
            'comment'=>[
                'required',
                'max:65535'
            ],
        ];
    }
}
