package application.model;

public class IngredientProduit {
    private int id;
    private int ingredient_id;
    private int produit_id;
    private String created_at;
    private String updated_at;

    public IngredientProduit(int id, int ingredient_id, int produit_id, String created_at, String updated_at) {
        this.id = id;
        this.ingredient_id = ingredient_id;
        this.produit_id = produit_id;
        this.created_at = created_at;
        this.updated_at = updated_at;
    }

    public int getId() { return id; }
    public int getIngredient_id() { return ingredient_id; }
    public int getProduit_id() { return produit_id; }
    public String getCreated_at() { return (created_at == null) ? "" : created_at; }
    public String getUpdated_at() { return (updated_at == null) ? "" : updated_at; }
} 