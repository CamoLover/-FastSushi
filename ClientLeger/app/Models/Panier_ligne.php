<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Panier_ligne extends Model
{

    protected $table = 'panier_lignes';
    protected $primaryKey = 'id_panier_ligne';
    public $timestamps = false;

    protected $fillable = [
        'id_panier', 'id_produit', 'quantite', 'nom', 'prix_ht', 'prix_ttc'
    ];

    public function panier()
    {
        return $this->belongsTo(Panier::class, 'id_panier', 'id_panier');
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit', 'id_produit');
    }
}
