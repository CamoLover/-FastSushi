package application.model;

public class Client {
    private int id_client;
    private String nom;
    private String prenom;
    private String email;
    private String tel;
    private String ville;
    private String mdp;

    public Client(int id, String nom, String prenom, String email, String tel, String ville, String mdp) {
        this.id_client = id;
        this.nom = nom;
        this.prenom = prenom;
        this.email = email;
        this.tel = tel;
        this.ville = ville;
        this.mdp = mdp;
    }

    public int getId() { return id_client; }
    public String getNom() { return (nom == null) ? "" : nom; }
    public String getPrenom() { return (prenom == null) ? "" : prenom; }
    public String getEmail() { return (email == null) ? "" : email; }
    public String getTel() { return (tel == null) ? "" : tel; }
    public String getVille() { return (ville == null) ? "" : ville; }
}
