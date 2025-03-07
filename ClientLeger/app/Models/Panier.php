<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Panier extends Model
{
    public function lignes(): HasMany
    {
        return $this->hasMany(Panier_ligne::class, 'id_panier', 'id_panier');
    }   

}
