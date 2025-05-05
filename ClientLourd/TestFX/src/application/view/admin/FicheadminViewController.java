package application.view.admin;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.Optional;

import application.BCryptUtil;
import application.model.Commande;
import application.model.Commande_ligne;
import application.model.Modele;
import at.favre.lib.crypto.bcrypt.BCrypt;
import javafx.collections.FXCollections;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.Node;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.ButtonType;
import javafx.scene.control.ComboBox;
import javafx.scene.control.TextField;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.stage.Stage;

public class FicheadminViewController {

    @FXML
    private Button cancel;

    @FXML
    private TextField email;

    @FXML
    private Button enregistrer;

    @FXML
    private TextField mdp;

    @FXML
    private TextField nom;

    @FXML
    private TextField prenom;

    @FXML
    private ComboBox<String> statut_emp;
    
    private int idEmploye;
    private boolean valid = false;
    public boolean isValid() {
        return valid;
    }
    @FXML 
    private void initialize() {
    	System.out.println("FicheadminViewController est initialisé.");
    	statut_emp.setItems(FXCollections.observableArrayList("Administrateur", "Preparateur"));
    }
    
    @FXML
    private void close(ActionEvent event) {
    	 Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
	     stage.close();
    }

    private boolean confirmation(String titre, String message) {
    	Alert alert = new Alert(Alert.AlertType.CONFIRMATION);
    	alert.setTitle(titre);
    	alert.setHeaderText(null);
    	alert.setContentText(message);
    	ButtonType yes = new ButtonType("Oui");
    	ButtonType cancel = new ButtonType("Annuler");
    	
    	alert.getButtonTypes().setAll(yes, cancel);
    	Optional<ButtonType> result = alert.showAndWait();
    	return result.isPresent() && result.get() == yes;
    }
    
    @FXML
    private void enregistrer(ActionEvent event) throws Exception {
    	Connection conn;
    	
		try {
			conn = Modele.getInstance().getConnection();
			String mdpString = "";
			if(!mdp.getText().isEmpty()) {
				mdpString = ", mdp = ?";
			}
	        String sql = "UPDATE employes SET nom = ?, prenom = ?, email = ?, statut_emp = ? "+mdpString+" WHERE id_employe = ?";
	        PreparedStatement pstmt;
			pstmt = conn.prepareStatement(sql);
		

	        // On "bind" les valeurs dans l'ordre des '?'
	        pstmt.setString(1, nom.getText());      // 1er ?
	        pstmt.setString(2, prenom.getText());   // 2e ?
	        pstmt.setString(3, email.getText());    // 3e ?
	        pstmt.setString(4, statut_emp.getSelectionModel().getSelectedItem());      // 4e ?
	        if(!mdp.getText().isEmpty()) {
	        	pstmt.setString(5, BCrypt.withDefaults().hashToString(12, mdp.getText().toCharArray()));
	        	pstmt.setInt(6, idEmploye);
	        } else {
	        	pstmt.setInt(5, idEmploye);              // 5e ? -> idEmploye pour la condition WHERE
	        }
	
	        // Exécuter la requête
	        int rowsUpdated = pstmt.executeUpdate();
	
	        if (rowsUpdated > 0) {
	            System.out.println("Employe mis à jour avec succès !");
	        } else {
	            System.out.println("Aucune mise à jour effectuée (id non trouvé).");
	        }
	        valid = true;
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
    }
    
    @FXML
   
    
    public void initData(int idEmploye) {
    	this.idEmploye = idEmploye;
    	//Récupération de la fiche de l'employe
    	Connection conn;
		try {
			conn = Modele.getInstance().getConnection();
	        String sql = "SELECT * FROM employes WHERE id_employe="+idEmploye;
	        Statement stmt = conn.createStatement();
	        ResultSet rs = stmt.executeQuery(sql);
	        rs.next();
	        nom.setText(rs.getString("nom"));
	        prenom.setText(rs.getString("prenom"));
	        email.setText(rs.getString("email"));
	        statut_emp.getSelectionModel().select(rs.getString("statut_emp"));
	        
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
    }
    
    @FXML
    private void supprimer(ActionEvent event) {
 
    	if(!confirmation("Supprimer ?", "Confirmez-vous la suppression ?")) return;
		try {
    		Connection conn = Modele.getInstance().getConnection();
    		Statement stmt = conn.createStatement();
    		
    		String sql = "UPDATE employes SET statut_emp = 'Inactif' WHERE id_employe = " + idEmploye;
	        stmt.execute(sql);  
    	} catch (SQLException e) {
	        e.printStackTrace();
	    }
		
		Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
	     stage.close();
    }
}
