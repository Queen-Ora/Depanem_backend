<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
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
         
         if($request->hasFile('avatar')){
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
}
