<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produits';
    protected $primaryKey = 'id_produit';
    public $timestamps = false;

    protected $fillable = ['nom', 'type_produit', 'prix_ttc', 'prix_ht', 'photo', 'tva', 'description'];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_produit', 'produit_id', 'ingredient_id');
    }
}

