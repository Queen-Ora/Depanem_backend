<?php

namespace App\Http\Controllers;

use App\Models\Opinion;
use Illuminate\Http\Request;

class OpinionController extends Controller
{
    public function PublishOpinion(Request $request, $id)
    {
        try {
            // Validation des champs avec messages personnalisés
            $validatedData = $request->validate([
                'opinion' => 'required|max:100'
            ], [
                'opinion.required' => 'L\'opinion est obligatoire.',
                'opinion.max' => 'L\'opinion ne peut pas dépasser 100 caractères.',
            ]);
            // Créer et sauvegarder l'opinion
            $opinion = new Opinion();
            $opinion->user_id = $id;
            $opinion->opinion = $request->opinion;
            $opinion->rate = $request->rate;
            $opinion->save();
    
            // Retourner la réponse JSON
            return response()->json([
                'opinion' => $opinion,
                'message' => 'Opinion publiée avec succès !'
            ], 201);
   
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Retourner les erreurs de validation
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }
}