package application.view.preparateur;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;
import javafx.scene.Node;
import javafx.scene.control.*;
import javafx.scene.layout.*;
import javafx.scene.image.ImageView;
import javafx.scene.shape.Circle;
import javafx.event.ActionEvent;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.geometry.Pos;
import java.io.IOException;
import java.sql.*;
import java.util.*;
import application.LoginController;
import application.model.*;

public class PreparateurViewController {
    
    @FXML private ListView<Commande> todoOrdersList;
    @FXML private ListView<Commande> currentOrdersList;
    @FXML private ListView<Commande> readyOrdersList;
    @FXML private ListView<Commande> deliveredOrdersList;
    
    @FXML private VBox todoOrderItemsContainer;
    @FXML private VBox currentOrderItemsContainer;
    @FXML private VBox readyOrderItemsContainer;
    @FXML private VBox deliveredOrderItemsContainer;
    
    @FXML private Button markInProgressButton;
    @FXML private Button markReadyButton;
    @FXML private Button markDeliveredButton;
    
    @FXML private Circle statusIndicator;
    @FXML private Label todoOrderStatus;
    @FXML private Label currentOrderStatus;
    @FXML private Label readyOrderStatus;
    @FXML private Label deliveredOrderStatus;
    
    private LoginController loginController;
    private Modele modele;
    
    private ObservableList<Commande> todoOrders = FXCollections.observableArrayList();
    private ObservableList<Commande> currentOrders = FXCollections.observableArrayList();
    private ObservableList<Commande> readyOrders = FXCollections.observableArrayList();
    private ObservableList<Commande> deliveredOrders = FXCollections.observableArrayList();
    
    @FXML
    public void initialize() {
        try {
            loginController = LoginController.getInstance();
            modele = Modele.getInstance();
            
            setupListViews();
            loadOrders();
            
            // Setup selection listeners
            todoOrdersList.getSelectionModel().selectedItemProperty().addListener(
                (observable, oldValue, newValue) -> {
                    showOrderDetails(newValue, todoOrderItemsContainer);
                    if (newValue != null) {
                        todoOrderStatus.setText("En attente");
                    }
                });
            
            currentOrdersList.getSelectionModel().selectedItemProperty().addListener(
                (observable, oldValue, newValue) -> {
                    showOrderDetails(newValue, currentOrderItemsContainer);
                    if (newValue != null) {
                        currentOrderStatus.setText("En cours");
                    }
                });
            
            readyOrdersList.getSelectionModel().selectedItemProperty().addListener(
                (observable, oldValue, newValue) -> {
                    showOrderDetails(newValue, readyOrderItemsContainer);
                    if (newValue != null) {
                        readyOrderStatus.setText("Prête");
                    }
                });
            
            deliveredOrdersList.getSelectionModel().selectedItemProperty().addListener(
                (observable, oldValue, newValue) -> {
                    showOrderDetails(newValue, deliveredOrderItemsContainer);
                    if (newValue != null) {
                        deliveredOrderStatus.setText("Livrée");
                    }
                });
                
        } catch (SQLException e) {
            e.printStackTrace();
            showError("Erreur de connexion à la base de données");
        }
    }
    
    private void setupListViews() {
        todoOrdersList.setItems(todoOrders);
        currentOrdersList.setItems(currentOrders);
        readyOrdersList.setItems(readyOrders);
        deliveredOrdersList.setItems(deliveredOrders);
        
        // Custom cell factory to display order information
        setCellFactory(todoOrdersList);
        setCellFactory(currentOrdersList);
        setCellFactory(readyOrdersList);
        setCellFactory(deliveredOrdersList);
    }
    
    private void setCellFactory(ListView<Commande> listView) {
        listView.setCellFactory(lv -> new ListCell<Commande>() {
            @Override
            protected void updateItem(Commande commande, boolean empty) {
                super.updateItem(commande, empty);
                if (empty || commande == null) {
                    setText(null);
                    setGraphic(null);
                } else {
                    HBox container = new HBox(10);
                    container.setAlignment(Pos.CENTER_LEFT);
                    
                    Circle statusDot = new Circle(5);
                    statusDot.getStyleClass().add("preparateur-status-dot");
                    
                    VBox textContainer = new VBox(2);
                    Label orderLabel = new Label(String.format("Commande #%d", commande.getId_commande()));
                    orderLabel.getStyleClass().add("preparateur-order-number");
                    
                    Label dateLabel = new Label(commande.getDate_panier());
                    dateLabel.getStyleClass().add("preparateur-order-date");
                    
                    Label priceLabel = new Label(commande.getMontant_tot() + "€");
                    priceLabel.getStyleClass().add("preparateur-order-price");
                    
                    textContainer.getChildren().addAll(orderLabel, dateLabel, priceLabel);
                    container.getChildren().addAll(statusDot, textContainer);
                    
                    setGraphic(container);
                    setText(null);
                }
            }
        });
    }
    
