<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FastSushi - Compo</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'sushi-red': '#e22028',
                        'sushi-dark': '#1a1a1a',
                        'sushi-card': '#242424',
                        'sushi-box': '#2a2a2a',
                        'sushi-hover': '#3a3a3a',
                    }
                }
            }
        }
    </script>
    <style>
        /* Style nécessaire pour l'effet de hover sur les ingrédients sélectionnés */
        .selected-ingredient:hover .ingredient-text {
            opacity: 0;
        }
        .selected-ingredient:hover .delete-icon {
            display: block;
        }
        .delete-icon {
            display: none;
        }
        /* Style pour les ingrédients désactivés */
        .ingredient-disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }
        /* Animation pour l'ajout des ingrédients */
        @keyframes float-to-cart {
            0% { 
                opacity: 1;
                transform: translate(0, 0) scale(1);
            }
            100% { 
                opacity: 0;
                transform: translate(50px, 50px) scale(0.5);
            }
        }
        .price-anim {
            position: absolute;
            animation: float-to-cart 0.8s ease-out forwards;
            pointer-events: none;
            z-index: 100;
        }
    </style>
</head>
<body class="bg-sushi-dark text-white font-sans flex justify-center items-center min-h-screen p-4">
    <!-- Global notification container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50"></div>

    <div class="w-full max-w-6xl bg-sushi-card rounded-xl shadow-lg pt-15 p-4 md:p-6" id="main-container">
        <div class="flex items-center mb-6">
            <i class="fas fa-fish text-sushi-red mr-3 text-xl"></i>
            <h1 class="text-xl md:text-2xl font-bold">Customisation</h1>
        </div>
        
        <!-- Sélection des plats -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6" id="plats-container">
            @foreach($customisations as $index => $customisation)
            <div class="md:col-span-2 bg-sushi-box rounded-lg border border-gray-700 relative p-4 flex flex-col justify-center items-center cursor-pointer transition-all hover:bg-sushi-hover hover:-translate-y-1 plat" 
                 data-plat="{{ $index + 1 }}" 
                 data-price="{{ $customisation->prix_ttc }}"
                 data-id="{{ $customisation->id_produit }}"
                 data-name="{{ $customisation->nom }}"
                 data-price-ht="{{ $customisation->prix_ht }}">
                @if($customisation->photo)
                    <img src="data:{{ $customisation->photo_type ?? 'image/png' }};base64,{{ $customisation->photo }}" 
                         alt="{{ $customisation->nom }}" 
                         class="w-16 h-16 object-cover rounded-full mb-2"
                         onerror="this.onerror=null; this.src='https://placehold.co/400x400/252422/FFFCF2?text=Fast+Sushi'">
                @else
                    <i class="fas fa-fish text-sushi-red text-2xl absolute top-4"></i>
                @endif
                <div class="pt-2 text-center">
                    <span class="block">{{ $customisation->nom }}</span>
                    <div class="text-sushi-red font-bold mt-2">{{ number_format($customisation->prix_ttc, 2, ',', ' ') }} €</div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Zone de sélection active -->
        <div class="hidden grid grid-cols-1 md:grid-cols-6 gap-4 mb-6" id="selection-zone">
            <!-- Zone où s'affiche le plat sélectionné -->
            <div class="hidden md:col-span-2 bg-sushi-box rounded-lg border border-gray-700 relative p-4 flex flex-col justify-center items-center min-h-36" id="selected-plat">
                <div class="price-container mt-2">
                    <span class="text-sushi-red font-bold base-price"></span>
                    <span class="text-sushi-red text-sm supplements-price ml-1"></span>
                </div>
            </div>
            
            <!-- Zone de drop pour les ingrédients -->
            <div class="hidden md:col-span-4 bg-sushi-box rounded-lg border-2 border-dashed border-sushi-red p-4 min-h-36 flex flex-wrap content-start" id="custom-zone">
                <span id="custom-zone-text" class="w-full text-center p-4">Glissez vos ingrédients ici (3 maximum)</span>
            </div>
        </div>

        <!-- Boutons d'actions -->
        <div class="grid grid-cols-2 gap-4 mb-6 hidden" id="action-buttons">
            <!-- Bouton de retour -->
            <button id="return-button" class="bg-gray-600 hover:bg-gray-700 text-white py-3 px-4 rounded-lg flex justify-center items-center transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </button>
            
            <!-- Bouton d'ajout au panier -->
            <button id="add-to-cart" class="bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-lg flex justify-center items-center transition-colors">
                <i class="fas fa-plus mr-2"></i> Ajouter
            </button>
        </div>

        <!-- Section des ingrédients (initialement cachée) -->
        <div id="ingredients-section" class="hidden">
            <!-- Titre pour la section d'ingrédients -->
            <div class="flex items-center my-4">
                <i class="fas fa-utensils text-sushi-red mr-2"></i>
                <h2 class="font-bold">Ingrédients disponibles</h2>
            </div>

            <!-- Grille des ingrédients -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
                @foreach($ingredients as $ingredient)
                <div class="bg-sushi-box rounded-lg border border-gray-700 p-4 text-center cursor-pointer transition-all hover:bg-sushi-hover hover:-translate-y-1 ingredient flex flex-col items-center justify-center" draggable="true" 
                    data-id="{{ $ingredient->id_ingredient }}" 
                    data-name="{{ $ingredient->nom }}" 
                    data-price="{{ $ingredient->prix_ht }}">
                    @if($ingredient->photo)
                        <img src="data:{{ $ingredient->photo_type ?? 'image/png' }};base64,{{ $ingredient->photo }}" 
                             alt="{{ $ingredient->nom }}" 
                             class="w-10 h-10 object-cover rounded-full mb-2"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x400/252422/FFFCF2?text=Fast+Sushi'">
                    @endif
                    {{ $ingredient->nom }}
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const plats = document.querySelectorAll('.plat');
            const selectedPlat = document.getElementById('selected-plat');
            const customZone = document.getElementById('custom-zone');
            const selectionZone = document.getElementById('selection-zone');
            const customZoneText = document.getElementById('custom-zone-text');
            const ingredients = document.querySelectorAll('.ingredient');
            const addToCartButton = document.getElementById('add-to-cart');
            const mainContainer = document.getElementById('main-container');
            const platsContainer = document.getElementById('plats-container');
            const ingredientsSection = document.getElementById('ingredients-section');
            const actionButtons = document.getElementById('action-buttons');
            const returnButton = document.getElementById('return-button');

            // Données globales pour suivre la sélection
            let selectedPlatData = {
                id: 0,
                name: '',
                price: 0,
                price_ht: 0,
                ingredients: [],
                supplementsTotal: 0
            };

            // Ajouter les icônes de poisson aux plats
            plats.forEach(plat => {
                // Check if the plat already has an image, only add icon if it doesn't
                if (!plat.querySelector('img')) {
                    const icon = document.createElement('i');
                    icon.className = 'fas fa-fish text-sushi-red text-2xl absolute top-4';
                    plat.prepend(icon);
                }
            });

            // Gérer la sélection du plat
            plats.forEach(plat => {
                plat.addEventListener('click', () => {
                    const platName = plat.querySelector('span').textContent.trim();
                    const platPrice = plat.getAttribute('data-price');
                    const platId = plat.getAttribute('data-id');
                    const platPriceHt = plat.getAttribute('data-price-ht');
                    const platImg = plat.querySelector('img');
                    
                    // Mettre à jour les données du plat sélectionné
                    selectedPlatData.id = platId;
                    selectedPlatData.name = platName;
                    selectedPlatData.price = platPrice;
                    selectedPlatData.price_ht = platPriceHt;
                    selectedPlatData.ingredients = [];
                    selectedPlatData.supplementsTotal = 0;
                    
                    // Afficher la zone de sélection
                    selectionZone.classList.remove('hidden');
                    selectedPlat.classList.remove('hidden');
                    customZone.classList.remove('hidden');
                    actionButtons.classList.remove('hidden');
                    ingredientsSection.classList.remove('hidden');
                    
                    // Définir le contenu du plat sélectionné
                    if (platImg) {
                        selectedPlat.innerHTML = `
                            <img src="${platImg.src}" alt="${platName}" class="w-20 h-20 object-cover rounded-full mb-4">
                            <div class="text-center">${platName}</div>
                            <div class="price-container mt-2">
                                <span class="text-sushi-red font-bold base-price">${parseFloat(platPrice).toFixed(2).replace('.', ',')} €</span>
                                <span class="text-sushi-red text-sm supplements-price ml-1"></span>
                            </div>
                        `;
                    } else {
                        selectedPlat.innerHTML = `
                            <i class="fas fa-fish text-sushi-red text-2xl mb-4"></i>
                            <div class="text-center">${platName}</div>
                            <div class="price-container mt-2">
                                <span class="text-sushi-red font-bold base-price">${parseFloat(platPrice).toFixed(2).replace('.', ',')} €</span>
                                <span class="text-sushi-red text-sm supplements-price ml-1"></span>
                            </div>
                        `;
                    }
                    
                    // Cacher seulement le conteneur des plats de cette section
                    platsContainer.classList.add('hidden');
                    
                    // Réinitialiser les états des ingrédients
                    ingredients.forEach(ing => {
                        ing.classList.remove('ingredient-disabled');
                    });
                    
                    updateCustomZoneMessage();
                });
            });

            // Activer le drag & drop des ingrédients (desktop)
            ingredients.forEach(ingredient => {
                ingredient.addEventListener('dragstart', (e) => {
                    // Vérifier si l'ingrédient est désactivé
                    if (ingredient.classList.contains('ingredient-disabled')) {
                        e.preventDefault();
                        return;
                    }
                    
                    e.dataTransfer.setData('text/plain', ingredient.textContent.trim());
                    e.dataTransfer.setData('id', ingredient.getAttribute('data-id'));
                    e.dataTransfer.setData('name', ingredient.getAttribute('data-name'));
                    e.dataTransfer.setData('price', ingredient.getAttribute('data-price'));
                });
                
                // Pour mobile - ajouter au clic
                ingredient.addEventListener('click', () => {
                    // Vérifier si un plat est sélectionné
                    if (selectedPlat.classList.contains('hidden')) return;
                    
                    // Vérifier si l'ingrédient est désactivé
                    if (ingredient.classList.contains('ingredient-disabled')) return;
                    
                    // Vérifier si on a déjà 3 ingrédients
                    if (customZone.querySelectorAll('.selected-ingredient').length >= 3) return;
                    
                    const id = ingredient.getAttribute('data-id');
                    const name = ingredient.getAttribute('data-name');
                    const price = ingredient.getAttribute('data-price');
                    
                    // Animation de prix
                    createPriceAnimation(ingredient, price);
                    
                    // Désactiver l'ingrédient
                    ingredient.classList.add('ingredient-disabled');
                    
                    addIngredient(name, id, price);
                });
            });

            // Fonction pour créer l'animation de prix
            function createPriceAnimation(sourceElement, price) {
                const priceAnim = document.createElement('div');
                priceAnim.classList.add('price-anim');
                priceAnim.textContent = `+${parseFloat(price).toFixed(2).replace('.', ',')} €`;
                priceAnim.style.color = '#e22028';
                priceAnim.style.fontWeight = 'bold';
                
                // Positionner l'élément à la position de l'ingrédient source
                const rect = sourceElement.getBoundingClientRect();
                const containerRect = mainContainer.getBoundingClientRect();
                
                priceAnim.style.left = `${rect.left - containerRect.left + rect.width/2}px`;
                priceAnim.style.top = `${rect.top - containerRect.top + rect.height/2}px`;
                
                mainContainer.appendChild(priceAnim);
                
                // Supprimer l'élément après l'animation
                setTimeout(() => {
                    priceAnim.remove();
                }, 800);
            }

            // Fonction pour ajouter un ingrédient à la zone de customisation
            function addIngredient(ingredientName, ingredientId, ingredientPrice) {
                const newIngredient = document.createElement('div');
                newIngredient.classList.add('bg-sushi-hover', 'border', 'border-gray-600', 'rounded-lg', 'p-3', 'm-1', 'relative', 'selected-ingredient', 'hover:bg-sushi-red', 'cursor-pointer');
                newIngredient.setAttribute('data-id', ingredientId);
                newIngredient.setAttribute('data-price', ingredientPrice);
                
                // Créer un span pour le texte de l'ingrédient
                const textSpan = document.createElement('span');
                textSpan.classList.add('ingredient-text', 'transition-opacity', 'duration-200');
                textSpan.textContent = ingredientName;
                newIngredient.appendChild(textSpan);
                
                // Créer l'icône de suppression
                const deleteIcon = document.createElement('i');
                deleteIcon.classList.add('fas', 'fa-trash', 'delete-icon', 'absolute', 'top-1/2', 'left-1/2', 'transform', '-translate-x-1/2', '-translate-y-1/2', 'text-white');
                newIngredient.appendChild(deleteIcon);

                // Ajouter l'ingrédient aux données sélectionnées
                selectedPlatData.ingredients.push({
                    id: parseInt(ingredientId, 10),
                    name: ingredientName,
                    price: parseFloat(ingredientPrice)
                });
                
                // Mettre à jour le prix total des suppléments
                selectedPlatData.supplementsTotal += parseFloat(ingredientPrice);
                updateTotalPrice();

                // Supprimer l'ingrédient au clic
                newIngredient.addEventListener('click', () => {
                    // Supprimer l'ingrédient des données sélectionnées
                    const index = selectedPlatData.ingredients.findIndex(i => i.id === parseInt(ingredientId, 10));
                    if (index > -1) {
                        selectedPlatData.ingredients.splice(index, 1);
                        // Soustraire le prix du supplément
                        selectedPlatData.supplementsTotal -= parseFloat(ingredientPrice);
                        updateTotalPrice();
                    }
                    
                    // Réactiver l'ingrédient dans la liste
                    const ingredientElement = document.querySelector(`.ingredient[data-id="${ingredientId}"]`);
                    if (ingredientElement) {
                        ingredientElement.classList.remove('ingredient-disabled');
                    }
                    
                    newIngredient.remove();
                    updateCustomZoneMessage();
                });

                customZone.appendChild(newIngredient);
                updateCustomZoneMessage();
            }
            
            // Mettre à jour l'affichage du prix total
            function updateTotalPrice() {
                const supplementsPriceElem = selectedPlat.querySelector('.supplements-price');
                if (supplementsPriceElem) {
                    if (selectedPlatData.supplementsTotal > 0) {
                        supplementsPriceElem.textContent = `(+${selectedPlatData.supplementsTotal.toFixed(2).replace('.', ',')} €)`;
                    } else {
                        supplementsPriceElem.textContent = '';
                    }
                }
            }

            // Autoriser le drop sur la zone de customisation
            customZone.addEventListener('dragover', (e) => {
                e.preventDefault();
            });

            // Gestion du drop des ingrédients avec limite de 3
            customZone.addEventListener('drop', (e) => {
                e.preventDefault();
                if (customZone.querySelectorAll('.selected-ingredient').length >= 3) return;
                
                const ingredientName = e.dataTransfer.getData('text/plain');
                const ingredientId = e.dataTransfer.getData('id');
                const ingredientPrice = e.dataTransfer.getData('price');
                
                // Vérifier si cet ingrédient est déjà dans la sélection
                if (selectedPlatData.ingredients.some(i => i.id === parseInt(ingredientId, 10))) {
                    return;
                }
                
                // Désactiver l'ingrédient dans la liste
                const ingredientElement = document.querySelector(`.ingredient[data-id="${ingredientId}"]`);
                if (ingredientElement) {
                    ingredientElement.classList.add('ingredient-disabled');
                    createPriceAnimation(ingredientElement, ingredientPrice);
                }
                
                addIngredient(ingredientName, ingredientId, ingredientPrice);
            });

            // Fonction pour gérer l'affichage du texte dans la zone de drop
            function updateCustomZoneMessage() {
                if (customZone.querySelectorAll('.selected-ingredient').length === 0) {
                    customZoneText.classList.remove('hidden');
                } else {
                    customZoneText.classList.add('hidden');
                }
            }

            // Gérer l'ajout au panier
            addToCartButton.addEventListener('click', () => {
                // Calculer le prix total (base + suppléments)
                const totalPrice = parseFloat(selectedPlatData.price) + selectedPlatData.supplementsTotal;
                
                // Log the ingredients data before sending
                console.log('Sending ingredients data:', selectedPlatData.ingredients);
                
                // Créer l'objet à envoyer
                const cartItem = {
                    id_produit: selectedPlatData.id,
                    nom: selectedPlatData.name + ' personnalisé',
                    prix_ttc: totalPrice,
                    prix_ht: parseFloat(selectedPlatData.price_ht) + selectedPlatData.supplementsTotal,
                    ingredients: selectedPlatData.ingredients.map(ing => ({
                        id: ing.id,
                        name: ing.name,
                        price: ing.price
                    }))
                };
                
                // Envoyer la requête AJAX
                fetch('/simple-add-to-cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(cartItem)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Récupérer les ingrédients sélectionnés
                        const ingredientsList = selectedPlatData.ingredients.map(ing => ing.name).join(', ');
                        
                        // Créer un message de confirmation
                        const messageContent = `${selectedPlatData.name} ajouté au panier:\n${ingredientsList.length > 0 ? '- Avec ' + ingredientsList : '- Sans personnalisation'}\nPrix: ${totalPrice.toFixed(2).replace('.', ',')} €`;
                        
                        // Afficher un message de confirmation avec le nouveau système
                        showNotification(messageContent, 'success');
                        
                        // Mettre à jour le compteur du panier si présent
                        const cartCount = document.getElementById('cart-count');
                        if (cartCount) {
                            cartCount.textContent = data.count || 0;
                        }
                        
                        // Réinitialiser la composition
                        resetComposition();
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de l\'ajout au panier:', error);
                    showNotification('Une erreur est survenue lors de l\'ajout au panier', 'error');
                });
            });

            // Fonction de réinitialisation
            function resetComposition() {
                // Réafficher les plats
                platsContainer.classList.remove('hidden');
                
                // Cacher la zone de sélection et les boutons d'action
                selectionZone.classList.add('hidden');
                selectedPlat.classList.add('hidden');
                customZone.classList.add('hidden');
                actionButtons.classList.add('hidden');
                ingredientsSection.classList.add('hidden');

                // Vider la zone de drop des ingrédients
                const selectedIngredients = customZone.querySelectorAll('.selected-ingredient');
                selectedIngredients.forEach(ing => ing.remove());
                
                // Réafficher le texte
                customZoneText.classList.remove('hidden');
                
                // Réinitialiser les ingrédients
                ingredients.forEach(ing => {
                    ing.classList.remove('ingredient-disabled');
                });
                
                // Réinitialiser les données sélectionnées
                selectedPlatData = {
                    id: 0,
                    name: '',
                    price: 0,
                    price_ht: 0,
                    ingredients: [],
                    supplementsTotal: 0
                };
            }
            
            // Bouton retour pour réinitialiser la composition
            returnButton.addEventListener('click', resetComposition);
        });

        // Add the showNotification function
        function showNotification(message, type = 'success') {
            const container = document.getElementById('notification-container');
            
            const notification = document.createElement('div');
            notification.className = `notification-item mb-4 ${type === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700'} border-l-4 p-4 rounded shadow-lg transform transition-all duration-500 ease-in-out`;
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <div class="mr-2">
                        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
                    </div>
                    <p>${message}</p>
                    <button onclick="this.closest('.notification-item').remove()" class="ml-4 ${type === 'success' ? 'text-green-700 hover:text-green-900' : 'text-red-700 hover:text-red-900'}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            container.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html>