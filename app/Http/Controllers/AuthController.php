<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\technicianRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerUser(RegisterRequest $request)
    {
        try {
            //  return response()->json($request->all());
            $user = new User();
            $user->lastname = $request->LastName;
            $user->firstname = $request->FirstName;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->status = 0;
            $user->password = Hash::make($request->password);

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $user->avatar = $filename;
            }


            //  Mail::to($request->email)->send(new AccountMail($request->name));
            $user->save();
            return response()->json([
                'message' => 'Inscription réussie!',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'inscription.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function TechnicianRegister(technicianRegisterRequest $request)
    {

        try {
            //  return response()->json($request->all());
            //   mot de passe test : z4Cm&042
            $user = new User();
            $user->lastname = $request->LastName;
            $user->firstname = $request->FirstName;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->profession = $request->profession;
            $user->matricule = $request->matricule;
            $user->localisation = $request->localisation;
            $user->status = 1;
            $user->password = Hash::make($request->password);

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $user->avatar = $filename;
            }

            //  Mail::to($request->email)->send(new AccountMail($request->name));
            $user->save();
            return response()->json([
                'message' => 'Inscription spécici!',
                'user' => $user,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'inscription.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function Login(Request $request)
    {
        try {
            $user = User::where('phone', $request->email)
                ->orWhere('email', $request->email)
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Email ou mot de passe incorrect'
                ], 401);
            }
            // $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'user' => $user,
                // 'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la connexion.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function listUsers()
    {
        try {
            $users = User::all();
            return response()->json([
                'users' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des utilisateurs.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function CurrentUser($id)
    {
        try {
            $user = User::find($id);

            if ($user) {
                // Ajout de l'URL complète de l'avatar
                $user->avatar = $user->avatar
                    ? url('uploads/' . $user->avatar)  // Si l'avatar est personnalisé
                    : asset('default.png');  // Si l'avatar est par défaut


                return response()->json([
                    'user' => $user,
                    // 'avatar_url' => asset('uploads/'. $user->avatar),
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Utilisateur non trouvé',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la sélection de l\'utilisateur.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function CountUsers()
    {
        try {
            $users = User::where('status', 0)->get();
            return response()->json([
                count($users)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la sélection des utilisateurs.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function CountTechnicians()
    {
        try {
            $technicians = User::where('status', 1)->get();
            return response()->json([
                count($technicians)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la sélection des techniciens.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function CheckIsTechnician($id)
    {
        try {
            $isTechnician = User::where('id', $id)->where('status', 1)->first(); 
            return response()->json([
                $isTechnician? true : false,  // Si l'utilisateur est un technicien
                // 'isTechnician' => $isTechnician? true : false,  // Si l'utilisateur est un technicien
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la sélection du technicien.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function GetAllTechnicians(){
        try {
            $technicians = User::where('status', 1)
            ->select('id', 'firstname', 'avatar', 'profession')
            ->get()
            ->groupBy('profession');

            $formattedTechnicians = [];

            foreach ($technicians as $profession => $techniciansList) {
                $formattedTechnicians[$profession] = $techniciansList->map(function ($technician) {
                    return [
                        'id' => $technician->id,
                        'name' => $technician->firstname,
                        'avatar' => $technician->avatar,
                    ];
                })->toArray();
            }
            
            return response()->json($formattedTechnicians);
           
           
            // return response()->json(['technicians' => $formattedTechnicians]);
            
            // return response()->json([
            //     $technicians
            // ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la 선택 des techniciens.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function GetLocalization($id){
        try {
            $user = User::where('id', $id)->where('status', 0)->first(); 
            return response()->json([
                'localisation' => $user->localisation,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération de la localisation.',
                'error' => $e->getMessage()
            ], 500);
        }

    }
}
