<?php

include '../includes/core.php';

ini_set("post_max_size", "100000M");
ini_set("upload_max_filesize", "100000M");
ini_set("memory_limit", -1);

$nomOrigine = $_POST['file'];
$extensionFichier = pathinfo($nomOrigine, PATHINFO_EXTENSION);
$extensionsAutorisees = array("jpeg", "jpg", "gif", "png");

if (!(in_array($extensionFichier, $extensionsAutorisees))) {
    $MESSAGE_ERROR = "Le fichier n'a pas l'extension attendue";
} else {
    $MESSAGE_VALID = "Le fichier a correctement été upload";

    $repertoireDestination = dirname(__FILE__) . "/";
    $nomDestination = "file_" . date("YmdHis") . "." . $extensionFichier;

    // on récupère les infos du fichier à uploader
    $file_temp = $_POST['file']['tmp_name'];
    $file_name = $_POST['file']['name'];

    // on renomme le fichier
    $file_date = date("ymdhis");
    $file_n_nom = $file_date . "." . $extensionFichier;

    if (move_uploaded_file(
        $_POST["file"]["tmp_name"],
        $repertoireDestination . $file_n_nom
    )) {
        echo "Le fichier temporaire " . $_POST["file"]["tmp_name"] .
            " a été déplacé vers " . $repertoireDestination . $file_n_nom;
    } else {
        echo "Le fichier n'a pas été uploadé (trop gros ?) ou " .
            "Le déplacement du fichier temporaire a échoué" .
            " vérifiez l'existence du répertoire " . $repertoireDestination . $file_n_nom;
    }
}

$media = $_POST['file'];
$requestMedia = "UPDATE Article SET media = '" . $media . "'";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Publication d'un article</title>
    <link rel="stylesheet" href="../style/articles_style.css">
    <link rel="stylesheet" href="../style/navbar_style.css">
    <link rel="stylesheet" href="../style/footer_style.css">
</head>

<body>
    <div id="container">
        <div id="content-wrap">
            <div class='message'>
                <?php echo "<p class='message-error'> $MESSAGE_ERROR </p>";
                echo "<p class='message-valid'> $MESSAGE_VALID </p>";
                ?>
                <button class='close'> x </button>
            </div>
            <!-- create the navbar -->
            <nav class="navbar">
                <ul>
                    <li> <img src="../assets/logo.png" id="logo"> </li>
                    <li> <a href="/pages/index.php">Accueil</a> </li>
                    <li> <a href="/pages/etudiants.php">Étudiants</a> </li>
                    <?php if ($_SESSION["compte"]) { ?>
                        <?php
                        echo "<li> <a href='profil.php?id=" . $_SESSION["compte"] . "'>Profil</a> </li>";
                        echo "<li><a href='edit_profil.php?id=" . $_SESSION["compte"] . "'>Mettre à jour le profil</a></li>";
                        echo "<li> <a href='articles.php?id=" . $_SESSION["compte"] . "' class='active'>Publier un article</a> </li>";
                        ?>
                        <li> <a href="./index.php?logout=1">Déconnexion</a> </li>
                    <?php } ?>
                </ul>
            </nav>


            <h1><?php echo "Publier un article" ?></h1>
            <div class="corps">
                <div class="article">
                    <h2> Publication Article </h2>
                    <form method="post">
                        <input type="contenu" name="contenu" id="contenu" placeholder="Contenu">
                        <div class="visibilite">
                            <label class="etiquette-visibilite"> Visibilité </label>
                            <select name="visibilite" id="visibilite">
                                <option value="" disabled selected>-- Choisissez --</option>
                                <option value="public"> public </option>
                                <option value="amis"> amis </option>
                            </select>
                        </div>
                        <form enctype="multipart/form-data" action="fileupload.php" method="post">
                            <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                            Média <input type="file" name="file" />
                            <button type="submit" value="1" name="article_preview"> PREVIEW ARTICLE </button>
                        </form>
                    </form>
                </div>
                <div class="apercu">
                    <h2> Aperçu dernier article </h2>
                    <form method="post">
                        <h4> <?php echo $_POST["contenu"] ?></h4>
                        <?php echo '<img src="' . $repertoireDestination . $file_n_nom . '">'; ?>
                        <h4> <?php echo $_POST["visibilite"] ?></h4>
                        <?php
                        ini_set('date.timezone', 'Europe/Paris');
                        $now = date_create()->format('Y-m-d H:i:s');
                        echo $now;

                        ?>
                        <button type="reset" value="1" name="article_modify"> MODIFIER ARTICLE </button>
                        <button type="submit" value="1" name="article_submit"> PUBLIER ARTICLE </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- create the footer -->
        <footer>
            <p>Copyright &copy; 2022 - Par Le groupe - Tous droits réservés</p>
            <?php $mysqli->close(); ?>
        </footer>
    </div>
</body>