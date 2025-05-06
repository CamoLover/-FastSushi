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
        $ingredients = [
            ['nom' => 'Fromage', 'photo' => null, 'photo_type' => 'image/png', 'prix_ht' => 2.50, 'type_ingredient' => 'fromage'],
            ['nom' => 'Concombre', 'photo' => null, 'photo_type' => 'image/png', 'prix_ht' => 1.00, 'type_ingredient' => 'légume'],
            ['nom' => 'Avocat', 'photo' => null, 'photo_type' => 'image/png', 'prix_ht' => 2.00, 'type_ingredient' => 'fruit'],
            ['nom' => 'Thon', 'photo' => null, 'photo_type' => 'image/png', 'prix_ht' => 3.50, 'type_ingredient' => 'poisson'],
            ['nom' => 'Saumon', 'photo' => null, 'photo_type' => 'image/png', 'prix_ht' => 4.00, 'type_ingredient' => 'poisson'],
            ['nom' => 'Crevettes', 'photo' => null, 'photo_type' => 'image/png', 'prix_ht' => 5.00, 'type_ingredient' => 'poisson'],
            ['nom' => 'Daurade', 'photo' => null, 'photo_type' => 'image/png', 'prix_ht' => 4.50, 'type_ingredient' => 'poisson'],
            ['nom' => 'Mangue', 'photo' => null, 'photo_type' => 'image/png', 'prix_ht' => 2.50, 'type_ingredient' => 'fruit'],
            ['nom' => 'Boursin', 'photo' => null, 'photo_type' => 'image/png', 'prix_ht' => 3.00, 'type_ingredient' => 'fromage'],
        ];

        $imageMap = [
            'Fromage' => 'fromage.png',
            'Concombre' => 'concombre.png',
            'Avocat' => 'avocat.png',
            'Thon' => 'thon.png',
            'Saumon' => 'saumon.png',
            'Crevettes' => 'crevette.png',
            'Daurade' => 'daurade.png',
            'Mangue' => 'mangue.png',
            'Boursin' => 'boursin.png'
        ];

        foreach ($ingredients as $ingredient) {
            try {
                $imageName = $imageMap[$ingredient['nom']] ?? null;
                $imagePath = $imageName ? public_path("media/{$imageName}") : null;
                
                if ($imagePath && file_exists($imagePath)) {
                    \Log::info("Loading image for {$ingredient['nom']} from: {$imagePath}");
                    $imageData = file_get_contents($imagePath);
                    $ingredient['photo'] = base64_encode($imageData);
                    $ingredient['photo_type'] = mime_content_type($imagePath);
                    \Log::info("Image loaded successfully for {$ingredient['nom']}, size: " . strlen($ingredient['photo']));
                } else {
                    \Log::warning("No image found for {$ingredient['nom']} at path: " . ($imagePath ?? 'null'));
                    $ingredient['photo'] = null;
                    $ingredient['photo_type'] = 'image/png';
                }

                DB::table('ingredients')->insert($ingredient);
                \Log::info("Successfully inserted ingredient: {$ingredient['nom']}" . ($ingredient['photo'] ? " with image" : " without image"));
            } catch (\Exception $e) {
                \Log::error("Error inserting ingredient: {$ingredient['nom']}");
                \Log::error("Exception: " . $e->getMessage());
                \Log::error("Stack trace: " . $e->getTraceAsString());
                continue;
            }
        }
    }

    public function down(): void
    {
        DB::table('ingredients')->truncate();
    }
};
