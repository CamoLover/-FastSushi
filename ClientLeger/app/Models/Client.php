<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
   
    public $timestamps = false;
    protected $fillable = ['nom', 'email', 'tel', 'prenom', 'mdp']; // Ajoutez ici le champ mentionné dans l'erreur


    // Définir la clé primaire
    protected $primaryKey = 'id_client'; // Utilise 'id_client' au lieu de 'id'
}

