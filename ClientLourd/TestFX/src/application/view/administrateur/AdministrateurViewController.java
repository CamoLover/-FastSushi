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
import application.headerController;
import application.view.client.listeViewController;
import application.view.admin.AdminViewController;


public class AdministrateurViewController {

    
    @FXML
    public void initialize() {
    	headerController.getInstance().updateButtonState(true);
    }

    @FXML
    private void handleListeClients(ActionEvent event) {   
        try {	
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/application/view/client/liste.fxml"));
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
    private void handleListeEmployes(ActionEvent event) {   
        try {	
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/application/view/admin/admin.fxml"));
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