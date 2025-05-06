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
                            img.src = `data:${product.photo_type || 'image/png'};base64,${product.photo}`;
                            img.alt = product.nom;
                            img.width = 150;
                            img.onerror = function() {
                                this.onerror = null;
                                this.src = 'https://placehold.co/400x300/252422/FFFCF2?text=Fast+Sushi';
                            };

                            const h3 = document.createElement('h3');
                            h3.textContent = product.nom;

                            // Ajoute l'image et le nom du produit à la div
                            productDiv.appendChild(img);
                            productDiv.appendChild(h3);

                            // Ajoute la div du produit au tbody
                            tableBody.appendChild(productDiv);
                        });
                    } else {
                        showNotification("Erreur lors du chargement des produits.", "error");
                    }
                })
                .catch(error => {
                    console.error("Erreur API:", error);
                    showNotification("Une erreur est survenue lors de la récupération des produits.", "error");
                });
        });
    </script>
</head>
<body>
    <h1>Meilleures Ventes</h1>
    <div id="products-list"></div> <!-- Conteneur où les produits seront ajoutés dynamiquement -->

</body>
</html>
