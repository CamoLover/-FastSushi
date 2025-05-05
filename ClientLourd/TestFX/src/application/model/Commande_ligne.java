package application.model;

public class Commande_ligne {

	  private int id_commande_ligne;
	  private int id_commande;
	  private int id_produit;
	    private int quantite;
	    private String nom;
	    private double prix_ht;
	    private double prix_ttc;
	    
	   
	    public Commande_ligne(int id_commande_ligne, int id_commande, int id_produit,int quantite, String nom, double prix_ht, double prix_ttc) {
			this.id_commande_ligne = id_commande_ligne;
			this.id_commande = id_commande;
			this.id_produit = id_produit;
			this.quantite = quantite;
			this.nom = nom;
			this.prix_ht = prix_ht;
			this.prix_ttc = prix_ttc;
}
		
	    
		public int getId_commande_ligne() { return id_commande_ligne; }
		public int getId_commande() { return id_commande; }
		public int getId_produit() { return id_produit; }
		public int getQuantite() { return quantite; }
		public String getNom() { return (nom == null) ? "" : nom; }
		public double getPrix_ht() { return prix_ht; }
		public double getPrix_ttc() { return prix_ttc; }
		
		public double getTotalDetail() { return prix_ttc * quantite; }
}

