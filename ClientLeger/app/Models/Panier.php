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
}
