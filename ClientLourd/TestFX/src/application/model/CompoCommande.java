package application.model;

public class CompoCommande {
    private int id_commande_ligne;
    private int id_ingredient;
    private double prix;

    public CompoCommande(int id_commande_ligne, int id_ingredient, double prix) {
        this.id_commande_ligne = id_commande_ligne;
        this.id_ingredient = id_ingredient;
        this.prix = prix;
    }

    public int getId_commande_ligne() { return id_commande_ligne; }
    public int getId_ingredient() { return id_ingredient; }
    public double getPrix() { return prix; }
} 