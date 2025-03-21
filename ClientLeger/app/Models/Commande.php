<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $table = 'commandes';
    protected $primaryKey = 'id_commande';
    public $timestamps = false;

    protected $fillable = [
        'id_client',
        'date_panier',
        'montant_tot',
        'statut'
    ];

    public function lignes()
    {
        return $this->hasMany(Commande_ligne::class, 'id_commande', 'id_commande');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }
}
