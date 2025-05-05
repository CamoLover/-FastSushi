package application;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

import application.model.Modele;
import at.favre.lib.crypto.bcrypt.BCrypt;

public class LoginController {
    private static LoginController instance;
    private Connection connection;
    private Integer currentEmployeeId;
    private String currentEmployeeStatus;
    
    // URL, user et password pour la connexion à la base de données
    private static final String DB_URL = "jdbc:mysql://localhost:33060/sushi_db";
    private static final String DB_USER = "admin";
    private static final String DB_PASSWORD = "root";
    
    // Constructeur privé pour le singleton
    private LoginController() {
       
    }
    
    // Méthode pour obtenir l'instance unique
    public static LoginController getInstance() {
        if (instance == null) {
            instance = new LoginController();
        }
        return instance;
    }
    
    // Méthode de connexion
    public boolean login(String email, String password) {
    	try {
			 connection = Modele.getInstance().getConnection();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
        String query = "SELECT id_employe, statut_emp, mdp FROM employes WHERE email = ?";
        
        try (PreparedStatement pstmt = connection.prepareStatement(query)) {
            pstmt.setString(1, email);
            
            System.out.println("Tentative de connexion avec email: " + email);
            
            ResultSet rs = pstmt.executeQuery();
            
            if (rs.next()) {
                String storedHash = rs.getString("mdp");
                System.out.println("Hash stocké: " + storedHash);
                
                // Vérifier le mot de passe avec BCrypt
                BCrypt.Result result = BCrypt.verifyer().verify(password.toCharArray(), storedHash);
                
                if (result.verified) {
                    this.currentEmployeeId = rs.getInt("id_employe");
                    this.currentEmployeeStatus = rs.getString("statut_emp");
                    System.out.println("Connexion réussie! Statut: " + this.currentEmployeeStatus);
                    return true;
                } else {
                    System.out.println("Mot de passe incorrect");
                }
            } else {
                System.out.println("Aucun utilisateur trouvé avec cet email");
            }
            
        } catch (SQLException e) {
            System.out.println("Erreur SQL: " + e.getMessage());
            e.printStackTrace();
        }
        
        return false;
    }
    
    // Méthode de déconnexion
    public void logout() {
        this.currentEmployeeId = null;
        this.currentEmployeeStatus = null;
    }
    
    // Méthode pour obtenir la page appropriée selon le statut
    public String getRedirectPage() {
        if (currentEmployeeStatus == null) {
            return "login/login.fxml";
        }
        
        switch (currentEmployeeStatus) {
            case "Preparateur":
                return "preparateur/preparateur.fxml";
            case "Administrateur":
            case "Manager":
                return "administrateur/administrateur.fxml";
            default:
                return "login/login.fxml";
        }
    }
    
    // Getters pour l'ID et le statut de l'employé connecté
    public Integer getCurrentEmployeeId() {
        return currentEmployeeId;
    }
    
    public String getCurrentEmployeeStatus() {
        return currentEmployeeStatus;
    }
    
    // Méthode pour fermer la connexion à la base de données
    public void closeConnection() {
        try {
            if (connection != null && !connection.isClosed()) {
                connection.close();
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
} 