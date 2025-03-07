<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Panier_ligne extends Model
{
    public function produit(): HasOne
    {
        return $this->hasOne(Produit::class, 'id_produit', 'id_produit');
    }
}
