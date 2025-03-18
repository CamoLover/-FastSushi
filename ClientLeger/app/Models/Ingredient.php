<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $table = 'ingredients';
    protected $primaryKey = 'id_ingredient';
    public $timestamps = false;

    protected $fillable = ['nom', 'photo', 'prix_ht', 'type_ingredient'];

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'ingredient_produit', 'ingredient_id', 'produit_id');
    }
}


