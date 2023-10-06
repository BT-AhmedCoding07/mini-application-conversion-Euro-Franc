<?php
session_start();

if (isset($_GET['id'])) {
    $conversion_id = $_GET['id'];

    // Supprimez la conversion sélectionnée de $_SESSION['conversion_history']
    unset($_SESSION['conversion_history'][$conversion_id]);

    // Réindexez le tableau pour éviter les clés manquantes
    $_SESSION['conversion_history'] = array_values($_SESSION['conversion_history']);

    // Redirigez l'utilisateur vers la page d'historique après la suppression
    header('Location: index-conversion.php');
    exit();
}
?>
