package application;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.control.Label;

public class ecran1 {

    @FXML
    private Label monLabel;

    @FXML
    void jeclique(ActionEvent event) {
    	monLabel.setText("J'ai cliqué");
    }

}
