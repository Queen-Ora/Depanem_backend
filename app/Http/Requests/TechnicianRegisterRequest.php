<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class technicianRegisterRequest extends FormRequest
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
            'FirstName' => ['required', 'string', 'max:255', 'min:5'],
            'LastName' => ['required', 'string', 'max:255', 'min:5'],
            'email' => ['required', 'string', 'email', 'max:255', 'min:8', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*.-]).{8,}$/'],
            'password_confirmation' => ['required', 'string', 'min:8'],
            'phone' => [
                'required',
                'regex:/^(\\+228)?[97]\\d{7}$/',
                'unique:users'
            ],
            'profession' => ['required', 'string', 'max:255', 'min:5'],
            'matricule' => ['required', 'string', 'max:255', 'min:5'],
            'localisation' => [
                'required',
                'string',
                'max:255',
                'min:5',
                'regex:/^(https?:\/\/)?(www\.)?(maps\.google\.com|goo\.gl|maps\.app\.goo\.gl)\/.*$/'
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048'],       
        ];

    }
    public function messages(): array
    {
        return [
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'phone.min' => 'Le numéro de téléphone doit contenir au moins 8 caractères.',
            'avatar.image' => 'Le fichier doit être une image.',
            'localisation.regex' => 'Veuillez fournir une URL valide pour Google Maps.',
            'avatar.mimes' => 'Le fichier doit être une image au format jpeg, jpg, png ou gif.',
            'avatar.max' => 'Le fichier doit avoir moins de 2 Mo.',
            'email.unique' => 'Cette adresse e-mail est deja utilisée.',
            'phone.unique' => 'Ce numero de telephone est deja utilisé.',
            'phone.regex' => 'Le numero de telephone doit etre valide, commencer par 9 ou 7 et comporter 8 chiffres, avec ou sans le code pays (+228).',
            'password.confirmed' => 'Les mots de passe ne sont pas identiques.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Erreurs de validation',
            'data'      => $validator->errors()
        ], 422));
    }
}
