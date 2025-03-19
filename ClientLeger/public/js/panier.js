
function deleteItem(button) {
    var url = $(button).data('url'); // Récupère l'URL dynamique à partir de data-url

    $.ajax({
        url: url, // Utilisation de l'URL dynamique
        type: 'DELETE', // Méthode HTTP DELETE
        success: function(response) {
            $("#contenu-panier").html(response.html); // Met à jour la div contenant le panier
            alert('Produit supprimé avec succès!');
        },
        error: function(e) {
            alert('Une erreur est survenue');
            console.log(e);
        }
    });
}

function minus_quantity(button) {
    var url = $(button).data('url');
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: url,
        type: 'PUT',
        data: {
            _token: csrfToken, // Ajout du token CSRF
            action: 'decrement', // Indique l'action
            quantite: 1 // On enlève 1 à chaque appel
        },
        success: function(response) {
            $("#contenu-panier").html(response.html);
        },
        error: function(e) {
            alert('Une erreur est survenue');
            console.log(e.responseText);
        }
    });
}

function add_quantity(button) {
    var url = $(button).data('url');
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: url,
        type: 'PUT',
        data: {
            _token: csrfToken,
            action: 'increment',
            quantite: 1 // On ajoute 1
        },
        success: function(response) {
            $("#contenu-panier").html(response.html);
        },
        error: function(e) {
            alert('Une erreur est survenue');
            console.log(e.responseText);
        }
    });
}