    private void loadOrders() {
        try {
            todoOrders.clear();
            currentOrders.clear();
            readyOrders.clear();
            deliveredOrders.clear();
            
            // First, let's count all orders in the database
            String countQuery = "SELECT COUNT(*) as total FROM commandes";
            PreparedStatement countStmt = modele.getConnection().prepareStatement(countQuery);
            ResultSet countRs = countStmt.executeQuery();
            int totalInDb = 0;
            if (countRs.next()) {
                totalInDb = countRs.getInt("total");
            }
            System.out.println("Total orders in database: " + totalInDb);
            
            // Now let's get all orders and print each one
            String query = "SELECT * FROM commandes";
            PreparedStatement stmt = modele.getConnection().prepareStatement(query);
            ResultSet rs = stmt.executeQuery();
            
            int totalOrders = 0;
            int todoCount = 0;
            int currentCount = 0;
            int readyCount = 0;
            int deliveredCount = 0;
            
            // Print column names
            ResultSetMetaData metaData = rs.getMetaData();
            int columnCount = metaData.getColumnCount();
            System.out.println("\nColumns in result set:");
            for (int i = 1; i <= columnCount; i++) {
                System.out.print(metaData.getColumnName(i) + " | ");
            }
            System.out.println("\n");
            
            while (rs.next()) {
                totalOrders++;
                int orderId = rs.getInt("id_commande");
                String status = rs.getString("statut");
                
                // Print all columns for this order
                System.out.print("Order #" + orderId + " - Row data: ");
                for (int i = 1; i <= columnCount; i++) {
                    System.out.print(rs.getString(i) + " | ");
                }
                System.out.println();
                
                Commande commande = new Commande(
                    orderId,
                    rs.getInt("id_client"),
                    rs.getString("date_panier"),
                    rs.getString("montant_tot"),
                    status
                );
                
                if (status == null) {
                    System.out.println("Warning: Order #" + orderId + " has null status");
                    todoOrders.add(commande);
                    todoCount++;
                    continue;
                }
                
                status = status.toLowerCase().trim();
                System.out.println("Processing Order #" + orderId + " Status: '" + status + "'");
                
                switch (status) {
	                case "a faire":
	                case "en attente":
                    case "à faire":
                        todoOrders.add(commande);
                        todoCount++;
                        break;
                    case "en cours":
                        currentOrders.add(commande);
                        currentCount++;
                        break;
                    case "pret":
                    case "prêt":
                    case "prete":
                    case "prête":
                        readyOrders.add(commande);
                        readyCount++;
                        break;
                    case "livre":
                    case "livré":
                    case "livree":
                    case "livrée":
                        deliveredOrders.add(commande);
                        deliveredCount++;
                        break;
                    default:
                        System.out.println("Unhandled status: '" + status + "' for order #" + orderId);
                        todoOrders.add(commande);
                        todoCount++;
                        break;
                }
            }
            
            System.out.println("\nSummary:");
            System.out.println("Total orders in database: " + totalInDb);
            System.out.println("Total orders processed: " + totalOrders);
            System.out.println("Orders by status:");
            System.out.println("- Todo: " + todoCount + " (size: " + todoOrders.size() + ")");
            System.out.println("- Current: " + currentCount + " (size: " + currentOrders.size() + ")");
            System.out.println("- Ready: " + readyCount + " (size: " + readyOrders.size() + ")");
            System.out.println("- Delivered: " + deliveredCount + " (size: " + deliveredOrders.size() + ")");
            
            // Print the actual orders in each list
            System.out.println("\nTodo orders: " + orderListToString(todoOrders));
            System.out.println("Current orders: " + orderListToString(currentOrders));
            System.out.println("Ready orders: " + orderListToString(readyOrders));
            System.out.println("Delivered orders: " + orderListToString(deliveredOrders));
            
        } catch (SQLException e) {
            e.printStackTrace();
            showError("Erreur lors du chargement des commandes");
        }
    }
    
