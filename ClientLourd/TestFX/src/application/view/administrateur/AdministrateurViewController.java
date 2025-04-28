package application.view.administrateur;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;
import javafx.scene.Node;
import javafx.event.ActionEvent;
import java.io.IOException;
import application.LoginController;
import application.view.liste.listeViewController;

public class AdministrateurViewController {
    
    private LoginController loginController;
    private listeViewController listeController;
    
    @FXML
    public void initialize() {
        loginController = LoginController.getInstance();
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
    
    @FXML
    private void handleListeClients(ActionEvent event) {   
        try {	
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/application/view/liste/liste.fxml"));
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