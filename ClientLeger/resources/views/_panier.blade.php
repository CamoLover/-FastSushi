<div class="container" id="contenu-panier">
@php
    use Illuminate\Support\Facades\DB;
@endphp

<style>
    /* Custom items styles */
    .ingredients-list {
        background-color: #2a2a2a;
        border-radius: 4px;
        padding: 8px;
    }
    
    .ingredients-list ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .quantity-fixed {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #2a2a2a;
        border-radius: 4px;
        padding: 4px 12px;
        min-width: 70px;
    }
    
    .toggle-ingredients {
        text-align: left;
        display: inline-block;
        padding: 4px 8px;
        background-color: #3a3a3a;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.2s, color 0.2s;
        border: 1px solid #4a4a4a;
    }
    
    .toggle-ingredients:hover {
        color: #ffffff;
        background-color: #e22028;
        border-color: #e22028;
    }
    
    /* Add this to ensure the toggle button is visible */
    .toggle-ingredients i {
        display: inline-block;
        width: 16px;
        height: 16px;
        text-align: center;
        transition: transform 0.3s ease;
    }
    
    .toggle-ingredients:not(.active) i {
        transform: rotate(0deg);
    }
    
    .toggle-ingredients.active i {
        transform: rotate(180deg);
    }
</style>

    <div class="header">
        <svg class="cart-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/>
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
        </svg>
        <h1>Votre Panier</h1>
    </div>

    <div class="cart-container">
    @if(isset($panier) && isset($panier[0]) && isset($panier[0]->lignes) && $panier[0]->lignes->isNotEmpty())
        @foreach (['Entrée', 'Plats', 'Desserts', 'Soupe', 'Customisation'] as $categorie)
            @php
                $items = collect();
                foreach ($panier[0]->lignes as $ligne) {
                    // Check if this is really a custom item for categorization
                    $isItemCustom = (isset($ligne->is_custom) && $ligne->is_custom === true) ||
                                   (isset($ligne->ingredients) && is_array($ligne->ingredients) && count($ligne->ingredients) > 0);
                    
                    // Only push to Customisation category if it's genuinely a custom item
                    if ($categorie === 'Customisation') {
                        if ($isItemCustom || (isset($ligne->produit) && $ligne->produit->type_produit === 'Customisation')) {
                            $items->push($ligne);
                        }
                    }
                    // For other categories, only include if it matches and is NOT a custom item
                    else if (isset($ligne->produit) && $ligne->produit->type_produit === $categorie && !$isItemCustom) {
                        $items->push($ligne);
                    }
                }
            @endphp
            @if ($items->isNotEmpty())
                <h2>{{ $categorie }}</h2>
                @foreach ($items as $item)
                    <div class="cart-item">
                    @php
                        $photoPath = isset($item->produit) ? $item->produit->photo : '/media/concombre.png';
                        
                        // Handle absolute URLs (coming from asset() function)
                        if (strpos($photoPath, 'http') === 0) {
                            // Keep the URL as is - it's an absolute URL
                        } 
                        // Handle relative paths without leading slash
                        else if ($photoPath && substr($photoPath, 0, 1) !== '/') {
                            $photoPath = '/media/' . $photoPath;
                        }
                        
                        // Make sure we have a fallback
                        if (empty($photoPath)) {
                            $photoPath = '/media/concombre.png';
                        }
                        
                        // Check if this is a custom item - check both DB-based and cookie-based custom items
                        $isCustomItem = (isset($item->produit) && $item->produit->type_produit === 'Customisation') || 
                                      (isset($item->is_custom) && $item->is_custom === true) ||
                                      (isset($item->ingredients) && is_array($item->ingredients) && count($item->ingredients) > 0);
                        
                        // Get ingredients for custom items
                        $ingredients = [];
                        if ($isCustomItem) {
                            // First try to get ingredients from the database
                            $ingredients = DB::table('compo_paniers')
                                ->join('ingredients', 'compo_paniers.id_ingredient', '=', 'ingredients.id_ingredient')
                                ->where('compo_paniers.id_panier_ligne', $item->id_panier_ligne)
                                ->select('ingredients.nom', 'compo_paniers.prix')
                                ->get();
                        }
                    @endphp
                    <img src="{{ $photoPath }}" alt="{{ $item->nom }}" onerror="this.src='/media/concombre.png'">
                        <div class="item-details">  
                            <h3 class="item-name">{{ $item->nom }}</h3>
                            <p class="item-price">{{ number_format($item->prix_ttc, 2, ',', ' ') }} €</p>
                            
                            @if($isCustomItem)
                                <div class="mt-2">
                                    <button type="button" class="text-blue-500 text-sm toggle-ingredients" 
                                            data-target="ingredients-{{ $item->id_panier_ligne }}"
                                            onclick="toggleIngredients(this)">
                                        <i class="fas fa-chevron-down mr-1"></i> Voir les ingrédients
                                    </button>
                                    <div id="ingredients-{{ $item->id_panier_ligne }}" class="ingredients-list hidden mt-2 pl-3 text-sm">
                                        <ul class="list-disc">
                                            @php
                                                // Debug output for seeing what's available
                                                \Log::debug('Ingredients for item ' . $item->id_panier_ligne, [
                                                    'ingredients' => $ingredients,
                                                    'count' => count($ingredients),
                                                    'has_direct_ingredients' => isset($item->ingredients),
                                                    'direct_count' => isset($item->ingredients) ? count($item->ingredients) : 0
                                                ]);
                                                
                                                // First check if we have direct ingredients on the item object (for cookie cart)
                                                if (isset($item->ingredients) && is_array($item->ingredients) && count($item->ingredients) > 0) {
                                                    $ingredients = collect($item->ingredients)->map(function($ing) {
                                                        return (object) [
                                                            'nom' => $ing['name'] ?? $ing['nom'] ?? 'Ingrédient',
                                                            'prix' => $ing['price'] ?? $ing['prix'] ?? 0
                                                        ];
                                                    });
                                                }
                                                // If no direct ingredients but DB ingredients are empty, try to get from cookie
                                                else if (count($ingredients) === 0 && !isset($item->produit)) {
                                                    $cookieCart = json_decode(request()->cookie('panier'), true) ?? [];
                                                    foreach ($cookieCart as $cookieItem) {
                                                        if (isset($cookieItem['id_panier_ligne']) && $cookieItem['id_panier_ligne'] == $item->id_panier_ligne) {
                                                            if (isset($cookieItem['ingredients']) && is_array($cookieItem['ingredients'])) {
                                                                $ingredients = collect($cookieItem['ingredients'])->map(function($ing) {
                                                                    return (object) [
                                                                        'nom' => $ing['name'] ?? $ing['nom'] ?? 'Ingrédient',
                                                                        'prix' => $ing['price'] ?? $ing['prix'] ?? 0
                                                                    ];
                                                                });
                                                            }
                                                            break;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            
                                            @if(count($ingredients) > 0)
                                                @foreach($ingredients as $ing)
                                                    <li>{{ $ing->nom }} <span class="text-gray-400">(+{{ number_format($ing->prix, 2, ',', ' ') }} €)</span></li>
                                                @endforeach
                                            @else
                                                <li>Aucun ingrédient spécifié</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="quantity-controls">
                        @if($isCustomItem)
                            <!-- Custom items can't have their quantity changed -->
                            <div class="quantity-fixed">
                                <span class="quantity-value">{{ $item->quantite }}</span>
                            </div>
                        @else
                            <form>
                                @csrf
                                @method('PUT')
                                <button type="submit" name="action" value="decrement" class="quantity-btn"
                                data-url="{{ route('panier.update', ['id_panier_ligne' => $item->id_panier_ligne]) }}"
                                onclick="minus_quantity(this)">-</button>
                                <span class="quantity-value">{{ $item->quantite }}</span>
                                <button type="submit" name="action" value="increment" class="quantity-btn"
                                data-url="{{ route('panier.update', ['id_panier_ligne' => $item->id_panier_ligne]) }}"
                                onclick="add_quantity(this)">+</button>
                            </form>
                        @endif
                        
                            <button type="button" 
                                class="delete-btn"  
                                data-url="{{ route('panier.destroy', ['id_panier_ligne' => $item->id_panier_ligne]) }}" 
                                onclick="deleteItem(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif
        @endforeach
        
        <div class="cart-footer">
            <div class="total">
                <span class="total-labelht">Total HT</span>
                <span class="total-amountht">{{ number_format($total_ht, 2, ',', ' ') }} €</span>
            </div>
            <div class="total">
                <span class="total-label">Total</span>
                <span class="total-amount">{{ number_format($total, 2, ',', ' ') }} €</span>
            </div>
            <form action="/commande/create" method="POST" id="orderForm">
                @csrf
                <button type="submit" class="checkout-btn">Passer la commande</button>
            </form>
        </div>
    @else
        <div class="empty-cart">
            <p>Votre panier est vide</p>
            <a href="/" class="continue-shopping">Continuer vos achats</a>
        </div>
    @endif
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <h2 class="text-2xl font-bold mb-4">Paiement</h2>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="cardNumber">
                Numéro de carte
            </label>
            <input type="text" id="cardNumber" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="1234 5678 9012 3456">
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="expiry">
                    Date d'expiration
                </label>
                <input type="text" id="expiry" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="MM/YY">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="cvv">
                    CVV
                </label>
                <input type="text" id="cvv" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="123">
            </div>
        </div>
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-700 font-bold">Total à payer:</span>
            <span class="text-2xl font-bold text-[#D90429]">{{ number_format($total, 2, ',', ' ') }} €</span>
        </div>
        <div class="flex justify-end space-x-4">
            <button id="cancelPayment" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">
                Annuler
            </button>
            <button id="confirmPayment" class="px-4 py-2 bg-[#D90429] text-white rounded hover:bg-[#660708] transition-colors">
                Confirmer le paiement
            </button>
        </div>
    </div>
</div>

<script>
// Ensure CSRF token is accessible from JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Check if CSRF meta tag exists, add it if it doesn't
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const metaTag = document.createElement('meta');
        metaTag.name = 'csrf-token';
        metaTag.content = '{{ csrf_token() }}';
        document.head.appendChild(metaTag);
    }
    
    // Only add event listeners if the elements exist
    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModal').classList.add('flex');
        });
    }
    
    const cancelPayment = document.getElementById('cancelPayment');
    if (cancelPayment) {
        cancelPayment.addEventListener('click', function() {
            document.getElementById('paymentModal').classList.add('hidden');
            document.getElementById('paymentModal').classList.remove('flex');
        });
    }
    
    const confirmPayment = document.getElementById('confirmPayment');
    if (confirmPayment) {
        confirmPayment.addEventListener('click', function() {
            // Immediately provide visual feedback
            const button = this;
            button.disabled = true;
            button.textContent = 'Traitement en cours...';
            
            // Get the CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Short delay to ensure UI updates before the potentially blocking AJAX call
            setTimeout(function() {
                // Make the API call to create the order
                $.ajax({
                    url: '/commande/create',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    data: {
                        _token: csrfToken
                    },
                    success: function(response) {
                        console.log('Order creation response:', response);
                        
                        if (response.success) {
                            // Set success flash message
                            $.post('/set-flash-message', {
                                _token: csrfToken,
                                type: 'success',
                                message: response.message
                            }, function() {
                                // Show a message before redirecting
                                alert('Commande confirmée avec succès!');
                                window.location.href = '/panier';
                            }).fail(function(err) {
                                console.error('Failed to set flash message:', err);
                                alert('Commande confirmée, mais erreur lors de l\'affichage du message.');
                                window.location.href = '/panier';
                            });
                        } else {
                            // Set error flash message
                            console.error('Order creation failed:', response.message);
                            $.post('/set-flash-message', {
                                _token: csrfToken,
                                type: 'error',
                                message: response.message
                            }, function() {
                                alert('Erreur: ' + response.message);
                                window.location.href = '/panier';
                            }).fail(function(err) {
                                console.error('Failed to set flash message:', err);
                                alert('Erreur: ' + response.message);
                                window.location.href = '/panier';
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Order creation AJAX error:', xhr);
                        console.error('Status:', xhr.status);
                        console.error('Response:', xhr.responseText);
                        
                        // Try to parse the response
                        let errorMessage = 'Une erreur est survenue lors de la création de la commande.';
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse.message) {
                                errorMessage = errorResponse.message;
                            }
                        } catch (e) {
                            console.error('Could not parse error response:', e);
                        }
                        
                        // Set error flash message
                        $.post('/set-flash-message', {
                            _token: csrfToken,
                            type: 'error',
                            message: errorMessage
                        }, function() {
                            alert('Erreur: ' + errorMessage);
                            button.disabled = false;
                            button.textContent = 'Confirmer le paiement';
                        }).fail(function(err) {
                            console.error('Failed to set flash message:', err);
                            alert('Erreur: ' + errorMessage);
                            button.disabled = false;
                            button.textContent = 'Confirmer le paiement';
                        });
                    }
                });
            }, 50); // small delay to ensure UI updates
        });
    }
});

