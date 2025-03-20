<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\Panier;
use App\Models\Panier_ligne;
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
        
        // Convertir le panier cookie en panier base de données s'il existe
        $cookieCart = json_decode($request->cookie('panier'), true);
        
        if (!empty($cookieCart)) {
            // Créer ou récupérer le panier de l'utilisateur
            $panier = Panier::firstOrCreate(
                ['id_client' => $client->id_client],
                [
                    'id_session' => session()->getId(),
                    'date_panier' => now(),
                    'montant_tot' => 0
                ]
            );
            
            // Transférer les éléments du cookie vers la base de données
            $panierController = new PanierController();
            foreach ($cookieCart as $item) {
                $panierController->addItemToPanier($item, $panier, $client->id_client);
            }
            
            // Recalculer le montant total du panier
            $montantTotal = $panier->panier_lignes->sum(function ($ligne) {
                return $ligne->prix_ttc * $ligne->quantite;
            });
            
            // Mise à jour du montant total du panier
            $panier->montant_tot = $montantTotal;
            $panier->save();
            
            // Créer le cookie pour vider le panier
            $emptyCartCookie = cookie('panier', '', -1);
        }
        
        if ($request->expectsJson()) {
            $response = response()->json([
                'message' => 'Connexion réussie',
                'client' => $client
            ]);
            
            // Ajouter le cookie pour vider le panier si nécessaire
            if (isset($emptyCartCookie)) {
                $response->withCookie($emptyCartCookie);
            }
            
            return $response;
        }
        
        $response = redirect('/')
            ->with('success', 'Connexion réussie. Bienvenue ' . $client->prenom . ' ' . $client->nom . '!');
            
        // Ajouter le cookie pour vider le panier si nécessaire
        if (isset($emptyCartCookie)) {
            $response->withCookie($emptyCartCookie);
        }
        
        return $response;
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
