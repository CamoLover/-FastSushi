@extends('layouts.home')

@section('title', 'Fast Sushi - Menu')

@section('content')

    <!-- Menu Container -->
    <div class="max-w-6xl mx-auto my-8 px-4">
        <!-- Titre principal amélioré -->
        <h1 class="text-4xl font-bold mb-12 text-center relative">
            <span class="bg-[#403D39] px-6 relative z-10">Notre Menu</span>
            <span class="absolute left-0 right-0 h-0.5 bg-red-600 top-1/2 -translate-y-1/2 z-0"></span>
        </h1>
        
        <!-- Entrée Section -->
        <div class="mb-12">
            <!-- Titre de section amélioré -->
            <h2 class="text-2xl font-bold mb-8 text-red-600 pb-2 flex items-center">
                <i class="fas fa-utensils mr-3"></i>
                <span class="border-b-2 border-white-600 pb-1">Entrée</span>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Salades Card -->
                <div class="bg-neutral-900 rounded-lg overflow-hidden shadow-lg border border-neutral-700">
                    <div class="h-48 bg-neutral-800 flex items-center justify-center overflow-hidden">
                        <img src="/api/placeholder/400/300" alt="Salade japonaise" class="w-full h-full object-cover" />
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-4 text-red-600 border-b border-neutral-700 pb-2">Salades</h3>
                        <ul class="space-y-2 mb-4">
                            <li class="flex justify-between items-center">
                                <span>Salade Choux</span>
                                <div class="flex items-center space-x-4">
                                    <span class="text-red-600">4,50 €</span>
                                    <button class="bg-red-600 text-white py-1 px-3 rounded hover:bg-red-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </li>
                            <li class="flex justify-between items-center">
                                <span>Salade Wakame</span>
                                <div class="flex items-center space-x-4">
                                    <span class="text-red-600">5,20 €</span>
                                    <button class="bg-red-600 text-white py-1 px-3 rounded hover:bg-red-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </li>
                            <li class="flex justify-between items-center">
                                <span>Salade Fève de soja</span>
                                <div class="flex items-center space-x-4">
                                    <span class="text-red-600">4,80 €</span>
                                    <button class="bg-red-600 text-white py-1 px-3 rounded hover:bg-red-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </li>
                            <li class="flex justify-between items-center">
                                <span>Salade Crevettes</span>
                                <div class="flex items-center space-x-4">
                                    <span class="text-red-600">6,50 €</span>
                                    <button class="bg-red-600 text-white py-1 px-3 rounded hover:bg-red-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Soupes Card -->
                <div class="bg-neutral-900 rounded-lg overflow-hidden shadow-lg border border-neutral-700">
                    <div class="h-48 bg-neutral-800 flex items-center justify-center overflow-hidden">
                        <img src="/api/placeholder/400/300" alt="Soupe japonaise" class="w-full h-full object-cover" />
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-4 text-red-600 border-b border-neutral-700 pb-2">Soupes</h3>
                        <ul class="space-y-2 mb-4">
                            <li class="flex justify-between items-center">
                                <span>Soupe Miso</span>
                                <div class="flex items-center space-x-4">
                                    <span class="text-red-600">3,80 €</span>
                                    <button class="bg-red-600 text-white py-1 px-3 rounded hover:bg-red-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </li>
                            <li class="flex justify-between items-center">
                                <span>Soupe Ramen crevettes</span>
                                <div class="flex items-center space-x-4">
                                    <span class="text-red-600">8,90 €</span>
                                    <button class="bg-red-600 text-white py-1 px-3 rounded hover:bg-red-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </li>
                            <li class="flex justify-between items-center">
                                <span>Soupe Ramen Poulets</span>
                                <div class="flex items-center space-x-4">
                                    <span class="text-red-600">7,90 €</span>
                                    <button class="bg-red-600 text-white py-1 px-3 rounded hover:bg-red-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Plats Section -->
        <div class="mb-12">
            <!-- Titre de section amélioré -->
            <h2 class="text-2xl font-bold mb-8 text-red-600 pb-2 flex items-center">
                <i class="fas fa-fish mr-3"></i>
                <span class="border-b-2 border-white-600 pb-1">Plats</span>
            </h2>
            
            <!-- Première ligne de sushis (3 colonnes) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <!-- Sushi Cards -->
                <!-- Sushi Saumon -->
                <div class="bg-neutral-900 rounded-lg overflow-hidden shadow-lg border border-neutral-700">
                    <div class="h-40 bg-neutral-800 flex items-center justify-center">
                        <i class="fas fa-fish text-4xl text-red-600"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-center">Sushi Saumon</h3>
                        <p class="text-center text-neutral-400 mt-2">4 pièces</p>
                        <p class="text-center text-red-600 font-bold mt-2">6,90 €</p>
                        <button class="w-full bg-red-600 text-white py-2 rounded mt-4 hover:bg-red-700 transition">
                            <i class="fas fa-plus mr-2"></i>Ajouter
                        </button>
                    </div>
                </div>
                <!-- Sushi Saumon -->

                <!-- Sushi Thon -->                
                <div class="bg-neutral-900 rounded-lg overflow-hidden shadow-lg border border-neutral-700">
                    <div class="h-40 bg-neutral-800 flex items-center justify-center">
                        <i class="fas fa-fish text-4xl text-red-600"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-center">Sushi Thon</h3>
                        <p class="text-center text-neutral-400 mt-2">4 pièces</p>
                        <p class="text-center text-red-600 font-bold mt-2">7,50 €</p>
                        <button class="w-full bg-red-600 text-white py-2 rounded mt-4 hover:bg-red-700 transition">
                            <i class="fas fa-plus mr-2"></i>Ajouter
                        </button>
                    </div>
                </div>
                <!-- Sushi Thon -->
                
                <!-- Sushi Crevettes -->
                <div class="bg-neutral-900 rounded-lg overflow-hidden shadow-lg border border-neutral-700">
                    <div class="h-40 bg-neutral-800 flex items-center justify-center">
                        <i class="fas fa-fish text-4xl text-red-600"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-center">Sushi Crevettes</h3>
                        <p class="text-center text-neutral-400 mt-2">4 pièces</p>
                        <p class="text-center text-red-600 font-bold mt-2">6,90 €</p>
                        <button class="w-full bg-red-600 text-white py-2 rounded mt-4 hover:bg-red-700 transition">
                            <i class="fas fa-plus mr-2"></i>Ajouter
                        </button>
                    </div>
                </div>
                <!-- Sushi Crevettes -->

            </div>
            
            <!-- Deuxième ligne de sushis (2 colonnes) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6 pb-15">                
                <!-- Sushi Daurade -->
                <div class="bg-neutral-900 rounded-lg overflow-hidden shadow-lg border border-neutral-700">
                    <div class="h-40 bg-neutral-800 flex items-center justify-center">
                        <i class="fas fa-fish text-4xl text-red-600"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-center">Sushi Daurade</h3>
                        <p class="text-center text-neutral-400 mt-2">4 pièces</p>
                        <p class="text-center text-red-600 font-bold mt-2">7,20 €</p>
                        <button class="w-full bg-red-600 text-white py-2 rounded mt-4 hover:bg-red-700 transition">
                            <i class="fas fa-plus mr-2"></i>Ajouter
                        </button>
                    </div>
                </div>
                <!-- Sushi Daurade -->
                
                <!-- Sushi Anguille -->
                <div class="bg-neutral-900 rounded-lg overflow-hidden shadow-lg border border-neutral-700">
                    <div class="h-40 bg-neutral-800 flex items-center justify-center">
                        <i class="fas fa-fish text-4xl text-red-600"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-center">Sushi Anguille</h3>
                        <p class="text-center text-neutral-400 mt-2">4 pièces</p>
                        <p class="text-center text-red-600 font-bold mt-2">8,50 €</p>
                        <button class="w-full bg-red-600 text-white py-2 rounded mt-4 hover:bg-red-700 transition">
                            <i class="fas fa-plus mr-2"></i>Ajouter
                        </button>
                    </div>
                </div>
                <!-- Sushi Anguille -->                
            </div>
            
            

        </div>

        @include('module.composition')
             
        <!-- Desserts Section -->
        <div class="mb-12">
            <!-- Titre de section amélioré -->
            <h2 class="text-2xl font-bold mb-8 text-red-600 pb-2 flex items-center">
                <i class="fas fa-ice-cream mr-3"></i>
                <span class="border-b-2 border-white-600 pb-1">Desserts</span>
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <!-- Dessert Cards -->
                <!-- Moelleux Chocolat --> 
                <div class="bg-neutral-900 rounded-lg overflow-hidden shadow-lg border border-neutral-700">
                    <div class="h-40 bg-neutral-800 flex items-center justify-center">
                        <i class="fas fa-cookie text-4xl text-red-600"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-center">Moelleux Chocolat</h3>
                        <p class="text-center text-neutral-400 mt-2">Servi avec crème anglaise</p>
                        <p class="text-center text-red-600 font-bold mt-2">5,90 €</p>
                        <button class="w-full bg-red-600 text-white py-2 rounded mt-4 hover:bg-red-700 transition">
                            <i class="fas fa-plus mr-2"></i>Ajouter
                        </button>
                    </div>
                </div>
                <!-- Moelleux Chocolat -->
                
                <!-- Maki Nutella Banane -->                
                <div class="bg-neutral-900 rounded-lg overflow-hidden shadow-lg border border-neutral-700">
                    <div class="h-40 bg-neutral-800 flex items-center justify-center">
                        <i class="fas fa-candy-cane text-4xl text-red-600"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-center">Maki Nutella banane</h3>
                        <p class="text-center text-neutral-400 mt-2">6 pièces</p>
                        <p class="text-center text-red-600 font-bold mt-2">6,50 €</p>
                        <button class="w-full bg-red-600 text-white py-2 rounded mt-4 hover:bg-red-700 transition">
                            <i class="fas fa-plus mr-2"></i>Ajouter
                        </button>
                    </div>
                </div>
                <!-- Maki Nutella Banane -->
                
                <!-- Crispy Nutella pané -->
                <div class="bg-neutral-900 rounded-lg overflow-hidden shadow-lg border border-neutral-700">
                    <div class="h-40 bg-neutral-800 flex items-center justify-center">
                        <i class="fas fa-ice-cream text-4xl text-red-600"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-center">Crispy Nutella pané</h3>
                        <p class="text-center text-neutral-400 mt-2">4 pièces</p>
                        <p class="text-center text-red-600 font-bold mt-2">5,90 €</p>
                        <button class="w-full bg-red-600 text-white py-2 rounded mt-4 hover:bg-red-700 transition">
                            <i class="fas fa-plus mr-2"></i>Ajouter
                        </button>
                    </div>
                </div>
                <!-- Crispy Nutella pané -->
            </div>
        </div>
    </div>
    @endsection