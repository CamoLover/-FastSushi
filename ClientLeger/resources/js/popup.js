document.addEventListener("DOMContentLoaded", function () {
    let panier = JSON.parse(localStorage.getItem("panier")) || [];

    function sauvegarderPanier() {
        localStorage.setItem("panier", JSON.stringify(panier));
    }

    function ajouterAuPanier(id_produit, quantite) {
        fetch("/ajouter-au-panier", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            },
            body: JSON.stringify({ id_produit, quantite }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.local) {
                    let produit = data.produit;
                    let item = panier.find(p => p.id_produit === produit.id_produit);
                    if (item) {
                        item.quantite += quantite;
                    } else {
                        panier.push({ ...produit, quantite });
                    }
                    sauvegarderPanier();
                    alert("Produit ajouté au panier !");
                } else {
                    alert(data.message);
                }
            });
    }

    window.addEventListener("beforeunload", function (event) {
        if (panier.length > 0) {
            event.preventDefault();
            event.returnValue = "Vous avez des articles non sauvegardés. Voulez-vous vraiment quitter ?";
        }
    });

    document.querySelectorAll(".ajouter-panier").forEach(button => {
        button.addEventListener("click", function () {
            let id_produit = this.dataset.id;
            let quantite = parseInt(this.dataset.quantite) || 1;
            ajouterAuPanier(id_produit, quantite);
        });
    });
});
