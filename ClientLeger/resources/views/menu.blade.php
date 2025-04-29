@extends('layouts.home')

@section('title', 'Fast Sushi - Menu')

@section('content')
    <style>
    #floatingHeader {
        z-index: 49 !important;
    }
    </style>
    <!-- Menu Container -->
    <div class="max-w-6xl mx-auto my-8 px-4">
        <!-- Titre principal amélioré -->
        <h1 class="text-4xl font-bold mb-12 text-center relative">
            <span class="bg-[#403D39] px-6 relative z-10">Notre Menu</span>
            <span class="absolute left-0 right-0 h-0.5 bg-red-600 top-1/2 -translate-y-1/2 z-0"></span>
        </h1>
        
        <!-- Entrée Section -->
        <div class="mb-12" id="entrees">
            <!-- Titre de section amélioré -->
            <h2 class="text-2xl font-bold mb-8 text-red-600 pb-2 flex items-center">
                <i class="fas fa-utensils mr-3"></i>
                <span class="border-b-2 border-white-600 pb-1">Entrée</span>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Salades Card -->
                <div class="bg-neutral-900 rounded-lg overflow-hidden shadow-lg border border-neutral-700">
                    <div class="h-48 bg-neutral-800 flex items-center justify-center overflow-hidden">
                        <img src="/media/saladechoux.png" alt="Salade japonaise" class="w-full h-full object-cover" />
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-4 text-red-600 border-b border-neutral-700 pb-2">Salades</h3>
                        <ul class="space-y-2 mb-4">
                            @foreach($entrees as $entree)
                            <li class="flex justify-between items-center">
                                <span>{{ $entree->nom }}</span>
                                <div class="flex items-center space-x-4">
                                    <span class="text-red-600">{{ number_format($entree->prix_ttc, 2, ',', ' ') }} €</span>
                                    <button class="bg-red-600 text-white py-1 px-3 rounded hover:bg-red-700 transition"
                                            data-id="{{ $entree->id_produit }}" 
                                            data-name="{{ $entree->nom }}" 
                                            data-price="{{ $entree->prix_ttc }}" 
                                            data-price-ht="{{ $entree->prix_ht }}"
                                            onclick="addToCart(this)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                
                <!-- Soupes Card -->
                <div class="bg-neutral-900 rounded-lg overflow-hidden shadow-lg border border-neutral-700">
                    <div class="h-48 bg-neutral-800 flex items-center justify-center overflow-hidden">
                        <img src="/media/soupemiso.png" alt="Soupe japonaise" class="w-full h-full object-cover" />
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-4 text-red-600 border-b border-neutral-700 pb-2">Soupes</h3>
                        <ul class="space-y-2 mb-4">
                            @foreach($soupes as $soupe)
                            <li class="flex justify-between items-center">
                                <span>{{ $soupe->nom }}</span>
                                <div class="flex items-center space-x-4">
                                    <span class="text-red-600">{{ number_format($soupe->prix_ttc, 2, ',', ' ') }} €</span>
                                    <button class="bg-red-600 text-white py-1 px-3 rounded hover:bg-red-700 transition"
                                            data-id="{{ $soupe->id_produit }}" 
                                            data-name="{{ $soupe->nom }}" 
                                            data-price="{{ $soupe->prix_ttc }}" 
                                            data-price-ht="{{ $soupe->prix_ht }}"
                                            onclick="addToCart(this)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Plats Section -->
        <div class="mb-12" id="plats">
            <!-- Titre de section amélioré -->
            <h2 class="text-2xl font-bold mb-8 text-red-600 pb-2 flex items-center">
                <i class="fas fa-fish mr-3"></i>
                <span class="border-b-2 border-white-600 pb-1">Plats</span>
            </h2>
            
            <!-- Grid pour les sushis -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($plats as $plat)
                <div class="bg-neutral-900 rounded-lg overflow-hidden shadow-lg border border-neutral-700">
                    <div class="h-40 bg-neutral-800 flex items-center justify-center overflow-hidden">
                        @if($plat->photo)
                            <img src="/media/{{ $plat->photo }}" alt="{{ $plat->nom }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-fish text-4xl text-red-600"></i>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-center">{{ $plat->nom }}</h3>
                        <p class="text-center text-neutral-400 mt-2">{{ $plat->description }}</p>
                        <p class="text-center text-red-600 font-bold mt-2">{{ number_format($plat->prix_ttc, 2, ',', ' ') }} €</p>
                        <button class="w-full bg-red-600 text-white py-2 rounded mt-4 hover:bg-red-700 transition"
                                data-id="{{ $plat->id_produit }}" 
                                data-name="{{ $plat->nom }}" 
                                data-price="{{ $plat->prix_ttc }}" 
                                data-price-ht="{{ $plat->prix_ht }}"
                                onclick="addToCart(this)">
                            <i class="fas fa-plus mr-2"></i>Ajouter
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @include('module.composition')
             
        <!-- Desserts Section -->
        <div class="mb-12 mt-12" id="desserts">
            <!-- Titre de section amélioré -->
            <h2 class="text-2xl font-bold mb-8 text-red-600 pb-2 flex items-center">
                <i class="fas fa-ice-cream mr-3"></i>
                <span class="border-b-2 border-white-600 pb-1">Desserts</span>
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($desserts as $dessert)
                <div class="bg-neutral-900 rounded-lg overflow-hidden shadow-lg border border-neutral-700">
                    <div class="h-40 bg-neutral-800 flex items-center justify-center overflow-hidden">
                        @if($dessert->photo)
                            <img src="/media/{{ $dessert->photo }}" alt="{{ $dessert->nom }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-ice-cream text-4xl text-red-600"></i>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-center">{{ $dessert->nom }}</h3>
                        <p class="text-center text-neutral-400 mt-2">{{ $dessert->description }}</p>
                        <p class="text-center text-red-600 font-bold mt-2">{{ number_format($dessert->prix_ttc, 2, ',', ' ') }} €</p>
                        <button class="w-full bg-red-600 text-white py-2 rounded mt-4 hover:bg-red-700 transition"
                                data-id="{{ $dessert->id_produit }}" 
                                data-name="{{ $dessert->nom }}" 
                                data-price="{{ $dessert->prix_ttc }}" 
                                data-price-ht="{{ $dessert->prix_ht }}"
                                onclick="addToCart(this)">
                            <i class="fas fa-plus mr-2"></i>Ajouter
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <script>
        function addToCart(button) {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const price = button.getAttribute('data-price');
            const priceHt = button.getAttribute('data-price-ht');
            
            // Send AJAX request to add to cart
            fetch('/regular-add-to-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id_produit: id,
                    nom: name,
                    prix_ttc: price,
                    prix_ht: priceHt,
                    quantite: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Show success message
                    showNotification(`${name} ajouté au panier`, 'success');
                    
                    // Update cart count if element exists
                    const cartCount = document.getElementById('cart-count');
                    if(cartCount) {
                        cartCount.textContent = data.count || 0;
                    }
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                showNotification('Une erreur est survenue lors de l\'ajout au panier', 'error');
            });
        }

        // Handle smooth scrolling to sections
        document.addEventListener('DOMContentLoaded', function() {
            // Function to scroll to element with offset for fixed header
            function scrollToElement(elementId) {
                const element = document.getElementById(elementId);
                if (element) {
                    const headerOffset = 100; // Adjust this value based on your header height
                    const elementPosition = element.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            }

            // Check if there's a hash in the URL when page loads
            if (window.location.hash) {
                // Remove the '#' from the hash
                const sectionId = window.location.hash.substring(1);
                // Add a small delay to ensure the page is fully loaded
                setTimeout(() => {
                    scrollToElement(sectionId);
                }, 100);
            }

            // Handle clicks on anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const sectionId = this.getAttribute('href').substring(1);
                    scrollToElement(sectionId);
                });
            });
        });
    </script>
    @endsection