// Function to decrease quantity
function minus_quantity(button) {
    event.preventDefault();
    const url = button.getAttribute('data-url');
    const quantitySpan = button.parentElement.querySelector('.quantity-value');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    console.log('Updating cart item at URL:', url);
    
    // Extract the ID from the URL
    const idMatch = url.match(/\/panier\/(\d+)$/);
    if (!idMatch || !idMatch[1]) {
        console.error('Invalid URL format, cannot extract ID');
        showToast('Erreur lors de la mise à jour de la quantité', 'error');
        return;
    }
    
    const id = idMatch[1];
    // Use the correct API endpoint - matching the one in routes/api.php
    const apiUrl = '/api/panier-update/' + id;
    
    console.log('Using API URL:', apiUrl);
    
    $.ajax({
        url: apiUrl,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        data: {
            _token: csrfToken,
            action: 'decrement'
        },
        success: function(response) {
            handleQuantityUpdateSuccess(response, quantitySpan);
        },
        error: function(xhr) {
            console.error('API route failed:', xhr);
            console.error('Response:', xhr.responseText);
            showToast('Erreur lors de la mise à jour de la quantité', 'error');
        }
    });
}

// Function to increase quantity
function add_quantity(button) {
    event.preventDefault();
    const url = button.getAttribute('data-url');
    const quantitySpan = button.parentElement.querySelector('.quantity-value');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    console.log('Increasing quantity at URL:', url);
    
    // Extract the ID from the URL
    const idMatch = url.match(/\/panier\/(\d+)$/);
    if (!idMatch || !idMatch[1]) {
        console.error('Invalid URL format, cannot extract ID');
        showToast('Erreur lors de la mise à jour de la quantité', 'error');
        return;
    }
    
    const id = idMatch[1];
    // Use the correct API endpoint - matching the one in routes/api.php
    const apiUrl = '/api/panier-update/' + id;
    
    console.log('Using API URL:', apiUrl);
    
    $.ajax({
        url: apiUrl,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        data: {
            _token: csrfToken,
            action: 'increment'
        },
        success: function(response) {
            handleQuantityUpdateSuccess(response, quantitySpan);
        },
        error: function(xhr) {
            console.error('API route failed:', xhr);
            console.error('Response:', xhr.responseText);
            showToast('Erreur lors de la mise à jour de la quantité', 'error');
        }
    });
}

