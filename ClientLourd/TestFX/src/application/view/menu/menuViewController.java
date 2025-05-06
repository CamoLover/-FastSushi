package application.view.menu;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

import application.LoginController;
import application.model.Commande;
import application.model.Modele;
import application.model.Produit;
import application.model.Ingredient;
import javafx.fxml.FXML;
import javafx.geometry.Pos;
import javafx.scene.control.Label;
import javafx.scene.control.Tab;
import javafx.scene.control.TabPane;
import javafx.scene.image.ImageView;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

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

    @FXML
    private VBox vboxTab1;
    
    private LoginController loginController;
    private Modele modele;
    
    
    public void initialize() {
    	 try {
             loginController = LoginController.getInstance();
             modele = Modele.getInstance();
             
             Tab selectedTab = tabPane.getSelectionModel().getSelectedItem();
             String tabTitle = selectedTab.getText();
             showProduitDetails(ProduitContainer);
                 
         } catch (SQLException e) {
             e.printStackTrace();
         }
    	
    }
    
    
    private void showProduitDetails(VBox container) {
        try {
            container.getChildren().clear();
            
            // Load order lines with products
            String query = "SELECT * FROM produits";
            PreparedStatement stmt = modele.getConnection().prepareStatement(query);
            ResultSet rs = stmt.executeQuery();
            
            while (rs.next()) {
                VBox itemBox = new VBox(5);
                itemBox.getStyleClass().add("preparateur-item");
                
                HBox headerBox = new HBox(10);
                headerBox.setAlignment(Pos.CENTER_LEFT);
                
                // Create product image view if available
                if (rs.getString("photo") != null) {
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
                    
                    ImageView productImage = new ImageView(produit.getPhotoAsImage());
                    productImage.setFitWidth(60);
                    productImage.setFitHeight(60);
                    productImage.getStyleClass().add("preparateur-item-image");
                    headerBox.getChildren().add(productImage);
                }
                
                VBox productInfo = new VBox(2);
                Label productLabel = new Label(rs.getString("nom"));
                productLabel.getStyleClass().add("preparateur-item-title");

                
                productInfo.getChildren().addAll(productLabel);
                headerBox.getChildren().add(productInfo);
                
                itemBox.getChildren().add(headerBox);
                
                container.getChildren().add(itemBox);
            }
            
        } catch (SQLException e) {
            e.printStackTrace();

        }
    }
    
}
