package application.model;

import java.io.ByteArrayInputStream;
import java.util.Base64;

import javafx.scene.image.Image;

public class Ingredient {
    private int id_ingredient;
    private String nom;
    private String photo;
    private String photo_type;
    private double prix_ht;
    private String type_ingredient;

    public Ingredient(int id_ingredient, String nom, String photo, String photo_type, double prix_ht, String type_ingredient) {
        this.id_ingredient = id_ingredient;
        this.nom = nom;
        this.photo = photo;
        this.photo_type = photo_type;
        this.prix_ht = prix_ht;
        this.type_ingredient = type_ingredient;
    }

    public int getId_ingredient() { return id_ingredient; }
    public String getNom() { return (nom == null) ? "" : nom; }
    public String getPhoto_type() { return (photo_type == null) ? "" : photo_type; }
    public double getPrix_ht() { return prix_ht; }
    public String getType_ingredient() { return (type_ingredient == null) ? "" : type_ingredient; }
    
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

