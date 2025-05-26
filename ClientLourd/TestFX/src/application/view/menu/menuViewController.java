package application.view.menu;
import java.io.ByteArrayInputStream;
import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Base64;
import java.util.HashSet;
import java.util.Set;

import application.LoginController;
import application.model.Modele;
import application.model.Produit;
import application.model.Ingredient;
import javafx.fxml.FXML;
import javafx.geometry.Pos;
import javafx.scene.control.*;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.HBox;
import javafx.scene.layout.Priority;
import javafx.scene.layout.Region;
import javafx.scene.layout.VBox;
import javafx.stage.FileChooser;
import javafx.stage.Stage;
import javafx.beans.value.ChangeListener;

public class menuViewController {

    @FXML
    private VBox IngredientContainer;

    @FXML
    private VBox ProduitContainer;

    @FXML
    private TabPane tabPane;

    @FXML
    private Tab ingredienttab;
    
    @FXML
    private Tab produittab;
    
    // Product edit controls
    @FXML private VBox productEditPane;
    @FXML private ImageView productImage;
    @FXML private TextField productNameField;
    @FXML private TextField productPriceTTCField;
    @FXML private TextField productPriceHTField;
    @FXML private TextField productTVAField;
    @FXML private ComboBox<String> productCategoryCombo;
    @FXML private TextArea productDescriptionArea;
    @FXML private Label productPriceTTCLabel;
    
    // Ingredient edit controls
    @FXML private VBox ingredientEditPane;
    @FXML private ImageView ingredientImage;
    @FXML private TextField ingredientNameField;
    @FXML private TextField ingredientPriceField;
    @FXML private ComboBox<String> ingredientCategoryCombo;
    
    @FXML private Button changeProductImageButton;
    @FXML private Button changeIngredientImageButton;
    
    private LoginController loginController;
    private Modele modele;
    private Produit currentProduct;
    private Ingredient currentIngredient;
    private String currentProductImage;
    private String currentIngredientImage;
    
    private VBox lastSelectedProduct = null;
    private VBox lastSelectedIngredient = null;
    
