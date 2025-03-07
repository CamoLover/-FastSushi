<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Panier - Restaurant Sushi</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background-color: #252422;
            color: white;
            font-family: system-ui, -apple-system, sans-serif;
        }

        /* Container styles */
        .container {
            max-width: 64rem;
            margin: 0 auto;
            padding: 1.5rem;
        }

        /* Header styles */
        .header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 1.875rem;
            font-weight: bold;
        }

        .cart-icon {
            width: 2rem;
            height: 2rem;
            color: #D90429;
        }

        /* Cart container */
        .cart-container {
            background-color: #403D39;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Cart item */
        .cart-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #660708;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item img {
            width: 6rem;
            height: 6rem;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .item-price {
            color: #CCC5B9;
        }

        /* Quantity controls */
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .quantity-btn {
            padding: 0.25rem;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 0.25rem;
            transition: background-color 0.2s;
        }

        .quantity-btn:hover {
            background-color: #D90429;
        }

        .quantity-value {
            width: 2rem;
            text-align: center;
        }

        .delete-btn {
            margin-left: 1rem;
            padding: 0.5rem;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 0.25rem;
            transition: background-color 0.2s;
        }

        .delete-btn:hover {
            background-color: #D90429;
        }

        /* Cart footer */
        .cart-footer {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #660708;
        }

        .total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .total-label {
            font-size: 1.25rem;
        }

        .total-amount {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .checkout-btn {
            width: 100%;
            padding: 0.75rem;
            background-color: #D90429;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .checkout-btn:hover {
            background-color: #BA181B;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <svg class="cart-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/>
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
        </svg>
        <h1>Votre Panier</h1>
    </div>

    <div class="cart-container">
        @foreach ($panier[0]->lignes as $item)
        <div class="cart-item">
            <img src="{{ $item->produit->photo }}" alt="{{ $item->nom }}">
            <div class="item-details">  
                <h3 class="item-name">{{ $item->nom }}</h3>
                <p class="item-price">{{ number_format($item->prix_ttc, 2, ',', ' ') }} €</p>
            </div>
            <div class="quantity-controls">
            <form action="{{ route('panier.update', ['id' => $item->id_panier]) }}" method="POST">

                    @csrf
                    @method('PUT')
                    <button type="submit" name="action" value="decrement" class="quantity-btn">-</button>
                    <span class="quantity-value">{{ $item->quantite }}</span>
                    <button type="submit" name="action" value="increment" class="quantity-btn">+</button>
                </form>
                <form action="{{ route('panier.destroy', ['id' => $item->id_panier]) }}" method="POST">

                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">&#128465;</button>
                </form>
            </div>
        </div>
        @endforeach
        
        <div class="cart-footer">
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

</body>
</html>