// Function to handle successful quantity updates
function handleQuantityUpdateSuccess(response, quantitySpan) {
    if (response.html) {
        // If server returned HTML, replace the entire cart content
        document.querySelector('#contenu-panier').outerHTML = response.html;
        
        // Re-attach event listeners to the new elements
        attachEventListeners();
    } else {
        // Otherwise just update the quantity displayed
        quantitySpan.textContent = response.nouvelle_quantite;
        
        // Update total if provided
        if (response.montant_total) {
            const totalElement = document.querySelector('.total-amount');
            if (totalElement) {
                totalElement.textContent = new Intl.NumberFormat('fr-FR', { 
                    minimumFractionDigits: 2, 
                    maximumFractionDigits: 2 
                }).format(response.montant_total) + ' €';
            }
        }
    }
    
    // Update the cart count in the header
    updateHeaderCartCount(response.count || 0);
    
    // Show success message
    showToast('Quantité mise à jour', 'success');
}

// Function to delete item
function deleteItem(button) {
    const url = button.getAttribute('data-url');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    console.log('Deleting item at URL:', url);
    
    // Extract the ID from the URL
    const idMatch = url.match(/\/panier\/(\d+)$/);
    if (!idMatch || !idMatch[1]) {
        console.error('Invalid URL format, cannot extract ID');
        showToast('Erreur lors de la suppression du produit', 'error');
        return;
    }
    
    const id = idMatch[1];
    // Use the correct API endpoint - matching the one in routes/api.php
    const apiUrl = '/api/panier-update/' + id;
    
    console.log('Using API URL:', apiUrl);
    
    $.ajax({
        url: apiUrl,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        data: {
            _token: csrfToken
        },
        success: function(response) {
            handleDeleteSuccess(response);
        },
        error: function(xhr) {
            console.error('API route failed:', xhr);
            console.error('Status:', xhr.status);
            console.error('Response:', xhr.responseText);
            showToast('Erreur lors de la suppression du produit', 'error');
        }
    });
}

