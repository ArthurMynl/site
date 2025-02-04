<?php

include '../includes/core.php';

$request = "SELECT etudiant.nom as nomEtudiant, prenom, email, photo, `description`, anneeScolaire.nom as nomAnnee FROM etudiant, anneeScolaire WHERE idAnneeScolaire = anneeScolaire AND idEtu =" . $_SESSION['compte'];
$result = $mysqli->query($request);

$request_pwd = "SELECT motDePasse FROM Etudiant WHERE idEtu=" . $_SESSION['compte'];
$result_pwd = $mysqli->query($request_pwd);

if (isset($_POST["edit_profil_submit"]) && $_POST["edit_profil_submit"] == 1) {

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $anneeScolaire = $_POST['annees'];
    $login = $_POST['login'];
    $description = $mysqli->real_escape_string(trim($_POST['description']));
    $old_pwd = $_POST['old_password'];
    $new_pwd = $_POST['password'];
    $confirm_pwd = $_POST['password_confirm'];
    $result_old_pwd = $result_pwd->fetch_assoc();

    if (isset($nom) && trim($nom) != '') {
        $requestNom = "UPDATE Etudiant SET nom = '" . $nom . "' WHERE idEtu = " . $_SESSION['compte'];
        $resultNom = $mysqli->query($requestNom);
    }

    if (isset($prenom) && trim($prenom) != '') {
        $requestPrenom = "UPDATE Etudiant SET prenom = '" . $prenom . "' WHERE idEtu = " . $_SESSION['compte'];
        $resultPrenom = $mysqli->query($requestPrenom);
    }

    if (isset($anneeScolaire) && trim($anneeScolaire) != '') {
        $requestAS = "UPDATE Etudiant SET anneeScolaire='" . $anneeScolaire . "' WHERE idEtu=" . $_SESSION['compte'];
        $resultAS = $mysqli->query($requestAS);
    }

    if (isset($login) && trim($login) != '') {
        $requestLogin = "UPDATE Etudiant SET email = '" . $login . "' WHERE idEtu = " . $_SESSION['compte'];
        $resultLogin = $mysqli->query($requestLogin);
    }

    if (isset($description) && trim($description) != '') {
        $requestDescription = "UPDATE Etudiant SET `description` = '" . $description . "' WHERE idEtu = " . $_SESSION['compte'];
        $resultDescription = $mysqli->query($requestDescription);
    }

    if (isset($old_pwd) && trim($old_pwd) != '' && $old_pwd == $result_old_pwd['motDePasse']) {
        if (isset($new_pwd) && trim($new_pwd) != '' && isset($confirm_pwd) && trim($confirm_pwd) != '' && $new_pwd == $confirm_pwd) {
            $requestPwd = "UPDATE Etudiant SET motDePasse='" . $new_pwd . "' WHERE idEtu='" . $_SESSION['compte'] . "'";
            $resultPwd = $mysqli->query($requestPwd);
        }
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $file_name = $_FILES['photo']['name'];
        $file_loc = $_FILES['photo']['tmp_name'];
        $file_extension = pathinfo($file_name)["extension"];
        $folder = '../assets/profil/';

        $new_file_name = $_SESSION['compte'] . "." . $file_extension;
        $final_file = str_replace(' ', '-', $new_file_name);
        move_uploaded_file($file_loc, $folder . $final_file);

        $requestPhoto = "UPDATE Etudiant SET photo = '" . $final_file . "' WHERE idEtu = " . $_SESSION['compte'];
        $resultPhoto = $mysqli->query($requestPhoto);

        $imgSrc = '../assets/profil/' . $final_file;

        //getting the image dimensions
        list($width, $height) = getimagesize($imgSrc);

        //saving the image into memory (for manipulation with GD Library)
        $myImage = imagecreatefromjpeg($imgSrc);

        // calculating the part of the image to use for thumbnail
        if ($width > $height) {
            $y = 0;
            $x = ($width - $height) / 2;
            $smallestSide = $height;
        } else {
            $x = 0;
            $y = ($height - $width) / 2;
            $smallestSide = $width;
        }

        // copying the part into thumbnail
        $thumbSize = 400;
        $thumb = imagecreatetruecolor($thumbSize, $thumbSize);
        imagecopyresampled($thumb, $myImage, 0, 0, $x, $y, $thumbSize, $thumbSize, $smallestSide, $smallestSide);

        imagejpeg($thumb, $folder . $final_file);
    }

    header("Location: profil.php?id=" . $_SESSION['compte']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mise à jour du profil</title>
    <link rel="stylesheet" href="../style/navbar_style.css">
    <link rel="stylesheet" href="../style/edit_profil_style.css">
    <link rel="stylesheet" href="../style/footer_style.css">

</head>

<body>

    <div id="container">
        <div id="content-wrap">
            <!-- create the navbar -->
            <nav class="navbar">
                <ul>
                    <li> <img src="../assets/logo.png" id="logo"> </li>
                    <li> <a href="index.php">Accueil</a> </li>
                    <li> <a href="etudiants.php">Étudiants</a> </li>
                    <?php if ($_SESSION["compte"]) {
                        echo "<li> <a href='profil.php?id=" . $_SESSION["compte"] . "' class='active'>Profil</a> </li>"; ?>
                        <li> <a href="articles.php">Publier un article</a> </li>
                        <li> <a href="Amis.php">Amis</a> </li>
                        <li> <a href="./index.php?logout=1" class="deconnexion">Déconnexion</a> </li>
                    <?php } ?>
                </ul>
            </nav>

            <div class="corps">
                <div class="editionProfil">
                    <?php $row = $result->fetch_assoc(); ?>
                    <h2>Mise à jour du profil</h2>
                    <form method="post" enctype="multipart/form-data">
                        <label> Photo de profil</label>
                        <input type="file" name="photo" id="modify-photo">
                        <label>Nom</label>
                        <?php echo "<input type='text' name='nom' id='nom' placeholder='" . $row["nomEtudiant"] . "'>"; ?>
                        <label>Prénom</label>
                        <?php echo "<input type='text' name='prenom' id='prenom' placeholder='" . $row["prenom"] . "'>"; ?>
                        <div class="selecteur">
                            <label>Année Scolaire</label>
                            <select name='annees' id='annees'>
                                <?php echo "<option value='" . $row['idAnneeScolaire'] . "'disabled selected>" . $row["nomAnnee"] . "</option>"; ?>
                                <option value="1"> E1</option>
                                <option value="2"> E2</option>
                                <option value="3"> E3e</option>
                                <option value="4"> E4e</option>
                                <option value="5"> E5e</option>
                                <option value="6"> E3a</option>
                                <option value="7"> E4a</option>
                                <option value="8"> E5a</option>
                                <option value="9"> B1</option>
                                <option value="10"> B2</option>
                                <option value="11"> B3</option>
                            </select>
                        </div>
                        <label>Email</label>
                        <?php echo "<input type='email' name='login' id='login' placeholder='" . $row["email"] . "'>"; ?>
                        <label>Description</label>
                        <?php $maDescription = $row["description"];
                        $maDescription = str_replace("'", "‘", $maDescription);
                        echo "<input type='text' name = 'description' id='description' placeholder='" . $maDescription . "'>"; ?>
                        <label>Ancien mot de passe</label>
                        <input type='password' name='old_password' id='old_password'>
                        <label>Nouveau mot de passe</label>
                        <input type='password' name='password' id='password'>
                        <label>Confirmez le nouveau mot de passe</label>
                        <input type='password' name='password_confirm' id='password_confirm'>
                        <button type="submit" value="1" name="edit_profil_submit">ENREGISTRER LES MODIFICATIONS</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <footer>
        <p>Copyright &copy; 2022 - Par Arthur Meyniel - Tous droits réservés</p>
        <?php $mysqli->close(); ?>
    </footer>

</body>