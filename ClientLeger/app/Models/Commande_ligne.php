<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande_ligne extends Model
{
    use HasFactory;

    protected $table = 'commande_lignes';
   

    public $timestamps = false;

    protected $fillable = ['id_produit', 'nom', 'quantite', 'id_commande'];
}
