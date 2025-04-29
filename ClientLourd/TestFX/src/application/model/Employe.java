package application.model;

public class Employe {
    private int id_employe;
    private String nom;
    private String prenom;
    private String email;
    private String mdp;
    private String statut_emp;

    public Employe(int id, String nom, String prenom, String email, String mdp, String statut_emp) {
        this.id_employe = id;
        this.nom = nom;
        this.prenom = prenom;
        this.email = email;
        this.mdp = mdp;
        this.statut_emp = statut_emp;
    }

    public int getId() { return id_employe; }
    public String getNom() { return (nom == null) ? "" : nom; }
    public String getPrenom() { return (prenom == null) ? "" : prenom; }
    public String getEmail() { return (email == null) ? "" : email; }
    public String getStatut() { return (statut_emp == null) ? "" : statut_emp; }
}
