<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\technicianRegisterRequest;
use App\Mail\MailOtpCode;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
                $isTechnician ? true : false,  // Si l'utilisateur est un technicien
                // 'isTechnician' => $isTechnician? true : false,  // Si l'utilisateur est un technicien
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la sélection du technicien.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function GetAllTechnicians()
    {
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

    public function GetTechnician($id)
    {
        try {
            $technician = User::where('id', $id)->where('status', 1)->first();
            return response()->json([
                'technician' => $technician,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération du technicien.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function Forgotten_password(Request $request)
    {
        // Rechercher l'utilisateur avec l'email donné
        $user = User::where('email', $request->email)->first();

        // Si l'utilisateur n'existe pas
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé!',
            ], 404);
        }

        try {
            // Générer un code OTP sécurisé
            $otpCode = [
                'email' => $request->email,
                'code' => rand(121111, 989898),
                'expires_at' => now()->addMinutes(10), // Expiration dans 10 minutes
            ];

            // Supprimer les anciens OTP pour cet utilisateur
            OtpCode::where('email', $request->email)->delete();

            // Enregistrer le nouveau OTP
            OtpCode::create($otpCode);

            // Envoyer le code OTP par email
            Mail::to($request->email)->send(new MailOtpCode($otpCode['code']));

            // Réponse JSON en cas de succès
            return response()->json([
                'message' => 'Le code OTP a été envoyé à votre adresse e-mail.',
                'otp_code' => $otpCode,
            ], 200);
        } catch (\Exception $e) {
            // Gestion des erreurs d'envoi d'email ou autres exceptions
            return response()->json([
                'message' => 'Erreur lors de l\'envoi du code OTP.',
                // 'error' => $e->getMessage(),
            ], 500); // Erreur interne du serveur
        }
    }

    public function Verify_otp(Request $request)
    {
        $otpCode = OtpCode::where('email', $request->email)->first();
        if (!$otpCode) {
            return response()->json([
                'message' => 'Please enter a valid email',
            ], 400);
        }
        if ($request->code == $otpCode->code) {
            return response()->json([
                'message' => 'OTP code verified successfully',
                'otp_code' => $otpCode,
            ], 200);
        }
        // OtpCode::where('email', $request->email)->delete();
        return response()->json([
            'message' => 'OTP code invalide',
        ], 400);
    }

    public function Reset_password(Request $request)
    {
        $code = OtpCode::where('email', $request->email)->first();

        if (!$code) {
            return response()->json([
                'message' => 'Please enter a valid email',
            ], 400);
        }

        if ($request->code != $code->code) {
            return response()->json([
                'message' => 'Code invalide',
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        // Supprimer le code OTP
        OtpCode::where('email', $request->email)->delete();
        return response()->json([
            'message' => 'Mot de passe modifié avec succes!',
            'user' => $user,
        ], 200);
    }

    public function UpdateUser(Request $request, $id)
    {
        try {
            $user = User::find($id);

            $validatedData = $request->validate([
                'lastname' => 'required|string|max:255|min:5',
                'firstname' => 'required|string|max:255 | min:5',
                'email' => 'nullable|email|unique:users,email,' . $user->id,  // Vérifie que l'email est unique mais autorise l'email actuel de l'utilisateur
                'phone' => 'required|string|max:255|regex:/^(\\+228)?[97]\\d{7}$/|unique:users,phone,' . $user->id,
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Valider le type d'image et sa taille
            ]);
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->phone = $request->phone;

            // Upload avatar

            if ($request->hasFile('avatar')) {
                // Supprimer l'ancien avatar si nécessaire
                if ($user->avatar && file_exists(public_path('uploads/' . $user->avatar))) {
                    unlink(public_path('uploads/' . $user->avatar));
                }

                // Enregistrer le nouvel avatar
                $file = $request->file('avatar');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $user->avatar = $filename;
            }
            $user->save();
            return response()->json([
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la modification de l\'utilisateur.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
public function UpdateTechnician(Request $request , $id ){
    try {
        $user = User::find($id);

        $validatedData = $request->validate([
            'lastname' => 'required|string|max:255|min:5',
            'firstname' => 'required|string|max:255 | min:5',
            'email' => 'nullable|email|unique:users,email,' . $user->id,  // Vérifie que l'email est unique mais autorise l'email actuel de l'utilisateur
            'phone' => 'required|string|max:255|regex:/^(\\+228)?[97]\\d{7}$/|unique:users,phone,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Valider le type d'image et sa taille
        ]);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Upload avatar

        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar si nécessaire
            if ($user->avatar && file_exists(public_path('uploads/' . $user->avatar))) {
                unlink(public_path('uploads/' . $user->avatar));
            }

            // Enregistrer le nouvel avatar
            $file = $request->file('avatar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $user->avatar = $filename;
        }
        $user->save();
        return response()->json([
            'user' => $user,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Une erreur est survenue lors de la modification de l\'utilisateur.',
            'error' => $e->getMessage()
        ], 500);
    }
}
    public function GetLocalization($id)
    {
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