// Common function to handle successful deletions
function handleDeleteSuccess(response) {
    // Replace the entire cart content with the updated HTML
    if (response.html) {
        document.querySelector('#contenu-panier').outerHTML = response.html;
        
        // Re-attach event listeners to the new elements
        attachEventListeners();
        
        // Update the cart count in the header
        updateHeaderCartCount(response.count || 0);
        
        // Show success message
        showToast('Produit supprimé du panier', 'success');
    }
}

// Function to re-attach event listeners after DOM updates
function attachEventListeners() {
    // Re-attach order form event listener
    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModal').classList.add('flex');
        });
    }
    
    // Re-attach modal buttons event listeners
    const cancelPayment = document.getElementById('cancelPayment');
    if (cancelPayment) {
        cancelPayment.addEventListener('click', function() {
            document.getElementById('paymentModal').classList.add('hidden');
            document.getElementById('paymentModal').classList.remove('flex');
        });
    }
    
    const confirmPayment = document.getElementById('confirmPayment');
    if (confirmPayment) {
        // Re-attach the payment confirmation handler
        confirmPayment.addEventListener('click', function() {
            // Implementation remains the same...
        });
    }
    
    // Re-attach ingredients toggle handlers - use the onclick attribute approach instead
    document.querySelectorAll('.toggle-ingredients').forEach(button => {
        button.setAttribute('onclick', 'toggleIngredients(this)');
    });
}

