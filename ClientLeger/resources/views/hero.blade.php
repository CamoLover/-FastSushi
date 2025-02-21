<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meilleures Ventes</title>
</head>
<body>
    <h1>Meilleures Ventes</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité Vendue</th>
                <th>Commandes</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bestSellers as $product)
                <tr>
                    <td>{{ $product->nom }}</td>
                    <td>{{ $product->total_quantite_vendue }}</td>
                    <td>{{ $product->historique_commandes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
