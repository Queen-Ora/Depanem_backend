<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReqRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
         
            'localisation' => [
                'required',
                'string',
                'max:255',
                'min:5',
                'regex:/^(https?:\/\/)?(www\.)?(maps\.google\.com|goo\.gl|maps\.app\.goo\.gl)\/.*$/'
            ],
            'message' => 'nullable',
            
        ];
    }
    public function messages(): array
    {
        return [
            'localisation.regex' => 'Veuillez fournir une URL valide pour Google Maps.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Erreurs de validation',
            'data'      => $validator->errors()
        ], 422));
    }
}