    @FXML
    public void initialize() {
        try {
            loginController = LoginController.getInstance();
            modele = Modele.getInstance();
            
            // Hide edit panes initially
            productEditPane.setVisible(false);
            ingredientEditPane.setVisible(false);
            
            // Add listeners for automatic TTC calculation
            ChangeListener<String> priceCalculationListener = (obs, oldVal, newVal) -> {
                calculateAndDisplayTTC();
            };
            
            productPriceHTField.textProperty().addListener(priceCalculationListener);
            productTVAField.textProperty().addListener(priceCalculationListener);
            
            // Load initial data
            refreshData();
            loadCategories();
            
            // Add tab change listener
            tabPane.getSelectionModel().selectedItemProperty().addListener((obs, oldTab, newTab) -> {
                refreshData();
                productEditPane.setVisible(false);
                ingredientEditPane.setVisible(false);
            });
            
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
    
    private void loadCategories() throws SQLException {
        // Load product categories
        Set<String> productCategories = new HashSet<>();
        String query = "SELECT DISTINCT type_produit FROM produits";
        PreparedStatement stmt = modele.getConnection().prepareStatement(query);
        ResultSet rs = stmt.executeQuery();
        while (rs.next()) {
            productCategories.add(rs.getString("type_produit"));
        }
        productCategoryCombo.getItems().clear();
        productCategoryCombo.getItems().addAll(productCategories);
        
        // Load ingredient categories
        Set<String> ingredientCategories = new HashSet<>();
        query = "SELECT DISTINCT type_ingredient FROM ingredients";
        stmt = modele.getConnection().prepareStatement(query);
        rs = stmt.executeQuery();
        while (rs.next()) {
            ingredientCategories.add(rs.getString("type_ingredient"));
        }
        ingredientCategoryCombo.getItems().clear();
        ingredientCategoryCombo.getItems().addAll(ingredientCategories);
    }
    
    @FXML
    public void handleRefresh() {
        refreshData();
    }
    
    private void refreshData() {
        Tab selectedTab = tabPane.getSelectionModel().getSelectedItem();
        if (selectedTab == produittab) {
            showProduitDetails();
        } else if (selectedTab == ingredienttab) {
            showIngredientDetails();
        }
    }
    
    private void clearSelection() {
        if (lastSelectedProduct != null) {
            lastSelectedProduct.getStyleClass().remove("selected");
        }
        if (lastSelectedIngredient != null) {
            lastSelectedIngredient.getStyleClass().remove("selected");
        }
    }
    
    @FXML
    public void handleAddProduct() {
        clearSelection();
        currentProduct = null;
        currentProductImage = null;
        clearProductFields();
        changeProductImageButton.setText("Ajouter une image");
        productEditPane.setVisible(true);
    }
    
    @FXML
    public void handleAddIngredient() {
        clearSelection();
        currentIngredient = null;
        currentIngredientImage = null;
        clearIngredientFields();
        changeIngredientImageButton.setText("Ajouter une image");
        ingredientEditPane.setVisible(true);
    }
    
    private void clearProductFields() {
        productNameField.clear();
        productPriceHTField.clear();
        productTVAField.clear();
        productDescriptionArea.clear();
        productCategoryCombo.getSelectionModel().clearSelection();
        productImage.setImage(null);
        productPriceTTCLabel.setText("0.00 €");
    }
    
    private void clearIngredientFields() {
        ingredientNameField.clear();
        ingredientPriceField.clear();
        ingredientCategoryCombo.getSelectionModel().clearSelection();
        ingredientImage.setImage(null);
    }
    
    @FXML
    public void handleChangeProductImage() {
        File file = chooseImageFile();
        if (file != null) {
            try {
                byte[] imageData = Files.readAllBytes(file.toPath());
                currentProductImage = Base64.getEncoder().encodeToString(imageData);
                productImage.setImage(new Image(new ByteArrayInputStream(imageData)));
            } catch (IOException e) {
                e.printStackTrace();
                showError("Erreur lors du chargement de l'image");
            }
        }
    }
    
    @FXML
    public void handleChangeIngredientImage() {
        File file = chooseImageFile();
        if (file != null) {
            try {
                byte[] imageData = Files.readAllBytes(file.toPath());
                currentIngredientImage = Base64.getEncoder().encodeToString(imageData);
                ingredientImage.setImage(new Image(new ByteArrayInputStream(imageData)));
            } catch (IOException e) {
                e.printStackTrace();
                showError("Erreur lors du chargement de l'image");
            }
        }
    }
    
    private File chooseImageFile() {
        FileChooser fileChooser = new FileChooser();
        fileChooser.setTitle("Sélectionner une image");
        fileChooser.getExtensionFilters().addAll(
            new FileChooser.ExtensionFilter("Images", "*.png", "*.jpg", "*.jpeg")
        );
        return fileChooser.showOpenDialog(new Stage());
    }
    
    @FXML
    public void handleSaveProduct() {
        try {
            String name = productNameField.getText();
            double prixHT = Double.parseDouble(productPriceHTField.getText());
            double tva = Double.parseDouble(productTVAField.getText());
            double prixTTC = prixHT * (1 + (tva / 100));
            String category = productCategoryCombo.getValue();
            String description = productDescriptionArea.getText();
            
            if (name.isEmpty() || category == null) {
                showError("Veuillez remplir tous les champs obligatoires");
                return;
            }
            
            if (currentProduct == null) {
                // Insert new product
                String query = "INSERT INTO produits (nom, type_produit, prix_ttc, prix_ht, photo, photo_type, tva, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                PreparedStatement stmt = modele.getConnection().prepareStatement(query);
                stmt.setString(1, name);
                stmt.setString(2, category);
                stmt.setDouble(3, prixTTC);
                stmt.setDouble(4, prixHT);
                stmt.setString(5, currentProductImage);
                stmt.setString(6, "image/jpeg");
                stmt.setDouble(7, tva);
                stmt.setString(8, description);
                stmt.executeUpdate();
            } else {
                // Update existing product
                String query = "UPDATE produits SET nom=?, type_produit=?, prix_ttc=?, prix_ht=?, tva=?, description=?" +
                             (currentProductImage != null ? ", photo=?, photo_type=?" : "") +
                             " WHERE id_produit=?";
                PreparedStatement stmt = modele.getConnection().prepareStatement(query);
                stmt.setString(1, name);
                stmt.setString(2, category);
                stmt.setDouble(3, prixTTC);
                stmt.setDouble(4, prixHT);
                stmt.setDouble(5, tva);
                stmt.setString(6, description);
                if (currentProductImage != null) {
                    stmt.setString(7, currentProductImage);
                    stmt.setString(8, "image/jpeg");
                    stmt.setInt(9, currentProduct.getId_produit());
                } else {
                    stmt.setInt(7, currentProduct.getId_produit());
                }
                stmt.executeUpdate();
            }
            
            refreshData();
            productEditPane.setVisible(false);
            clearSelection();
            
        } catch (NumberFormatException e) {
            showError("Les prix et TVA doivent être des nombres valides");
        } catch (SQLException e) {
            e.printStackTrace();
            showError("Erreur lors de la sauvegarde du produit");
        }
    }
    
    @FXML
    public void handleSaveIngredient() {
        try {
            String name = ingredientNameField.getText();
            double prix = Double.parseDouble(ingredientPriceField.getText());
            String category = ingredientCategoryCombo.getValue();
            
            if (name.isEmpty() || category == null) {
                showError("Veuillez remplir tous les champs obligatoires");
                return;
            }
            
            if (currentIngredient == null) {
                // Insert new ingredient
                String query = "INSERT INTO ingredients (nom, type_ingredient, prix_ht, photo, photo_type) VALUES (?, ?, ?, ?, ?)";
                PreparedStatement stmt = modele.getConnection().prepareStatement(query);
                stmt.setString(1, name);
                stmt.setString(2, category);
                stmt.setDouble(3, prix);
                stmt.setString(4, currentIngredientImage);
                stmt.setString(5, "image/jpeg");
                stmt.executeUpdate();
            } else {
                // Update existing ingredient
                String query = "UPDATE ingredients SET nom=?, type_ingredient=?, prix_ht=?" +
                             (currentIngredientImage != null ? ", photo=?, photo_type=?" : "") +
                             " WHERE id_ingredient=?";
                PreparedStatement stmt = modele.getConnection().prepareStatement(query);
                stmt.setString(1, name);
                stmt.setString(2, category);
                stmt.setDouble(3, prix);
                if (currentIngredientImage != null) {
                    stmt.setString(4, currentIngredientImage);
                    stmt.setString(5, "image/jpeg");
                    stmt.setInt(6, currentIngredient.getId_ingredient());
                } else {
                    stmt.setInt(4, currentIngredient.getId_ingredient());
                }
                stmt.executeUpdate();
            }
            
            refreshData();
            ingredientEditPane.setVisible(false);
            clearSelection();
            
        } catch (NumberFormatException e) {
            showError("Le prix doit être un nombre valide");
        } catch (SQLException e) {
            e.printStackTrace();
            showError("Erreur lors de la sauvegarde de l'ingrédient");
        }
    }
    
    @FXML
    public void handleDeleteProduct() {
        if (currentProduct != null && showConfirmation("Êtes-vous sûr de vouloir supprimer ce produit ?")) {
            try {
                String query = "DELETE FROM produits WHERE id_produit = ?";
                PreparedStatement stmt = modele.getConnection().prepareStatement(query);
                stmt.setInt(1, currentProduct.getId_produit());
                stmt.executeUpdate();
                
                refreshData();
                productEditPane.setVisible(false);
                clearSelection();
            } catch (SQLException e) {
                e.printStackTrace();
                showError("Erreur lors de la suppression du produit");
            }
        }
    }
    
    @FXML
    public void handleDeleteIngredient() {
        if (currentIngredient != null && showConfirmation("Êtes-vous sûr de vouloir supprimer cet ingrédient ?")) {
            try {
                String query = "DELETE FROM ingredients WHERE id_ingredient = ?";
                PreparedStatement stmt = modele.getConnection().prepareStatement(query);
                stmt.setInt(1, currentIngredient.getId_ingredient());
                stmt.executeUpdate();
                
                refreshData();
                ingredientEditPane.setVisible(false);
                clearSelection();
            } catch (SQLException e) {
                e.printStackTrace();
                showError("Erreur lors de la suppression de l'ingrédient");
            }
        }
    }
    
    private void showProduitDetails() {
        try {
            ProduitContainer.getChildren().clear();
            
            String query = "SELECT * FROM produits ORDER BY type_produit, nom";
            PreparedStatement stmt = modele.getConnection().prepareStatement(query);
            ResultSet rs = stmt.executeQuery();
            
            String currentType = null;
            
            while (rs.next()) {
                String typeProduct = rs.getString("type_produit");
                
                if (currentType == null || !currentType.equals(typeProduct)) {
                    currentType = typeProduct;
                    Label typeLabel = new Label(currentType.toUpperCase());
                    typeLabel.getStyleClass().add("preparateur-section-title");
                    typeLabel.setStyle("-fx-padding: 10 0 5 0;");
                    ProduitContainer.getChildren().add(typeLabel);
                }
                
                Produit produit = new Produit(
                    rs.getInt("id_produit"),
                    rs.getString("nom"),
                    rs.getString("type_produit"),
                    rs.getDouble("prix_ttc"),
                    rs.getDouble("prix_ht"),
                    rs.getString("photo"),
                    rs.getString("photo_type"),
                    rs.getDouble("tva"),
                    rs.getString("description")
                );
                
                VBox itemBox = createProductItemBox(produit);
                ProduitContainer.getChildren().add(itemBox);
            }
            
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
    
    private VBox createProductItemBox(Produit produit) {
        VBox itemBox = new VBox(5);
        itemBox.getStyleClass().addAll("preparateur-item", "menu-item");
        itemBox.setOnMouseClicked(e -> {
            clearSelection();
            itemBox.getStyleClass().add("selected");
            lastSelectedProduct = itemBox;
            showProductEditPane(produit);
        });
        
        HBox headerBox = new HBox(10);
        headerBox.setAlignment(Pos.CENTER_LEFT);
        
        if (produit.getPhoto() != null) {
            ImageView productImage = new ImageView(produit.getPhotoAsImage());
            productImage.setFitWidth(80);
            productImage.setFitHeight(80);
            productImage.getStyleClass().add("preparateur-item-image");
            headerBox.getChildren().add(productImage);
        }
        
        VBox productInfo = new VBox(5);
        Label nameLabel = new Label(produit.getNom());
        nameLabel.getStyleClass().add("preparateur-item-title");
        
        Label priceLabel = new Label(String.format("%.2f €", produit.getPrix_ttc()));
        priceLabel.getStyleClass().add("preparateur-order-price");
        
        if (produit.getDescription() != null && !produit.getDescription().isEmpty()) {
            Label descLabel = new Label(produit.getDescription());
            descLabel.getStyleClass().add("preparateur-ingredient");
            productInfo.getChildren().addAll(nameLabel, descLabel, priceLabel);
        } else {
            productInfo.getChildren().addAll(nameLabel, priceLabel);
        }
        
        Region spacer = new Region();
        HBox.setHgrow(spacer, Priority.ALWAYS);
        headerBox.getChildren().addAll(productInfo, spacer);
        
        itemBox.getChildren().add(headerBox);
        return itemBox;
    }
    
    private void showProductEditPane(Produit produit) {
        currentProduct = produit;
        currentProductImage = null;
        
        productNameField.setText(produit.getNom());
        productPriceHTField.setText(String.format("%.2f", produit.getPrix_ht()));
        productTVAField.setText(String.format("%.2f", produit.getTva()));
        productCategoryCombo.setValue(produit.getType_produit());
        productDescriptionArea.setText(produit.getDescription());
        changeProductImageButton.setText("Changer l'image");
        
        if (produit.getPhotoAsImage() != null) {
            productImage.setImage(produit.getPhotoAsImage());
        } else {
            productImage.setImage(null);
        }
        
        calculateAndDisplayTTC();
        productEditPane.setVisible(true);
    }
    
    private void calculateAndDisplayTTC() {
        try {
            String htText = productPriceHTField.getText().trim();
            String tvaText = productTVAField.getText().trim();
            
            if (!htText.isEmpty() && !tvaText.isEmpty()) {
                double ht = Double.parseDouble(htText);
                double tva = Double.parseDouble(tvaText);
                double ttc = ht * (1 + (tva / 100));
                productPriceTTCLabel.setText(String.format("%.2f €", ttc));
            } else {
                productPriceTTCLabel.setText("0.00 €");
            }
        } catch (NumberFormatException e) {
            productPriceTTCLabel.setText("0.00 €");
        }
    }
    
    private void showIngredientDetails() {
        try {
            IngredientContainer.getChildren().clear();
            
            String query = "SELECT * FROM ingredients ORDER BY type_ingredient, nom";
            PreparedStatement stmt = modele.getConnection().prepareStatement(query);
            ResultSet rs = stmt.executeQuery();
            
            String currentType = null;
            
            while (rs.next()) {
                String typeIngredient = rs.getString("type_ingredient");
                
                if (currentType == null || !currentType.equals(typeIngredient)) {
                    currentType = typeIngredient;
                    Label typeLabel = new Label(currentType.toUpperCase());
                    typeLabel.getStyleClass().add("preparateur-section-title");
                    typeLabel.setStyle("-fx-padding: 10 0 5 0;");
                    IngredientContainer.getChildren().add(typeLabel);
                }
                
                Ingredient ingredient = new Ingredient(
                    rs.getInt("id_ingredient"),
                    rs.getString("nom"),
                    rs.getString("photo"),
                    rs.getString("photo_type"),
                    rs.getDouble("prix_ht"),
                    rs.getString("type_ingredient")
                );
                
                VBox itemBox = createIngredientItemBox(ingredient);
                IngredientContainer.getChildren().add(itemBox);
            }
            
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
    
    private VBox createIngredientItemBox(Ingredient ingredient) {
        VBox itemBox = new VBox(5);
        itemBox.getStyleClass().addAll("preparateur-item", "menu-item");
        itemBox.setOnMouseClicked(e -> {
            clearSelection();
            itemBox.getStyleClass().add("selected");
            lastSelectedIngredient = itemBox;
            showIngredientEditPane(ingredient);
        });
        
        HBox headerBox = new HBox(10);
        headerBox.setAlignment(Pos.CENTER_LEFT);
        
        if (ingredient.getPhotoAsImage() != null) {
            ImageView ingredientImage = new ImageView(ingredient.getPhotoAsImage());
            ingredientImage.setFitWidth(80);
            ingredientImage.setFitHeight(80);
            ingredientImage.getStyleClass().add("preparateur-item-image");
            headerBox.getChildren().add(ingredientImage);
        }
        
        VBox ingredientInfo = new VBox(5);
        Label nameLabel = new Label(ingredient.getNom());
        nameLabel.getStyleClass().add("preparateur-item-title");
        
        Label priceLabel = new Label(String.format("%.2f €", ingredient.getPrix_ht()));
        priceLabel.getStyleClass().add("preparateur-order-price");
        
        ingredientInfo.getChildren().addAll(nameLabel, priceLabel);
        
        Region spacer = new Region();
        HBox.setHgrow(spacer, Priority.ALWAYS);
        headerBox.getChildren().addAll(ingredientInfo, spacer);
        
        itemBox.getChildren().add(headerBox);
        return itemBox;
    }
    
    private void showIngredientEditPane(Ingredient ingredient) {
        currentIngredient = ingredient;
        currentIngredientImage = null;
        
        ingredientNameField.setText(ingredient.getNom());
        ingredientPriceField.setText(String.format("%.2f", ingredient.getPrix_ht()));
        ingredientCategoryCombo.setValue(ingredient.getType_ingredient());
        changeIngredientImageButton.setText("Changer l'image");
        
        if (ingredient.getPhotoAsImage() != null) {
            ingredientImage.setImage(ingredient.getPhotoAsImage());
        } else {
            ingredientImage.setImage(null);
        }
        
        ingredientEditPane.setVisible(true);
    }
    
    private void showError(String message) {
        Alert alert = new Alert(Alert.AlertType.ERROR);
        alert.setTitle("Erreur");
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }
    
    private boolean showConfirmation(String message) {
        Alert alert = new Alert(Alert.AlertType.CONFIRMATION);
        alert.setTitle("Confirmation");
        alert.setHeaderText(null);
        alert.setContentText(message);
        return alert.showAndWait().orElse(ButtonType.CANCEL) == ButtonType.OK;
    }
}
