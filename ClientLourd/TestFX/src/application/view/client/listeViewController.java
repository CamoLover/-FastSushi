package application.view.client;


import java.io.IOException;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

import application.model.Client;
import application.model.Modele;
import javafx.animation.PauseTransition;
import javafx.beans.value.ChangeListener;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TextField;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.input.KeyEvent;
import javafx.stage.Modality;
import javafx.stage.Stage;
import javafx.util.Duration;

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
    
    @FXML
    private TextField searchBar;
    
    private ObservableList<Client> clientsList = FXCollections.observableArrayList();
    private ObservableList<Client> clientsListFilter = FXCollections.observableArrayList();
    private ChangeListener<Client> tableListener;
    private boolean isRefreshing = false;
    @FXML
    public void initialize() {
        refreshData();
        
        //Affectation des types de fcellules dans les colonnes 
        System.out.println("id:"+ new PropertyValueFactory<>("id"));
        colId.setCellValueFactory(new PropertyValueFactory<>("id"));
        colNom.setCellValueFactory(new PropertyValueFactory<>("nom"));
        colPrenom.setCellValueFactory(new PropertyValueFactory<>("prenom"));
        colEmail.setCellValueFactory(new PropertyValueFactory<>("email"));
        colTel.setCellValueFactory(new PropertyValueFactory<>("tel"));
        colVille.setCellValueFactory(new PropertyValueFactory<>("ville"));

        //Détermine quel Observable le TableView clients doit utiliser pour s'afficher
        tableClients.setItems(clientsListFilter);

        tableListener = ((obs, oldSelection, newSelection) -> {
        	if (isRefreshing) return; 
        	if (newSelection != null) {
        		//Desactive l'event
        		tableClients.getSelectionModel().selectedItemProperty().removeListener(tableListener);
                int idClient = newSelection.getId(); // si ton modèle Client a un getId()

	        	//Ouvrir la fiche du client en popup
	        	FXMLLoader loader = new FXMLLoader(getClass().getResource("/application/view/client/fiche.fxml"));
	            Parent root;
				try {
					System.out.println("OPEN");
					root = loader.load();
		            // Récupérer le contrôleur associé
		            ficheViewController popupController = loader.getController();
	
		            // Passer le paramètre
		            popupController.initData(idClient);
	
		            // Créer une nouvelle fenêtre (Stage)
		            Stage stage = new Stage();
		            stage.setTitle("Fiche Client");
		            stage.initModality(Modality.APPLICATION_MODAL);  // Bloque l'accès aux autres fenêtres tant que celle-ci est ouverte
		            stage.setScene(new Scene(root));
		            stage.showAndWait();
		            
		            System.out.println("Close");
		            //On rafraichis les donnéees si elles ont été modifié
		            isRefreshing = true;
		            
		            //CORRIGE le probleme de double lancement de l'event
		            PauseTransition pause = new PauseTransition(Duration.millis(100)); // 100 ms de pause
		            pause.setOnFinished(event -> {isRefreshing = false; refreshData();});
		            pause.play();
		            //Reactive l'event
		            tableClients.getSelectionModel().selectedItemProperty().addListener(tableListener);
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
        	}
        });
        tableClients.getSelectionModel().selectedItemProperty().addListener(tableListener);
    }
    
    public void refreshData() {	
    	try {
    		clientsList.clear();
    		Connection conn = Modele.getInstance().getConnection();
	        String sql = "SELECT * FROM clients";
	        Statement stmt = conn.createStatement();
	        ResultSet rs = stmt.executeQuery(sql);
	
	        /*
	         * Parcours des enregistrement pour créer la liste des clients
	         */
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
    	clientsListFilter.clear();
    	String filterString = searchBar.getText().toLowerCase();
    	for(Client c : clientsList) {
    		if(		filterString == null  
				||  filterString.isEmpty()  
				||  c.getNom().indexOf(filterString) != -1 
				||  c.getPrenom().indexOf(filterString) != -1 
				||  c.getEmail().indexOf(filterString) != -1 
				||  c.getTel().indexOf(filterString) != -1
				||  c.getVille().indexOf(filterString) != -1 
			) {
    			clientsListFilter.add(c);
    		}
    	}
    }
    
}