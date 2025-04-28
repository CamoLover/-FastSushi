package application.view.client;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

import application.model.Modele;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.Node;
import javafx.scene.control.Accordion;
import javafx.scene.control.Button;
import javafx.scene.control.TextField;
import javafx.scene.control.TitledPane;
import javafx.stage.Stage;

public class ficheViewController {

    @FXML
    private TextField email;

    @FXML
    private TextField mdp;

    @FXML
    private TextField nom;

    @FXML
    private TextField prenom;

    @FXML
    private TextField tel;

    @FXML
    private TextField ville;
    
    @FXML
    private Accordion accordeon;

    @FXML
    private TitledPane paneClient;

    @FXML
    private TitledPane paneHisto;
    

    @FXML
    private Button btnCancel;

    @FXML
    private Button btnEnregistrer;

    private int idClient;
    
    @FXML 
    private void initialize() {
    }
    
    @FXML
    private void close(ActionEvent event) {
    	 Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
	     stage.close();
    }

    @FXML
    private void enregistrer(ActionEvent event) {
    	Connection conn;
    	
		try {
			conn = Modele.getInstance().getConnection();

	        String sql = "UPDATE clients SET nom = ?, prenom = ?, email = ?, tel = ?, ville = ? WHERE id_client = ?";
	        PreparedStatement pstmt;
			pstmt = conn.prepareStatement(sql);
		

	        // On "bind" les valeurs dans l'ordre des '?'
	        pstmt.setString(1, nom.getText());      // 1er ?
	        pstmt.setString(2, prenom.getText());   // 2e ?
	        pstmt.setString(3, email.getText());    // 3e ?
	        pstmt.setString(4, tel.getText());      // 4e ?
	        pstmt.setString(5, ville.getText());    // 5e ?
	        pstmt.setInt(6, idClient);              // 6e ? -> idClient pour la condition WHERE
	
	        // Exécuter la requête
	        int rowsUpdated = pstmt.executeUpdate();
	
	        if (rowsUpdated > 0) {
	            System.out.println("Client mis à jour avec succès !");
	        } else {
	            System.out.println("Aucune mise à jour effectuée (id non trouvé).");
	        }
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
    }
    
    public void initData(int idClient) {
    	this.idClient = idClient;
    	//Récupération de la fiche du client
    	Connection conn;
		try {
			conn = Modele.getInstance().getConnection();
	        String sql = "SELECT * FROM clients WHERE id_client="+idClient;
	        Statement stmt = conn.createStatement();
	        ResultSet rs = stmt.executeQuery(sql);
	        rs.next();
	        nom.setText(rs.getString("nom"));
	        prenom.setText(rs.getString("prenom"));
	        email.setText(rs.getString("email"));
	        tel.setText(rs.getString("tel"));
	        ville.setText(rs.getString("ville"));
	        
	        accordeon.setExpandedPane(paneClient);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
    }

    

}
