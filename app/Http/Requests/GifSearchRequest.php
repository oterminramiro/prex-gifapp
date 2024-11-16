<?php

namespace App\Http\Requests;

use App\Exceptions\ApiFailedValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class GifSearchRequest extends FormRequest
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
            'query' => 'required|string',
            'limit' => 'int',
            'offset' => 'int',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ApiFailedValidationException($validator);
    }
}