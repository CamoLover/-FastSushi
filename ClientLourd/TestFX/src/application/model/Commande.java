package application.model;

public class Commande {
	  private int id_commande;
	  private int id_client;
	    private String date_panier;
	    private String montant_tot;
	    private String statut;

	    public Commande(int id_commande, int id_client , String date_panier, String montant_tot, String statut) {
	        this.id_commande = id_commande;
	        this.id_client = id_client;
	        this.date_panier = date_panier;
	        this.montant_tot = montant_tot;
	        this.statut = statut;
	        
	    }

	    public int getId_commande() { return id_commande; }
	    public int getId_client() { return id_client; }
	    public String getDate_panier() { return (date_panier == null) ? "" : date_panier; }
	    public String getMontant_tot() { return (montant_tot == null) ? "" : montant_tot; }
	    public String getStatut() { return (statut == null) ? "" : statut; }
	}

