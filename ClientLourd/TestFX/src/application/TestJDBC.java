package application;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class TestJDBC {
    public static void main(String[] args) {
        String url = "jdbc:mysql://localhost:33060/sushi_db";
        String user = "admin";
        String password = "root";

        try {
            // Charger le driver (facultatif pour les nouvelles versions de Java)
            Class.forName("com.mysql.cj.jdbc.Driver");

            // Établir la connexion
            Connection connection = DriverManager.getConnection(url, user, password);
            System.out.println("Connexion réussie !");
            
            // Fermer la connexion
            connection.close();
        } catch (ClassNotFoundException e) {
            System.out.println("Erreur : Driver JDBC non trouvé !");
            e.printStackTrace();
        } catch (SQLException e) {
            System.out.println("Erreur de connexion !");
            e.printStackTrace();
        }
    }
}
