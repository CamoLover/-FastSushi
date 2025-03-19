<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meilleures Ventes</title>
    <script>
        // Fonction pour récupérer les meilleures ventes depuis l'API
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/best-sellers')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const products = data.data; // Récupère les produits depuis la réponse de l'API
                        const tableBody = document.querySelector('#products-list'); // Sélectionne le tbody

                        // Parcours des produits et ajout à la table
                        products.forEach(product => {
                            const productDiv = document.createElement('div');
                            productDiv.classList.add('product');

                            const img = document.createElement('img');
                            img.src = product.photo; // Utilise l'URL de l'image renvoyée par l'API
                            img.alt = product.nom;
                            img.width = 150;

                            const h3 = document.createElement('h3');
                            h3.textContent = product.nom;

                            // Ajoute l'image et le nom du produit à la div
                            productDiv.appendChild(img);
                            productDiv.appendChild(h3);

                            // Ajoute la div du produit au tbody
                            tableBody.appendChild(productDiv);
                        });
                    } else {
                        alert("Erreur lors du chargement des produits.");
                    }
                })
                .catch(error => {
                    console.error("Erreur API:", error);
                    alert("Une erreur est survenue lors de la récupération des produits.");
                });
        });
    </script>
</head>
<body>
    <h1>Meilleures Ventes</h1>
    <div id="products-list"></div> <!-- Conteneur où les produits seront ajoutés dynamiquement -->

</body>
</html>
