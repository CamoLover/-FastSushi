package application.model;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class Modele {

    private static Modele instance;
    private Connection connection;

    // URL, user et password pour la connexion à la base de données
    private static final String DB_URL = "jdbc:mysql://localhost:33060/sushi_db";
    private static final String DB_USER = "admin";
    private static final String DB_PASSWORD = "root";

    private Modele() throws SQLException {
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            this.connection = DriverManager.getConnection(DB_URL, DB_USER, DB_PASSWORD);
            System.out.println("Connexion réussie à la base de données.");
        } catch (ClassNotFoundException | SQLException e) {
            e.printStackTrace();
        }
    }

    public static Modele getInstance() throws SQLException {
        if (instance == null || instance.getConnection().isClosed()) {
            instance = new Modele();
        }
        return instance;
    }

    public Connection getConnection() {
        return connection;
    }
}