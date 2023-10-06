<?php
session_start();

if (isset($_POST['submit']) && isset($_POST['conversion_id']) && isset($_POST['new_message'])) {
    $conversion_id = $_POST['conversion_id'];
    $new_message = $_POST['new_message'];
    
    // Parcourez l'historique des conversions pour trouver la conversion avec l'ID correspondant
    foreach ($_SESSION['conversion_history'] as $key => $conversion) {
        $conversion_parts = explode(" - ", $conversion, 2); // Séparez la date du message
        if (count($conversion_parts) == 2) {
            $date = $conversion_parts[0];
            $message = $conversion_parts[1];
            
            // Si l'ID correspond, mettez à jour la conversion
            if ($key == $conversion_id) {
                $_SESSION['conversion_history'][$key] = date("Y-m-d H:i:s") . " - " . $new_message;
                
                // Redirigez vers la page principale après la mise à jour
                header("Location: index-conversion.php");
                exit();
            }
        }
    }
    echo "Conversion non trouvée.";
} else {
    echo "Données de mise à jour non valides.";
}
?>
