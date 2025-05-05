package application.view.admin;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Tab;
import javafx.scene.control.TabPane;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TextField;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.input.KeyEvent;
import javafx.stage.Modality;
import javafx.stage.Stage;
import javafx.util.Duration;

import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

import application.model.Client;
import application.model.Commande;
import application.model.Commande_ligne;
import application.model.Employe;
import application.model.Modele;
import application.view.admin.FicheadminViewController;
import javafx.animation.PauseTransition;
import javafx.beans.property.SimpleIntegerProperty;
import javafx.beans.property.SimpleStringProperty;
import javafx.beans.value.ChangeListener;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;


public class AdminViewController {
	
	 @FXML
	    private TextField searchBar;

	    @FXML
	    private TableView<Employe> tableAdmin;
	    
	    @FXML 
	    private ObservableList<Employe> adminList = FXCollections.observableArrayList();	
	    @FXML 
	    private ObservableList<Employe> adminListFilter = FXCollections.observableArrayList();	

	    @FXML
	    private TableColumn<Employe, Number> colIdadmin;

	    @FXML
	    private TableColumn<Employe, String> colNomadmin;

	    @FXML
	    private TableColumn<Employe, String> colPrenomadmin;

	    @FXML
	    private TableColumn<Employe, String> colEmailadmin;

	    @FXML
	    private TableView<Employe> tablePrep;
	    
	    @FXML 
	    private ObservableList<Employe> prepList = FXCollections.observableArrayList();
	    @FXML 
	    private ObservableList<Employe> prepListFilter = FXCollections.observableArrayList();	

	    @FXML
	    private TableColumn<Employe, Number> colIdprep;

	    @FXML
	    private TableColumn<Employe, String> colNomprep;

	    @FXML
	    private TableColumn<Employe, String> colPrenomprep;

	    @FXML
	    private TableColumn<Employe, String> colEmailprep;
	    
	    @FXML
	    private TabPane tabPane;

	    @FXML
	    private ChangeListener<Employe> tableListener;
	    
	    private boolean isRefreshing = false;
	    
	    private boolean openPopup(int idEmploye) {
	    	if (isRefreshing) return false; 
        	if (idEmploye > 0) {
        		Tab selectedTab = tabPane.getSelectionModel().getSelectedItem();
        		String tabTitle = selectedTab.getText();
        		System.out.println("click "+tabTitle);
        		//Desactive l'event
        		if (tabTitle.equals("Administrateurs")) {
        			tableAdmin.getSelectionModel().selectedItemProperty().removeListener(tableListener);
        		} else if (tabTitle.equals("Préparateurs")) {
        		    tablePrep.getSelectionModel().selectedItemProperty().removeListener(tableListener);
        		}
        		
                //int idEmploye = newSelection.getId(); // si ton modèle Client a un getId()

	        	//Ouvrir la fiche du client en popup
	        	FXMLLoader loader = new FXMLLoader(getClass().getResource("/application/view/admin/ficheadmin.fxml"));
	            Parent root;
				try {

					root = loader.load();
		            // Récupérer le contrôleur associé
		            FicheadminViewController popupController = loader.getController();
	
		            // Passer le paramètre
		            popupController.initData(idEmploye);
	
		            // Créer une nouvelle fenêtre (Stage)
		            Stage stage = new Stage();
		            stage.setTitle("Fiche Employe");
		            stage.initModality(Modality.APPLICATION_MODAL);  // Bloque l'accès aux autres fenêtres tant que celle-ci est ouverte
		            stage.setScene(new Scene(root));
		            stage.showAndWait();
		            boolean estValide = popupController.isValid();
		            
		            System.out.println("Close");
		            //On rafraichis les donnéees si elles ont été modifié
		            isRefreshing = true;
		            
		            //CORRIGE le probleme de double lancement de l'event
		            PauseTransition pause = new PauseTransition(Duration.millis(100)); // 100 ms de pause
		            pause.setOnFinished(event -> {isRefreshing = false; refreshData();});
		            pause.play();
		            //Reactive l'event
		            
		            if (tabTitle.equals("Administrateurs")) {
		            	tableAdmin.getSelectionModel().selectedItemProperty().addListener(tableListener);
	        		} else if (tabTitle.equals("Préparateurs")) {
	        			tablePrep.getSelectionModel().selectedItemProperty().addListener(tableListener);
	        		}
		            return estValide;
		            
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
					return false;
				}
        	} else {
        		return false;
        	}
	    }
	    @FXML
	    private void initialize() {
	    	refreshData();
	        
	     // Affectation des types de cellules dans les colonnes du tableau
 			colIdadmin.setCellValueFactory(new PropertyValueFactory<>("id"));
 			colNomadmin.setCellValueFactory(new PropertyValueFactory<>("nom"));
 			colPrenomadmin.setCellValueFactory(new PropertyValueFactory<>("prenom"));
 			colEmailadmin.setCellValueFactory(new PropertyValueFactory<>("email"));
 			
 			
 			colIdprep.setCellValueFactory(new PropertyValueFactory<>("id"));
 			colNomprep.setCellValueFactory(new PropertyValueFactory<>("nom"));
 			colPrenomprep.setCellValueFactory(new PropertyValueFactory<>("prenom"));
 			colEmailprep.setCellValueFactory(new PropertyValueFactory<>("email"));


 		// Détermine quel Observable le TableView commandes doit utiliser pour s'afficher
	        tableAdmin.setItems(adminListFilter);
	        tablePrep.setItems(prepListFilter);

	        tableListener = ((obs, oldSelection, newSelection) -> {
        		int idEmploye = (newSelection != null ? newSelection.getId() : -1);
        		openPopup(idEmploye);
	        });
	        Tab selectedTab = tabPane.getSelectionModel().getSelectedItem();
    		String tabTitle = selectedTab.getText();
    		tableAdmin.getSelectionModel().selectedItemProperty().addListener(tableListener);
    		tablePrep.getSelectionModel().selectedItemProperty().addListener(tableListener);
	    }
	    
