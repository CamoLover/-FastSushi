package application.model;

import javafx.scene.image.Image;
import java.io.ByteArrayInputStream;
import java.util.Base64;

public class Produit {
    private int id_produit;
    private String nom;
    private String type_produit;
    private double prix_ttc;
    private double prix_ht;
    private String photo;
    private String photo_type;
    private double tva;
    private String description;

    public Produit(int id_produit, String nom, String type_produit, double prix_ttc, double prix_ht, 
                  String photo, String photo_type, double tva, String description) {
        this.id_produit = id_produit;
        this.nom = nom;
        this.type_produit = type_produit;
        this.prix_ttc = prix_ttc;
        this.prix_ht = prix_ht;
        this.photo = photo;
        this.photo_type = photo_type;
        this.tva = tva;
        this.description = description;
    }

    public int getId_produit() { return id_produit; }
    public String getNom() { return (nom == null) ? "" : nom; }
    public String getType_produit() { return (type_produit == null) ? "" : type_produit; }
    public double getPrix_ttc() { return prix_ttc; }
    public double getPrix_ht() { return prix_ht; }
    public String getPhoto() { return photo; }
    public String getPhoto_type() { return (photo_type == null) ? "" : photo_type; }
    public double getTva() { return tva; }
    public String getDescription() { return (description == null) ? "" : description; }

    public Image getPhotoAsImage() {
        if (photo == null || photo.isEmpty()) {
            return null;
        }
        try {
            byte[] imageData = Base64.getDecoder().decode(photo);
            return new Image(new ByteArrayInputStream(imageData));
        } catch (Exception e) {
            e.printStackTrace();
            return null;
        }
    }
} 