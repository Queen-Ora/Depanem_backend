<?php

namespace App\Http\Controllers;


use App\Models\FinRequest;
use App\Models\RejRequest;
use App\Models\Request as RequestModel;
use App\Models\User;
use PhpParser\Node\Stmt\TryCatch;

class ReqController extends Controller
{
    public function SendRequest($technician_id, $user_id)
    {
        // php artisan serve --host 0.0.0.0 --port 8000
        // npm run dev:host
        // php artisan migrate:refresh --path=database/migrations/la migration.php
        try {
            // Création de la demande
            $Req = new RequestModel();
            $Req->user_id = $user_id;
            $Req->technician_id = $technician_id;
            // $Req->location = $request->localisation;

            //modification du champ isAvailable pour le technicien
            $technician = User::find($technician_id);
            $technician->isAvailable = 0;

            $technician->save();
            $Req->save();

            return response()->json([
                'message' => 'La demande a été bien envoyée',
                'data' => $Req
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Retourner les erreurs de validation avec les messages personnalisés
            return response()->json([
                'message' => 'Erreurs de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Retourner les erreurs générales
            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'envoi de la demande.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function AcceptService( $technician_id, $user_id)
    {
        $Req = new RequestModel();
        $Req->user_id = $user_id;
        $Req->technician_id = $technician_id;
        $Req->isChecked = 1;
        $Req->sender = 1;

        $Req->save();
    
        return response()->json([
           'message' => 'Le service a été accepté',
            'data' => $Req
        ], 200);
    }

    public function getRequestsByTechnician($technician_id)
    {
        try {
            // Récupérer les requêtes pour un technicien spécifique
            $requests = RequestModel::where('technician_id', $technician_id)
                ->where('isChecked', 0) // Utilise un filtre strict
                ->with('user') // Récupère les informations de l'utilisateur qui a envoyé la requête
                ->get();

          

            return response()->json([
                'message' => 'Requêtes récupérées avec succès',
                'data' => $requests
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des requêtes.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getRequestsByUser($user_id)
{
    try {
        // Récupérer les requêtes pour un utilisateur particulier
        $requests = RequestModel::where('user_id', $user_id)
            ->where('isChecked', 1) // Utilise un filtre strict
            ->where('sender', 1)
            ->with('user') // Récupère les informations de l'utilisateur qui a envoyé la requête
            ->get();

    

        // Convertir la collection en tableau
        $requestsArray = $requests->toArray();

        return response()->json([
            'message' => 'Requêtes récupérées avec succès',
            'data' => $requestsArray // Renvoie le tableau
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Une erreur est survenue lors de la sélection des requêtes.',
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function RejectRequest(){
    $RejReq=new RejRequest();
    $RejReq->save();

    return response()->json([
       'message' => 'La demande a été bien rejetée',
        'data' => $RejReq
    ], 200);
     
    }

    public function getRejRequests()
    {
        // Récupérer les requêtes rejetées
        $rejRequests = RejRequest::all();

        return response()->json([
           'message' => 'Requêtes rejetées récupérées avec succès',
            'data' => $rejRequests
        ], 200); 
    }

    public function FinRequest(){
        $FinReq=new FinRequest();
        $FinReq->save();
        return response()->json([
           'message' => 'La demande a été bien financée',
            'data' => $FinReq
        ], 200);
    }

    public function getFinRequests()
    {
        // Récupérer les requêtes rejetées
        $finRequests = FinRequest::all();

        return response()->json([
           'message' => 'Requêtes rejetées récupérées avec succès',
            'data' => $finRequests
        ], 200);
    }

    public function updateRequest($request_id)
    {
        try {
            // Mettre à jour le statut de la requête
            $request = RequestModel::find($request_id);
            $request->isChecked = 1;
            $request->save();

            return response()->json([
                'message' => 'Le statut de la requête a été mis à jour avec succès',
                'data' => $request
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour du statut de la requête.',
                // 'error' => $e->getMessage()
            ], 500);
        }
    }

    public function countCheckedRequests($technician_id)
    {
        $checkedRequestsCount = RequestModel::where('technician_id', $technician_id)
            ->where('isChecked', 0)
            ->count();

        return response()->json([
            // 'message' => 'Nombre de requêtes vérifiées récupérées avec succès',
            'count' => $checkedRequestsCount,
        ], 200);
    }
}
