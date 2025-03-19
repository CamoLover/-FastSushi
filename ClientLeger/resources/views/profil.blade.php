@extends('layouts.home')

@section('title', 'Fast Sushi - Profil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- User Profile Information -->
    <div class="bg-[#252422] rounded-lg shadow-lg p-6 mb-8">
        <div class="flex items-center space-x-4 mb-6">
            <div class="w-16 h-16 rounded-full bg-[#D90429] flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr(session('client')->prenom, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold">{{ session('client')->prenom }} {{ session('client')->nom }}</h1>
                <p class="text-[#CCC5B9]">{{ session('client')->email }}</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-semibold mb-4 text-[#FFFCF2]">Informations personnelles</h2>
                <div class="space-y-3">
                    <div class="flex flex-col">
                        <span class="text-sm text-[#CCC5B9]">Nom</span>
                        <span>{{ session('client')->nom }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm text-[#CCC5B9]">Prénom</span>
                        <span>{{ session('client')->prenom }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm text-[#CCC5B9]">Email</span>
                        <span>{{ session('client')->email }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm text-[#CCC5B9]">Téléphone</span>
                        <span>{{ session('client')->telephone ?: 'Non renseigné' }}</span>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="text-xl font-semibold mb-4 text-[#FFFCF2]">Adresse</h2>
                <div class="space-y-3">
                    <div class="flex flex-col">
                        <span class="text-sm text-[#CCC5B9]">Adresse</span>
                        <span>{{ session('client')->adresse ?: 'Non renseignée' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm text-[#CCC5B9]">Ville</span>
                        <span>{{ session('client')->ville ?: 'Non renseignée' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm text-[#CCC5B9]">Code postal</span>
                        <span>{{ session('client')->code_postal ?: 'Non renseigné' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <a href="#" class="inline-flex items-center px-4 py-2 bg-[#D90429] text-white font-medium rounded-lg hover:bg-[#660708] transition-colors duration-300">
                <i class="fa-solid fa-edit mr-2"></i> Modifier mes informations
            </a>
        </div>
    </div>
    
    <!-- Order History -->
    <div class="bg-[#252422] rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-[#FFFCF2]">Historique des commandes</h2>
        
        @php
            // Get commandes with eager loading of lignes relationship
            $commandes = App\Models\Commande::with(['lignes' => function($query) {
                $query->orderBy('id_commande_ligne', 'asc');
            }])
            ->where('id_client', session('client')->id_client)
            ->orderBy('date_panier', 'desc')
            ->get();
            
            // Debug commandes
            foreach ($commandes as $key => $commande) {
                if (!$commande->lignes || $commande->lignes->isEmpty()) {
                    \Log::info("Commande #{$commande->id_commande} has no lines");
                } else {
                    \Log::info("Commande #{$commande->id_commande} has {$commande->lignes->count()} lines");
                }
            }
        @endphp
        
        @if($commandes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-[#333]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#CCC5B9] uppercase tracking-wider">N° Commande</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#CCC5B9] uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#CCC5B9] uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#CCC5B9] uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#CCC5B9] uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#252422] divide-y divide-gray-700">
                        @foreach($commandes as $commande)
                        <tr class="hover:bg-[#333] transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium">#{{ $commande->id_commande }}</div>
                                <!-- Debug info -->
                                <div class="text-xs text-gray-500">
                                    {{-- Checking if lignes relationship exists and contains items --}}
                                    Lignes: {{ $commande->lignes ? $commande->lignes->count() : 'None' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">{{ date('d/m/Y', strtotime($commande->date_panier)) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($commande->statut == 'En attente') bg-yellow-200 text-yellow-800
                                    @elseif($commande->statut == 'livré') bg-green-200 text-green-800
                                    @elseif($commande->statut == 'Annulée') bg-red-200 text-red-800
                                    @else bg-blue-200 text-blue-800
                                    @endif">
                                    {{ $commande->statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold">{{ number_format($commande->montant_tot, 2, ',', ' ') }} €</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="button" class="detail-btn text-[#D90429] hover:text-[#660708] transition-colors" 
                                        data-order-id="{{ $commande->id_commande }}">
                                    <i class="fas fa-eye"></i> Détails
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Order Details Row (hidden by default) -->
                        <tr id="order-details-{{ $commande->id_commande }}" class="hidden bg-[#333] order-details">
                            <td colspan="5" class="px-6 py-4">
                                <div class="text-sm">
                                    <h4 class="font-bold mb-2">Détails de la commande #{{ $commande->id_commande }}</h4>
                                    
                                    @if($commande->lignes && $commande->lignes->count() > 0)
                                        <table class="min-w-full divide-y divide-gray-600">
                                            <thead>
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-[#CCC5B9]">Produit</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-[#CCC5B9]">Quantité</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-[#CCC5B9]">Prix unitaire</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-[#CCC5B9]">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-600">
                                                @foreach($commande->lignes as $ligne)
                                                <tr>
                                                    <td class="px-4 py-2">{{ $ligne->nom }}</td>
                                                    <td class="px-4 py-2">{{ $ligne->quantite }}</td>
                                                    <td class="px-4 py-2">{{ number_format($ligne->prix_ttc, 2, ',', ' ') }} €</td>
                                                    <td class="px-4 py-2">{{ number_format($ligne->prix_ttc * $ligne->quantite, 2, ',', ' ') }} €</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-yellow-400">Aucun détail disponible pour cette commande.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-[#333] rounded-lg p-6 text-center">
                <p class="text-lg">Vous n'avez pas encore passé de commande.</p>
                <a href="/" class="inline-block mt-4 px-4 py-2 bg-[#D90429] text-white font-medium rounded-lg hover:bg-[#660708] transition-colors duration-300">
                    Commencer mes achats
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Make all detail buttons trigger the toggle function
        const detailButtons = document.querySelectorAll('.detail-btn');
        detailButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const orderId = this.getAttribute('data-order-id');
                toggleOrderDetails(orderId);
            });
        });
    });

    function toggleOrderDetails(orderId) {
        const detailsRow = document.getElementById(`order-details-${orderId}`);
        console.log(`Toggling details for order ${orderId}`, detailsRow);
        
        if (!detailsRow) {
            console.error(`Could not find details row for order ${orderId}`);
            return;
        }
        
        // Close all other open detail rows
        const allDetailRows = document.querySelectorAll('.order-details');
        allDetailRows.forEach(row => {
            if (row.id !== `order-details-${orderId}` && !row.classList.contains('hidden')) {
                row.classList.add('hidden');
            }
        });
        
        // Toggle the clicked detail row
        detailsRow.classList.toggle('hidden');
    }
</script>
@endsection
