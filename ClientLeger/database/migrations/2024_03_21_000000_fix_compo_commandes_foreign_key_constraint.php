<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixCompoCommandesForeignKeyConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear any existing data in the compo_commandes table
        DB::table('compo_commandes')->truncate();

        // Drop the incorrect foreign key constraint
        Schema::table('compo_commandes', function (Blueprint $table) {
            $table->dropForeign('compo_commandes_id_commande_ligne_foreign');
        });

        // Add the correct foreign key constraint
        Schema::table('compo_commandes', function (Blueprint $table) {
            $table->foreign('id_commande_ligne')
                  ->references('id_commande_ligne')
                  ->on('commande_lignes')
                  ->onDelete('cascade');
        });
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Disable foreign key checks for the rollback
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Drop the corrected foreign key
        Schema::table('compo_commandes', function (Blueprint $table) {
            $table->dropForeign('compo_commandes_id_commande_ligne_foreign');
        });

        // Restore the original foreign key (though it was incorrect)
        Schema::table('compo_commandes', function (Blueprint $table) {
            $table->foreign('id_commande_ligne')
                  ->references('id_commande')
                  ->on('commande_lignes')
                  ->onDelete('cascade');
        });
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
} 