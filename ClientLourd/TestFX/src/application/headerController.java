package application;

import java.io.IOException;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.stage.Stage;

public class headerController {

    @FXML
    private Button btnRetour;
    private static headerController instance;
    
    @FXML
    public void initialize() {
    	headerController.instance = this;
    }
    
    public void updateButtonState(boolean isAdminScreen) {
        if (isAdminScreen) {
            btnRetour.setDisable(true);  // Griser le bouton si on est dans l'écran administrateur
        } else {
            btnRetour.setDisable(false);  // Sinon, activer le bouton
        }
    }
    
    public static headerController getInstance() {
    	if(instance == null) {
    		instance = new headerController();
 		
    	}
    	return instance;
    }
    
    @FXML
    void handleLogout(ActionEvent event) {
    		LoginController.getInstance().logout();
        
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
    void handleRetour(ActionEvent event) {
    	FXMLLoader loader = new FXMLLoader(getClass().getResource("/application/view/administrateur/administrateur.fxml"));
        Parent root;
		try {
			root = loader.load();
	        
	        Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
	        Scene scene = new Scene(root);
	        stage.setScene(scene);
	        stage.show();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
    }

}
