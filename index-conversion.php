<?php
    // Démarrer la session (assurez-vous que cette ligne est présente en haut de toutes vos pages)
    session_start();

    // Initialisez un tableau vide pour stocker l'historique des conversions dans la session
    if (!isset($_SESSION['conversion_history'])) {
        $_SESSION['conversion_history'] = array();
    }

    $valeur = @$_POST['valeur'];
    $devise1 = @$_POST['devise1'];
    $devise2 = @$_POST['devise2'];
    $date_filtre = @$_POST['date_filtre']; // Date sélectionnée pour le filtre

    $is_valid = true;

    $conversion_result = ""; // Initialisez une variable pour stocker le résultat de la conversion

    if (isset($_POST['submit']) && !empty($valeur)) {
        if (!is_numeric($valeur)) {
            $error_message = "La valeur entrée n'est pas un nombre valide.";
            echo "<script>showErrorPopup('" . $error_message . "');</script>";
            $is_valid = false;
            echo $error_message;
        } else {
            $conv = 0.0;
            if ($devise1 != $devise2) {
                if ($devise1 == '€' && $devise2 == 'FCFA') {
                    $conv = 655.96 * $valeur;
                    $conversion_message = $valeur . " € = " . $conv . " FCFA";
                    $conversion_result = $conversion_message; // Stockez le résultat dans $conversion_result
                    echo "<b class='aff'>" . $conversion_message . "</b>";
                }
            
                if ($devise1 == 'FCFA' && $devise2 == '€') {
                    $conv = 0.001524 * $valeur;
                    $conversion_message = $valeur . " FCFA = " . $conv . " €";
                    $conversion_result = $conversion_message; // Stockez le résultat dans $conversion_result
                    echo "<b class='aff'>" . $conversion_message . "</b>";
                }
            
                if ($conv == 0) {
                    $conversion_result = "Conversion impossible";
                    echo $conversion_result; // Affichez le message d'erreur
                }
            
                // Ajoutez la conversion à l'historique des conversions dans la session avec la date actuelle
                $conversion_with_date = date("Y-m-d H:i:s") . " - " . $conversion_message;
                array_push($_SESSION['conversion_history'], $conversion_with_date);
            } else {
                $conversion_result = "Sélectionnez des devises différentes pour la conversion.";
                echo $conversion_result; // Affichez le message d'erreur
            }
        }
    }
?>


<!DOCTYPE html>
<html>
<head>
    <title>Conversion monnaie</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="utf8">
</head>
<body>
    <div id="content" align="center">
        <strong style="color: blue; font-size: 30px;">Conversion monnaie</strong><br><br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" onsubmit="return validateForm()">
            <table>
                <tr>
                    <td>
                        <b>Monnaie d'entrée :</b>
                    </td>
                    <td>
                        <select name="devise1" id="devise1">
                            <option value="<?php print $devise1; ?>">
                                <?php print $devise1; ?>
                            </option>
                            <option value="€">€</option>
                            <option value="FCFA">FCFA</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><b>Valeur :</b>
                    </td>
                    <td>
                        <input type="text" name="valeur" id="valeur" value="<?php print $valeur; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Monnaie de sortie :</b>
                    </td>
                    <td>
                        <select name="devise2" id="devise2">
                            <option value="<?php print $devise2; ?>">
                                <?php print $devise2; ?>
                            </option>
                            <option value="€">€</option>
                            <option value="FCFA">FCFA</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" name="submit" value="Convertir" class="bouton">
                    </td>
                </tr>
            </table>
        </form>

          <!-- Afficher les résultats de conversion ici -->
          <div id="conversion-result">
            <?php
                if (!empty($conversion_result)) {
                    echo "<b class='aff'>" . $conversion_result . "</b>";
                }
            ?>
        </div>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
            <label for="date_filtre">Filtrer par date :</label>
            <input type="date" name="date_filtre" id="date_filtre" value="<?php echo $date_filtre; ?>">
            <input type="submit" value="Filtrer">
        </form>
    </div>

    <!-- Code HTML pour le popup -->
    <div id="error-popup" class="popup">
        <div class="popup-content">
            <span class="close" id="close-popup">&times;</span>
            <div id="error-message" class="error-message"></div>
        </div>
    </div>

    <div id="conversion-history">
        <strong style="color: blue; font-size: 30px;">Historique des Conversions</strong><br><br>
        <table class="table">
            <tr>
                <th>Date</th>
                <th>Conversion</th>
                <th>Action</th> <!-- Nouvelle colonne pour les actions -->
            </tr>
            <?php
            $total = 0; // Initialisation du total
            // Affichez l'historique des conversions depuis la session, en filtrant par date si une date est sélectionnée
            foreach ($_SESSION['conversion_history'] as $key => $conversion) {
                $conversion_parts = explode(" - ", $conversion, 2); // Séparez la date du message
                if (count($conversion_parts) == 2) {
                    $date = $conversion_parts[0];
                    $message = $conversion_parts[1];

                    // Appliquer le filtre par date s'il est défini
                    if (empty($date_filtre) || $date_filtre == date("Y-m-d", strtotime($date))) {
                        echo "<tr>";
                        echo "<td>" . $date . "</td>";
                        echo "<td>" . $message . "</td>";
                        echo "<td>
                                <a href='editer_conversion.php?id=" . $key . "'>Éditer</a> | 
                                <a href='delete_conversion.php?id=" . $key . "'>Supprimer</a> | 
                                <a href='update_conversion.php?id=" . $key . "'>Mise à jour</a>
                              </td>"; // Ajoutez les liens d'édition et de suppression
                        echo "</tr>";

                        // Mettez à jour le total en fonction de la conversion actuelle
                        // Vous devrez extraire la valeur de la conversion à partir de $message
                        $conversion_parts = explode(" = ", $message);
                        if (count($conversion_parts) == 2) {
                            $conversion_value = trim($conversion_parts[1], " FCFA €");
                            $total += (float)$conversion_value;
                        }
                    }
                }
            }
            ?>
        </table>
        
        <!-- Afficher le total en bas du tableau -->
        <div style="margin-top: 20px;">
            <strong>Total : <?php echo $total; ?></strong>
        </div>
    </div>

    <!-- JavaScript pour le popup d'erreur -->
    <script>
        function showErrorPopup(message) {
            var errorPopup = document.getElementById("error-popup");
            var errorMessage = document.getElementById("error-message");
            errorMessage.innerHTML = message;
            errorPopup.style.display = "block";
        }

        function closeErrorPopup() {
            var errorPopup = document.getElementById("error-popup");
            errorPopup.style.display = "none";
        }

        // Écouteur d'événement pour le bouton de fermeture du popup
        var closePopupButton = document.getElementById("close-popup");
        closePopupButton.addEventListener("click", closeErrorPopup);

        // Fonction de validation du formulaire
        function validateForm() {
            var valeur = document.getElementById("valeur").value;
            if (!isNumeric(valeur)) {
                showErrorPopup("La valeur entrée n'est pas un nombre valide.");
                return false; // Empêche la soumission du formulaire
            }
            return true; // Autorise la soumission du formulaire
        }

        // Fonction pour vérifier si une valeur est numérique
        function isNumeric(value) {
            return !isNaN(parseFloat(value)) && isFinite(value);
        }
    </script>
</body>
</html>
