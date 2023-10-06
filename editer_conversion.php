<?php
session_start();

$conversion = ""; // Initialisation de la variable $conversion

if (isset($_GET['id'])) {
    $conversion_id = $_GET['id'];

    // Assurez-vous que l'ID de conversion existe et que la session contient cette conversion
    if (isset($_SESSION['conversion_history'][$conversion_id])) {
        $conversion = $_SESSION['conversion_history'][$conversion_id];

        if (isset($_POST['update'])) {
            // Mettez à jour la conversion avec les nouvelles données
            // Assurez-vous de gérer la mise à jour correctement ici
            $_SESSION['conversion_history'][$conversion_id] = date("Y-m-d H:i:s") . " - " . $_POST['new_message'];

            // Redirigez l'utilisateur vers la page d'historique après la mise à jour
            header('Location: index-conversion.php');
            exit();
        }
    } else {
        echo "Conversion non trouvée.";
    }
} else {
    echo "ID de conversion non spécifié.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Éditer Conversion</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="utf8">
</head>
<body>
    <div id="content" align="center">
        <strong style="color: blue; font-size: 30px;">Éditer Conversion</strong><br><br>
        <?php
        if (!empty($conversion)) {
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
            <!-- Ajoutez un champ caché pour stocker l'ID de la conversion -->
            <input type="hidden" name="conversion_id" value="<?php echo $conversion_id; ?>">
            
            <!-- Affichez le formulaire de mise à jour avec les données actuelles de la conversion -->
            <!-- Assurez-vous que les champs du formulaire sont pré-remplis avec les valeurs actuelles -->
            <!-- Vous pouvez utiliser les valeurs de $conversion pour pré-remplir les champs -->
            <input type="text" name="new_message" value="<?php echo htmlspecialchars($conversion); ?>">
            <input type="submit" name="update" value="Mettre à Jour" class="bouton">
        </form>
        <?php
        } else {
            echo "Conversion non trouvée.";
        }
        ?>
    </div>
</body>
</html>
