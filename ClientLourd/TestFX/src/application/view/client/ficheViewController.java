package application.view.client;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

import javafx.beans.value.ChangeListener;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.control.Accordion;
import javafx.scene.control.Button;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TextField;
import javafx.scene.control.TitledPane;
import javafx.scene.control.cell.PropertyValueFactory;
import application.model.Modele;
import application.model.Client;
import application.model.Commande;
import application.model.Commande_ligne;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.Node;
import javafx.stage.Stage;

public class ficheViewController {
	
	  @FXML
	    private Accordion accordeon;

	    @FXML
	    private Button cancel;

	    @FXML
	    private TableColumn<?, ?> date;

	    @FXML
	    private TextField email;

	    @FXML
	    private Button enregistrer;

	    @FXML
	    private TextField mdp;

	    @FXML
	    private TextField nom;

	    @FXML
	    private TableColumn<?, ?> numcomm;

	    @FXML
	    private TitledPane paneClient;

	    @FXML
	    private TitledPane paneHisto;

	    @FXML
	    private TextField prenom;

	    @FXML
	    private TableColumn<?, ?> prixunit;

	    @FXML
	    private TableColumn<?, ?> produit;

	    @FXML
	    private TableColumn<?, ?> quantité;

	    @FXML
	    private TableColumn<?, ?> statut;

	    @FXML
	    private TextField tel;

	    @FXML
	    private TableColumn<?, ?> total;

	    @FXML
	    private TableColumn<?, ?> totaldetail;

	    @FXML
	    private TextField ville;


    

    @FXML
    private Button btnCancel;

    @FXML
    private Button btnEnregistrer;

    private int idClient;
    
    @FXML 
    private ChangeListener<Commande> tableListener;
    @FXML 
    private TableView<Commande> tableCommandes;
    @FXML 
    private ObservableList<Commande> commandesList = FXCollections.observableArrayList();	
    @FXML 
    private TableView<Commande_ligne> tableDetail;
    @FXML 
    private ObservableList<Commande_ligne> commandes_ligneList = FXCollections.observableArrayList();
    
    @FXML 
    private void initialize() {
        tableListener = ((obs, oldSelection, newSelection) -> {
            if (newSelection != null) {
                int idCommande = newSelection.getId_commande();

                Connection conn;
                try {
                    conn = Modele.getInstance().getConnection();
                    String sql = "SELECT * FROM commande_lignes WHERE id_commande = ?";
                    PreparedStatement stmt = conn.prepareStatement(sql);
                    stmt.setInt(1, idCommande);
                    ResultSet rs = stmt.executeQuery();

                    // Vider l'ancienne liste
                    commandes_ligneList.clear();

                    while (rs.next()) {
                        commandes_ligneList.add(new Commande_ligne(
                            rs.getInt("id_commande_ligne"),
                            rs.getInt("id_commande"),
                            rs.getInt("id_produit"),
                            rs.getInt("quantite"),
                            rs.getString("nom"),
                            rs.getDouble("prix_ht"),
                            rs.getDouble("prix_ttc")
                        ));
                    }

                } catch (SQLException e) {
                    e.printStackTrace();
                }

                // Mise à jour des colonnes
                produit.setCellValueFactory(new PropertyValueFactory<>("nom"));
                quantité.setCellValueFactory(new PropertyValueFactory<>("quantite"));
                prixunit.setCellValueFactory(new PropertyValueFactory<>("prix_ttc"));
                totaldetail.setCellValueFactory(new PropertyValueFactory<>("totalDetail"));

                tableDetail.setItems(commandes_ligneList);
            }
        });

        tableCommandes.getSelectionModel().selectedItemProperty().addListener(tableListener);
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
    
    @FXML
   
    
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
	        
	        
	        sql = "SELECT * FROM commandes WHERE id_client = " + idClient;
	        rs = stmt.executeQuery(sql);
	        // Parcours des enregistrements pour créer la liste des commandes
            while (rs.next()) {
                commandesList.add(new Commande(
                        rs.getInt("id_commande"),
                        rs.getInt("id_client"),
                        rs.getString("date_panier"),
                        rs.getString("montant_tot"),
                        rs.getString("statut")
                ));
             }
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		// Affectation des types de cellules dans les colonnes du tableau
		numcomm.setCellValueFactory(new PropertyValueFactory<>("id_commande"));
        date.setCellValueFactory(new PropertyValueFactory<>("date_panier"));
        total.setCellValueFactory(new PropertyValueFactory<>("montant_tot"));
        statut.setCellValueFactory(new PropertyValueFactory<>("statut"));

        // Détermine quel Observable le TableView commandes doit utiliser pour s'afficher
        tableCommandes.setItems(commandesList);
    }

    
   
    
}
