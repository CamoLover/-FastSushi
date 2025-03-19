
function deleteItem(button) {
    var url = $(button).data('url'); // Récupère l'URL dynamique à partir de data-url

    $.ajax({
        url: url, // Utilisation de l'URL dynamique
        type: 'DELETE', // Méthode HTTP DELETE
        success: function(response) {
            // Si la suppression est réussie, retirer l'élément du DOM
            $(button).closest('.cart-item').remove();
            alert('Produit supprimé avec succès!');
        },
        error: function(e) {
            alert('Une erreur est survenue');
            console.log(e);
        }
    });
}