// Function to show toast messages
function showToast(message, type = 'success') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
    
    // Add icon based on message type
    const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
    toast.innerHTML = `<i class="fas fa-${icon} mr-2"></i> ${message}`;
    
    // Add to DOM
    document.body.appendChild(toast);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Function to update the cart count in the header
function updateHeaderCartCount(count) {
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

// Function to toggle ingredient lists
function toggleIngredients(button) {
    console.log('Toggle button clicked:', button);
    const targetId = button.getAttribute('data-target');
    console.log('Target ID:', targetId);
    const targetElement = document.getElementById(targetId);
    console.log('Target element:', targetElement);
    
    if (targetElement) {
        if (targetElement.classList.contains('hidden')) {
            console.log('Showing ingredients list');
            targetElement.classList.remove('hidden');
            button.classList.add('active');
            button.innerHTML = '<i class="fas fa-chevron-up mr-1"></i> Masquer les ingrédients';
        } else {
            console.log('Hiding ingredients list');
            targetElement.classList.add('hidden');
            button.classList.remove('active');
            button.innerHTML = '<i class="fas fa-chevron-down mr-1"></i> Voir les ingrédients';
        }
    } else {
        console.error('Target element not found:', targetId);
    }
}

// Toggle ingredients list for custom items
document.addEventListener('DOMContentLoaded', function() {
    // Add onclick attribute directly to toggle-ingredients buttons
    document.querySelectorAll('.toggle-ingredients').forEach(button => {
        button.setAttribute('onclick', 'toggleIngredients(this)');
    });
    
    // Make sure we also attach this event to any new toggle buttons added via AJAX
    attachEventListeners();
});
</script>
