<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            'FirstName' => ['required', 'string', 'max:255','min:5'],
            'LastName' => ['required', 'string', 'max:255','min:5'],
            'email' => ['required', 'string', 'email', 'max:255','min:8', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
            'password_confirmation' => ['required', 'string', 'min:8'],
          'phone' => ['required', 'regex:/^(\+228)?[9]\d{7}$/', 'unique:users'],
            'avatar' => ['nullable','image','mimes:jpeg,jpg,png,gif','max:2048'],
    
        ];
        
     
    }
    public function messages(): array
    {
        return [
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'phone.min' => 'Le numéro de téléphone doit contenir au moins 8 caractères.',
            'avatar.image' => 'Le fichier doit être une image.',
            'avatar.mimes' => 'Le fichier doit être une image au format jpeg, jpg, png ou gif.',
            'avatar.max' => 'Le fichier doit avoir moins de 2 Mo.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'phone.regex' => 'Le numéro de téléphone doit être valide, commencer par 9 et comporter 8 chiffres, avec ou sans le code pays (+228).',
            'password.confirmed' => 'Les mots de passe ne sont pas identiques.',
            'password_confirmation.required' => 'Veuillez confirmer votre mot de passe.',
            'FirstName.required' => 'Veuillez entrer votre prénom.',
            'FirstName.min' => 'Votre prénom doit faire au moins 5 caractères.',
            'FirstName.max' => 'Votre prénom ne doit pas faire plus de 255 caractères.',
            'LastName.required' => 'Veuillez entrer votre nom.',
            'LastName.min' => 'Votre nom doit faire au moins 5 caractères.',
            'LastName.max' => 'Votre nom ne doit pas faire plus de 255 caractères.',

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
