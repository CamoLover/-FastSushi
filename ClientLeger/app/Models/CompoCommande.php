<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompoCommande extends Model
{
    use HasFactory;

    protected $table = 'compo_commandes';
    protected $primaryKey = ['id_commande_ligne', 'id_ingredient'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_commande_ligne',
        'id_ingredient',
        'prix'
    ];

    /**
     * Get the order line that this ingredient belongs to.
     */
    public function commandeLigne()
    {
        return $this->belongsTo(Commande_ligne::class, 'id_commande_ligne', 'id_commande_ligne');
    }

    /**
     * Get the ingredient information.
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'id_ingredient', 'id_ingredient');
    }
} 