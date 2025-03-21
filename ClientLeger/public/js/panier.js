function deleteItem(button) {
    var url = $(button).data('url'); // Récupère l'URL dynamique à partir de data-url

    $.ajax({
        url: url, // Utilisation de l'URL dynamique
        type: 'DELETE', // Méthode HTTP DELETE
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.html) {
                $("#contenu-panier").html(response.html); // Met à jour la div contenant le panier
            } else {
                // Si pas de HTML retourné, recharger la page
                location.reload();
            }
        },
        error: function(e) {
            console.error('Erreur lors de la suppression', e.responseText);
            alert('Une erreur est survenue lors de la suppression du produit. Veuillez réessayer.');
        }
    });
}

function minus_quantity(button) {
    var url = $(button).data('url');
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: url,
        type: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            _token: csrfToken, // Ajout du token CSRF
            action: 'decrement', // Indique l'action
            quantite: 1 // On enlève 1 à chaque appel
        },
        success: function(response) {
            if (response.html) {
                $("#contenu-panier").html(response.html);
            } else {
                // Si pas de HTML retourné, recharger la page
                location.reload();
            }
        },
        error: function(e) {
            console.error('Erreur lors de la mise à jour de la quantité', e.responseText);
            alert('Une erreur est survenue lors de la mise à jour de la quantité. Veuillez réessayer.');
        }
    });
}

function add_quantity(button) {
    var url = $(button).data('url');
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: url,
        type: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            _token: csrfToken,
            action: 'increment',
            quantite: 1 // On ajoute 1
        },
        success: function(response) {
            if (response.html) {
                $("#contenu-panier").html(response.html);
            } else {
                // Si pas de HTML retourné, recharger la page
                location.reload();
            }
        },
        error: function(e) {
            console.error('Erreur lors de la mise à jour de la quantité', e.responseText);
            alert('Une erreur est survenue lors de la mise à jour de la quantité. Veuillez réessayer.');
        }
    });
}

