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

    public function getOpinions()
    {
        try {

            $opinions = Opinion::with('user')->latest()->take(3)->get();

            // Création d'un tableau avec des données formatées pour la réponse JSON
            //si le tableau est vide

            $opinionsData = $opinions->map(function ($opinion) {
                return [
                    'id' => $opinion->id,
                    'opinion' => $opinion->opinion,
                    'rate' => $opinion->rate,
                    'user_name' => $opinion->user->firstname . ' ' . $opinion->user->lastname,  // Utilisation de la relation 'user' pour récupérer le nom
                    'avatar' => $opinion->user->avatar,
                ];
            });

            return response()->json([
                'data' => $opinionsData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des opinions.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
