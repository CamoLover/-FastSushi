<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreaCompteController extends Controller
{
    /**
     * Crée un compte utilisateur.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function createAccount(Request $request)
    {
        // Validation des données envoyées
        $validator = Validator::make($request->all(), [
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'telephone'=> 'nullable|string|max:20',
            'email'    => 'required|email|unique:clients,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            // Si la requête est une API, retourner une réponse JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Sinon, rediriger vers le formulaire avec les erreurs
            return redirect('/sign')
                ->with('signup_error', 'Erreur dans le formulaire. Veuillez vérifier vos informations.')
                ->withErrors($validator)
                ->withInput();
        }

        // Création de l'utilisateur en hashant le mot de passe
        $user = Client::create([
            'nom'      => $request->input('nom'),
            'prenom'   => $request->input('prenom'),
            'tel'      => $request->input('telephone'),
            'email'    => $request->input('email'),
            'mdp'      => Hash::make($request->input('password')),
        ]);

        // Si la requête est une API, retourner une réponse JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Compte créé avec succès',
                'user_id' => $user->id_client,
            ], 201);
        }
        
        // Sinon, rediriger vers la page d'accueil avec un message de succès
        session(['client' => $user]);
        return redirect('/')
            ->with('success', 'Compte créé avec succès. Bienvenue chez Fast Sushi!');
    }
}
