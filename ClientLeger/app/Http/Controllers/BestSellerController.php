<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande_ligne;
use Illuminate\Support\Facades\DB;

class BestSellerController extends Controller

{
    public function bestSellers()
    {
        $bestSellers = Commande_ligne::select(
                'id_produit',
                'nom',
                DB::raw('SUM(quantite) as total_quantite_vendue'),
                DB::raw('COUNT(DISTINCT id_commande) as nombre_commandes'),
                DB::raw('GROUP_CONCAT(id_commande ORDER BY id_commande ASC SEPARATOR ", ") as historique_commandes')
            )
            ->groupBy('id_produit', 'nom')
            ->orderByDesc('total_quantite_vendue')
            ->get();

            return view('hero', compact('bestSellers'));

    }
}