	    @FXML
	    private void close(ActionEvent event) {
	    	 Stage stage = (Stage) ((Node) event.getSource()).getScene().getWindow();
		     stage.close();
	    }

	    @FXML
	    private void enregistrer(ActionEvent event) {

				
	    }
	    public void refreshData() {	
	    	try {
	    		adminList.clear();
	    		prepList.clear();
	    		Connection conn = Modele.getInstance().getConnection();
		        String sql = "SELECT * FROM employes  WHERE statut_emp='Administrateur' ";
		        Statement stmt = conn.createStatement();
		        ResultSet rs = stmt.executeQuery(sql);
		
		        /*
		         * Parcours des enregistrement pour créer la liste des clients
		         */
		        while (rs.next()) {
		        	adminList.add(new Employe(
	                        rs.getInt("id_employe"),
	                        rs.getString("nom"),
	                        rs.getString("prenom"),
	                        rs.getString("email"),
	                        rs.getString("statut_emp")
	                ));
	             }
		        
		        
		        sql = "SELECT * FROM employes WHERE statut_emp='Preparateur' ";
		        rs = stmt.executeQuery(sql);
		        
		        while (rs.next()) {
		        	prepList.add(new Employe(
	                        rs.getInt("id_employe"),
	                        rs.getString("nom"),
	                        rs.getString("prenom"),
	                        rs.getString("email"),
	                        rs.getString("statut_emp")
	                ));
	             }
	    	} catch (SQLException e) {
		        e.printStackTrace();
		    }

	        updateFilter();
	    }
	    
	    @FXML
	    public void handleTextChange(KeyEvent event) {
	        updateFilter();
	    }
	    
	    /*
	     * Mise à jour du filtre pour la barre de recherche
	     */
	    public void updateFilter() {
	    	adminListFilter.clear();
	    	prepListFilter.clear();
	    	String filterString = searchBar.getText().toLowerCase();
	    	for(Employe e : adminList) {
	    		if(		filterString == null  
					||  filterString.isEmpty()  
					||  e.getNom().indexOf(filterString) != -1 
					||  e.getPrenom().indexOf(filterString) != -1 
					||  e.getEmail().indexOf(filterString) != -1 
					||  e.getStatut().indexOf(filterString) != -1
				) {
	    			adminListFilter.add(e);
	    			
	    		}
	    	}
	    	for(Employe e : prepList) {
	    		if(		filterString == null  
					||  filterString.isEmpty()  
					||  e.getNom().indexOf(filterString) != -1 
					||  e.getPrenom().indexOf(filterString) != -1 
					||  e.getEmail().indexOf(filterString) != -1 
					||  e.getStatut().indexOf(filterString) != -1
				) {

	    			prepListFilter.add(e);
	    		}
	    	}
	    	
	    }
	    
	    @FXML
	    private void addclick(ActionEvent event) {
     
    		try {
	    		adminList.clear();
	    		prepList.clear();
	    		Connection conn = Modele.getInstance().getConnection();
		     
		
		        Tab selectedTab = tabPane.getSelectionModel().getSelectedItem();
	    		String tabTitle = selectedTab.getText();
	    		String sql = "";
	    		if (tabTitle.equals("Administrateurs")) {
	    			sql = "INSERT INTO employes (nom, prenom, email, mdp, statut_emp) VALUES ('nom', 'prenom', 'email', 'fictifbdd1.', 'Administrateur' )";
	    		} else if (tabTitle.equals("Préparateurs")) {
	    			sql = "INSERT INTO employes (nom, prenom, email, mdp, statut_emp) VALUES ('nom', 'prenom', 'email', 'fictifbdd1.', 'Preparateur' )";
	    		}
	    		Statement stmt = conn.createStatement();
	    		stmt.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
		        ResultSet rs = stmt.getGeneratedKeys();
		        if (rs.next()) {
		            int insertedId = rs.getInt(1); // La première colonne contient la clé générée
		            boolean isValid = openPopup(insertedId);
		            if(!isValid) {
		            	sql = "DELETE FROM employes where id_employe="+insertedId;
		            	stmt.execute(sql);
		            }
		        }        
	    	} catch (SQLException e) {
		        e.printStackTrace();
		    }
	    }
}