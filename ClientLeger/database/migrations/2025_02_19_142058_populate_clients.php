<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('clients')->insert([

            [
                'id_client' => 1,
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'email' => 'jean.dupont@example.com',
                'tel' => '0612345678',
                'mdp' => bcrypt('password123'),
                'adresse' => '12 rue des Lilas',
                'cp' => 75001,
                'ville' => 'Paris'
            ],
            [
                'id_client' => 2,
                'nom' => 'Martin',
                'prenom' => 'Sophie',
                'email' => 'sophie.martin@example.com',
                'tel' => '0623456789',
                'mdp' => bcrypt('securepass'),
                'adresse' => '34 avenue Victor Hugo',
                'cp' => 69002,
                'ville' => 'Lyon'

            ],
           
            [
                'id_client' => 3,
                'nom' => 'Lemoine',
                'prenom' => 'Paul',
                'email' => 'paul.lemoine@example.com',
                'tel' => '0623456789',
                'mdp' => bcrypt('paulpass'),
                'adresse' => '78 boulevard Haussmann',
                'cp' => 75008,
                'ville' => 'Paris',
            ],
            [
                'id_client' => 4,
                'nom' => 'Morel',
                'prenom' => 'Camille',
                'email' => 'camille.morel@example.com',
                'tel' => '0645678901',
                'mdp' => bcrypt('camillepass'),
                'adresse' => '32 rue du Capitole',
                'cp' => 31000,
                'ville' => 'Toulouse',
            ],
            [
                'id_client' => 5,
                'nom' => 'Dubois',
                'prenom' => 'Alexandre',
                'email' => 'alexandre.dubois@example.com',
                'tel' => '0656789012',
                'mdp' => bcrypt('alexpass'),
                'adresse' => '14 avenue de Bretagne',
                'cp' => 35000,
                'ville' => 'Rennes',
            ],
        ]);
    }

    
};
