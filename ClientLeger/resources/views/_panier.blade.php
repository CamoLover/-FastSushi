<div class="container" id="contenu-panier">
    <div class="header">
        <svg class="cart-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/>
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
        </svg>
        <h1>Votre Panier</h1>
    </div>

    <div class="cart-container">
    @if(isset($panier) && is_array($panier) && count($panier) > 0 && isset($panier[0]) && $panier[0]->lignes && $panier[0]->lignes->isNotEmpty())
        @foreach (['Entrée', 'Plats', 'Desserts', 'Soupe', 'Customisation'] as $categorie)
            @php
                $items = collect();
                foreach ($panier[0]->lignes as $ligne) {
                    if (isset($ligne->produit) && $ligne->produit->type_produit === $categorie) {
                        $items->push($ligne);
                    }
                }
            @endphp
            @if ($items->isNotEmpty())
                <h2>{{ $categorie }}</h2>
                @foreach ($items as $item)
                    <div class="cart-item">
                    <img src="{{ isset($item->produit) ? $item->produit->photo : asset('media/concombre.png') }}" alt="{{ $item->nom }}">
                        <div class="item-details">  
                            <h3 class="item-name">{{ $item->nom }}</h3>
                            <p class="item-price">{{ number_format($item->prix_ttc, 2, ',', ' ') }} €</p>
                        </div>
                        <div class="quantity-controls">
                    
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
});

document.getElementById('orderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    document.getElementById('paymentModal').classList.remove('hidden');
    document.getElementById('paymentModal').classList.add('flex');
});

document.getElementById('cancelPayment').addEventListener('click', function() {
    document.getElementById('paymentModal').classList.add('hidden');
    document.getElementById('paymentModal').classList.remove('flex');
});

document.getElementById('confirmPayment').addEventListener('click', function() {
    // Simulate payment processing
    this.disabled = true;
    this.textContent = 'Traitement en cours...';
    
    // Get the CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Use form data for better compatibility
    const formData = new FormData();
    formData.append('_token', csrfToken);
    
    // Make the API call to create the order
    $.ajax({
        url: '/commande/create',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                // Set success flash message
                $.post('/set-flash-message', {
                    _token: '{{ csrf_token() }}',
                    type: 'success',
                    message: response.message
                }, function() {
                    window.location.href = '/panier';
                });
            } else {
                // Set error flash message
                $.post('/set-flash-message', {
                    _token: '{{ csrf_token() }}',
                    type: 'error',
                    message: response.message
                }, function() {
                    window.location.href = '/panier';
                });
            }
        },
        error: function(xhr) {
            // Set error flash message
            $.post('/set-flash-message', {
                _token: '{{ csrf_token() }}',
                type: 'error',
                message: 'Une erreur est survenue lors de la création de la commande.'
            }, function() {
                window.location.href = '/panier';
            });
        }
    });
});
</script>
