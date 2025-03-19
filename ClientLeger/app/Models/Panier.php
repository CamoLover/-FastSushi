<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Panier extends Model
{
    
    protected $table = 'paniers';
    protected $primaryKey = 'id_panier';
    public $timestamps = false; 

    protected $fillable = [
        'id_session', 'id_client', 'date_panier', 'montant_tot'
    ];

    public function lignes()
    {
        return $this->hasMany(Panier_ligne::class, 'id_panier', 'id_panier');
    }

    public function panier_lignes()
{
    return $this->hasMany(Panier_ligne::class, 'id_panier', 'id_panier');
}


    public static function getPanier($idClient) {
        $clients = \App\Models\Client::find($idClient); // Utiliser un ID d'utilisateur fictif pour tester
        $panier = Panier::where('id_client', $clients->id_client)->with('lignes.produit')->get(); // Récupérer le panier et les lignes avec les produits associés
        //$panier = Panier::where('id_client', $clients->id_client)->with('lignes')->get(); // Récupérer le panier et les lignes avec les produits associés
        //dd($panier[0]->lignes);
        // Calcul du total en parcourant les lignes du panier
        $total = $panier->sum(fn($item) => $item->lignes->sum(fn($ligne) => $ligne->prix_ttc * $ligne->quantite));
        $total_ht = $panier->sum(fn($item) => $item->lignes->sum(fn($ligne) => $ligne->prix_ht * $ligne->quantite));

        $panier[0]->lignes = $panier[0]->lignes->map(function ($item) {
            $item->produit->photo = $item->produit->photo 
                ? asset('media/' . $item->produit->photo) 
                : asset('media/concombre.png');
        
            return $item; // Retourne l'objet tel quel, mais avec `photo` mis à jour
        });
        return compact('panier', 'total', 'total_ht');
    }
}