    private String orderListToString(ObservableList<Commande> orders) {
        StringBuilder sb = new StringBuilder("[");
        for (Commande c : orders) {
            sb.append("#").append(c.getId_commande()).append(", ");
        }
        if (sb.length() > 1) {
            sb.setLength(sb.length() - 2); // Remove last ", "
        }
        sb.append("]");
        return sb.toString();
    }
    
    @FXML
    private void handleMarkInProgress(ActionEvent event) {
        Commande selectedOrder = todoOrdersList.getSelectionModel().getSelectedItem();
        if (selectedOrder != null) {
            updateOrderStatus(selectedOrder, "en cours");
            loadOrders();
        }
    }
    
    @FXML
    private void handleMarkReady(ActionEvent event) {
        Commande selectedOrder = currentOrdersList.getSelectionModel().getSelectedItem();
        if (selectedOrder != null) {
            updateOrderStatus(selectedOrder, "pret");
            loadOrders();
        }
    }
    
    @FXML
    private void handleMarkDelivered(ActionEvent event) {
        Commande selectedOrder = readyOrdersList.getSelectionModel().getSelectedItem();
        if (selectedOrder != null) {
            updateOrderStatus(selectedOrder, "livre");
            loadOrders();
        }
    }
    
    private void updateOrderStatus(Commande commande, String newStatus) {
        try {
            String query = "UPDATE commandes SET statut = ? WHERE id_commande = ?";
            PreparedStatement stmt = modele.getConnection().prepareStatement(query);
            stmt.setString(1, newStatus);
            stmt.setInt(2, commande.getId_commande());
            stmt.executeUpdate();
            
            System.out.println("Updated order #" + commande.getId_commande() + " status to: " + newStatus);
            
        } catch (SQLException e) {
            e.printStackTrace();
            showError("Erreur lors de la mise à jour du statut de la commande");
        }
    }
    
    private void showOrderDetails(Commande commande, VBox container) {
        if (commande == null) {
            container.getChildren().clear();
            return;
        }
        
        try {
            container.getChildren().clear();
            
            // Load order lines with products
            String query = "SELECT cl.*, p.* FROM commande_lignes cl " +
                         "JOIN produits p ON cl.id_produit = p.id_produit " +
                         "WHERE cl.id_commande = ?";
            PreparedStatement stmt = modele.getConnection().prepareStatement(query);
            stmt.setInt(1, commande.getId_commande());
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
                
                Label quantityLabel = new Label("Quantité: " + rs.getInt("quantite"));
                quantityLabel.getStyleClass().add("preparateur-item-quantity");
                
                productInfo.getChildren().addAll(productLabel, quantityLabel);
                headerBox.getChildren().add(productInfo);
                
                itemBox.getChildren().add(headerBox);
                
                // Load ingredients for this order line
                loadIngredients(itemBox, rs.getInt("id_commande_ligne"));
                
                container.getChildren().add(itemBox);
            }
            
        } catch (SQLException e) {
            e.printStackTrace();
            showError("Erreur lors du chargement des détails de la commande");
        }
    }
    
    private void loadIngredients(VBox itemBox, int commandeLigneId) throws SQLException {
        String query = "SELECT i.nom, cc.prix FROM compo_commandes cc " +
                      "JOIN ingredients i ON cc.id_ingredient = i.id_ingredient " +
                      "WHERE cc.id_commande_ligne = ?";
        PreparedStatement stmt = modele.getConnection().prepareStatement(query);
        stmt.setInt(1, commandeLigneId);
        ResultSet rs = stmt.executeQuery();
        
        VBox ingredientsBox = new VBox(2);
        ingredientsBox.getStyleClass().add("preparateur-ingredients");
        
        while (rs.next()) {
            Label ingredientLabel = new Label("• " + rs.getString("nom"));
            ingredientLabel.getStyleClass().add("preparateur-ingredient");
            ingredientsBox.getChildren().add(ingredientLabel);
        }
        
        if (!ingredientsBox.getChildren().isEmpty()) {
            itemBox.getChildren().add(ingredientsBox);
        }
    }
    
    private void showError(String message) {
        Alert alert = new Alert(Alert.AlertType.ERROR);
        alert.setTitle("Erreur");
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }
    
    @FXML
    private void handleLogoutButton(ActionEvent event) {
        loginController.logout();
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/application/view/login/login.fxml"));
            Parent root = loader.load();
            Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
            Scene scene = new Scene(root);
            stage.setScene(scene);
            stage.show();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
} 