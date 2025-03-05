<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Panier</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/panier.js') }}" defer></script>
</head>
<body>

    <h1>Test d'ajout au panier</h1>

    <button class="ajouter-panier" data-id="1" data-quantite="1">Ajouter Produit 1</button>
    <button class="ajouter-panier" data-id="2" data-quantite="1">Ajouter Produit 2</button>

</body>
</html>
