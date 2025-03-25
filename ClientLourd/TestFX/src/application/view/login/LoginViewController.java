package application.view.login;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Alert;
import javafx.scene.control.Alert.AlertType;
import javafx.scene.control.PasswordField;
import javafx.scene.control.TextField;
import javafx.stage.Stage;
import javafx.scene.Node;
import javafx.event.ActionEvent;
import java.io.IOException;
import application.LoginController;

public class LoginViewController {
    @FXML
    private TextField emailField;
    
    @FXML
    private PasswordField passwordField;
    
    private LoginController loginController;
    
    @FXML
    public void initialize() {
        loginController = LoginController.getInstance();
    }
    
    @FXML
    private void handleLoginButton(ActionEvent event) {
        String email = emailField.getText();
        String password = passwordField.getText();
        
        if (loginController.login(email, password)) {
            try {
                String redirectPage = loginController.getRedirectPage();
                FXMLLoader loader = new FXMLLoader(getClass().getResource("/application/view/" + redirectPage));
                Parent root = loader.load();
                
                Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
                Scene scene = new Scene(root);
                stage.setScene(scene);
                stage.show();
                
            } catch (IOException e) {
                e.printStackTrace();
                showError("Erreur", "Impossible de charger la page suivante.");
            }
        } else {
            showError("Erreur de connexion", "Email ou mot de passe incorrect.");
        }
    }
    
    private void showError(String title, String content) {
        Alert alert = new Alert(AlertType.ERROR);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(content);
        alert.showAndWait();
    }
} 