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
        $clients = \App\Models\Client::find($idClient);
        
        if (!$clients) {
            return [
                'panier' => [],
                'total' => 0,
                'total_ht' => 0
            ];
        }
        
        // Get the panier directly
        $panier = Panier::where('id_client', $clients->id_client)->first();
        
        if (!$panier) {
            // Create a new panier if none exists
            $panier = Panier::create([
                'id_client' => $clients->id_client,
                'id_session' => session()->getId(),
                'date_panier' => now(),
                'montant_tot' => 0
            ]);
            
            // Return empty result with the newly created panier
            return [
                'panier' => [$panier],
                'total' => 0,
                'total_ht' => 0
            ];
        }
        
        // Load the lignes with their associated produits
        $panier->load('lignes.produit');
        
        // If panier has no lines, return empty cart data
        if ($panier->lignes->isEmpty()) {
            return [
                'panier' => [$panier],
                'total' => 0,
                'total_ht' => 0
            ];
        }
        
        // Calcul du total
        $total = $panier->lignes->sum(function($ligne) {
            return $ligne->prix_ttc * $ligne->quantite;
        });
        
        $total_ht = $panier->lignes->sum(function($ligne) {
            return $ligne->prix_ht * $ligne->quantite;
        });
        
        // Format photos for products
        $panier->lignes->each(function($item) {
            if ($item->produit) {
                $item->produit->photo = $item->produit->photo 
                    ? asset('media/' . $item->produit->photo) 
                    : asset('media/concombre.png');
            }
        });
        
        // Return in the format expected by the view
        return [
            'panier' => [$panier],
            'total' => $total,
            'total_ht' => $total_ht
        ];
    }
}
