package application.view.liste;


import java.io.IOException;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

import application.model.Client;
import application.model.Modele;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.stage.Stage;

public class listeViewController {

    @FXML
    private TableColumn<Client, String> colEmail;

    @FXML
    private TableColumn<Client, Integer> colId;

    @FXML
    private TableColumn<Client, String> colNom;

    @FXML
    private TableColumn<Client, String> colPrenom;

    @FXML
    private TableColumn<Client, String> colTel;

    @FXML
    private TableColumn<Client, String> colVille;

    @FXML
    private TableView<Client> tableClients;
    
    private ObservableList<Client> clientsList = FXCollections.observableArrayList();
    
    @FXML
    public void initialize() {
		try {
	        Connection conn = Modele.getInstance().getConnection();
	        
	        String sql = "SELECT * FROM clients";
	        Statement stmt = conn.createStatement();
	        ResultSet rs = stmt.executeQuery(sql);
	
	        while (rs.next()) {
	        	clientsList.add(new Client(
	                    rs.getInt("id_client"),
	                    rs.getString("nom"),
	                    rs.getString("prenom"),
	                    rs.getString("email"),
	                    rs.getString("tel"),
	                    rs.getString("ville"),
	                    rs.getString("mdp")
	            ));
	        }
	
	        colId.setCellValueFactory(new PropertyValueFactory<>("id"));
	        colNom.setCellValueFactory(new PropertyValueFactory<>("nom"));
	        colPrenom.setCellValueFactory(new PropertyValueFactory<>("prenom"));
	        colEmail.setCellValueFactory(new PropertyValueFactory<>("email"));
	        colTel.setCellValueFactory(new PropertyValueFactory<>("tel"));
	        colVille.setCellValueFactory(new PropertyValueFactory<>("ville"));
	
	        tableClients.setItems(clientsList);
	
	    } catch (SQLException e) {
	        e.printStackTrace();
	    }
    }
    
    @FXML
    private void handleRetour(ActionEvent event) {   
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