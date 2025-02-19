<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User; // Assurez-vous que le modèle User existe et est correctement configuré
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreaCompteController extends Controller


{
    
  /**
     * Crée un compte utilisateur.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
     
     
    public function createAccount(Request $request)
    {
        // Validation des données envoyées
        $validator = Validator::make($request->all(), [
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'tel'=> 'nullable|string|max:20',
            'email'    => 'required|email|unique:users,email',
            'mdp' => 'required|string|min:8', // adapter la taille minimale selon vos besoins
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Création de l'utilisateur en hashant le mot de passe
        $user = Client::create([
            'nom'      => $request->input('nom'),
            'prenom'   => $request->input('prenom'),
            'tel'=> $request->input('telephone'),
            'email'    => $request->input('email'),
            'mdp' => Hash::make($request->input('password')), // bcrypt est utilisé par défaut
            
            

        ]);

        return response()->json([
            'message' => 'Compte créé avec succès',
            'user_id' => $user->id,
        ], 201);
    }
}
