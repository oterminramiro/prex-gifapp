<?php

namespace App\Http\Requests;

use App\Exceptions\ApiFailedValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class GifFavoriteRequest extends FormRequest
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
            'user_id' => 'integer|required|exists:users,id',
            'alias' => 'required|string',
            'gif_id' => 'required|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ApiFailedValidationException($validator);
    }
}