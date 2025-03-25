package application;
	
import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.stage.Stage;
import javafx.scene.Scene;
import javafx.scene.Parent;

public class Main extends Application {
	@Override
	public void start(Stage stage) throws Exception{
		Parent root = FXMLLoader.load(getClass().getResource("/application/view/login/login.fxml"));
		Scene scene = new Scene(root);
		stage.setTitle("FastSushi - Connexion");
		stage.setScene(scene);
		stage.show();
	}
	
	@Override
	public void stop() {
		// Fermer la connexion à la base de données lors de la fermeture de l'application
		LoginController.getInstance().closeConnection();
	}
	
	public static void main(String[] args) {
		launch(args);
	}
}
