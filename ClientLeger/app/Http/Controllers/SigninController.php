<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class SigninController extends Controller
{
    /**
     * Authentifie un utilisateur.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function signin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mdp' => 'required'
        ]);

        $client = Client::where('email', $request->email)->first();

        if (!$client || !Hash::check($request->mdp, $client->mdp)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Identifiants incorrects'], 401);
            }
            
            return redirect('/sign')
                ->with('signin_error', 'Identifiants incorrects. Veuillez réessayer.')
                ->withInput($request->except('mdp'));
        }

        // Stocker les informations du client en session
        session(['client' => $client]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Connexion réussie',
                'client' => $client
            ]);
        }
        
        return redirect('/')
            ->with('success', 'Connexion réussie. Bienvenue ' . $client->prenom . ' ' . $client->nom . '!');
    }
    
    /**
     * Déconnecte l'utilisateur.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Supprimer la session client
        Session::forget('client');
        
        return redirect('/')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
