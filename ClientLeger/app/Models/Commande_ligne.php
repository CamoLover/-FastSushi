<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande_ligne extends Model
{
    use HasFactory;

    protected $table = 'commande_lignes';
    protected $primaryKey = 'id_commande_ligne';
    public $timestamps = false;

    protected $fillable = [
        'id_commande',
        'id_produit',
        'quantite',
        'nom',
        'prix_ht',
        'prix_ttc'
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_commande', 'id_commande');
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit', 'id_produit');
    }

    /**
     * Get the custom ingredients for this order line.
     */
    public function ingredients()
    {
        return $this->hasMany('App\Models\CompoCommande', 'id_commande_ligne', 'id_commande_ligne');
    }
}
