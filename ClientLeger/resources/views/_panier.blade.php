
<div class="container" id="contenu-panier">
    <div class="header">
        <svg class="cart-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/>
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
        </svg>
        <h1>Votre Panier</h1>
    </div>

    <div class="cart-container">
    @foreach (['Entrée', 'Plats', 'Desserts', 'Soupe', 'Customisation'] as $categorie)
            @php
                $items = $panier[0]->lignes->where('produit.type_produit', $categorie);
            @endphp
            @if ($items->isNotEmpty())
                <h2>{{ $categorie }}</h2>
                @foreach ($items as $item)
                    <div class="cart-item">
                    <img src="{{ $item->produit->photo }}" alt="{{ $item->nom }}">
                        <div class="item-details">  
                            <h3 class="item-name">{{ $item->nom }}</h3>
                            <p class="item-price">{{ number_format($item->prix_ttc, 2, ',', ' ') }} €</p>
                        </div>
                        <div class="quantity-controls">
                    
                            <form>
                                @csrf
                                @method('PUT')
                                <button type="submit" name="action" value="decrement" class="quantity-btn"
                                data-url="{{ route('panier.update', ['id_panier_ligne' => $item->id_panier]) }}"
                                onclick="minus_quantity(this)">-</button>
                                <span class="quantity-value">{{ $item->quantite }}</span>
                                <button type="submit" name="action" value="increment" class="quantity-btn"
                                data-url="{{ route('panier.update', ['id_panier_ligne' => $item->id_panier]) }}"
                                onclick="add_quantity(this)">+</button>

                            </form>
                        
                            <button type="button" 
                                class="delete-btn"  
                                data-url="{{ route('panier.destroy', ['id_panier_ligne' => $item->id_panier]) }}" 
                                onclick="deleteItem(this)">&#128465;
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
            <form action= "" method="POST">
                @csrf
                <button type="submit" class="checkout-btn">Passer la commande</button>
            </form>
        </div>
    </div>
</div>
