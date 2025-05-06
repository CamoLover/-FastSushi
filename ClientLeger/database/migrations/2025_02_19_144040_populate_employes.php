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
        DB::table('employes')->insert([
            [
                'id_employe' => 1,
                'nom' => 'Bernard',
                'prenom' => 'Luc',
                'email' => 'luc.bernard@example.com',
                'mdp' => bcrypt('luckpass'),
                'statut_emp' => 'Preparateur',
            ],
            [
                'id_employe' => 2,
                'nom' => 'Moreau',
                'prenom' => 'Elise',
                'email' => 'elise.moreau@example.com',
                'mdp' => bcrypt('elisepass'),
                'statut_emp' => 'Preparateur',
            ],
            [
                'id_employe' => 3,
                'nom' => 'Rousseau',
                'prenom' => 'Nicolas',
                'email' => 'nicolas.rousseau@example.com',
                'mdp' => bcrypt('nicolaspass'),
                'statut_emp' => 'Administrateur',
            ]
        ]);
    }

   
};
