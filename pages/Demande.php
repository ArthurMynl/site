<?php

include "../includes/core.php";
$_TITRE_PAGE = "Amis RS ESEO";

if (isset($_POST["rechercher_amis_submit"]) && $_POST["rechercher_amis_submit"] == 1) {
	$sql = "SELECT a.nom,a.prenom FROM Amis,Etudiant e, Etudiant a 
	WHERE e.idEtu = '".$SESSION['compte']." AND Amis.etudiant = e.idEtu
    AND Amis.amis = a.idEtu 
    AND a.prenom = '" .trim($_POST['amis'])."";
	
    $result = $mysqli->query($sql);
    if (!$result) {
        exit($mysqli->error);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Demande d'ami</title>
    <link rel="stylesheet" href="../style/index_style.css">
</head>

<body>
    <div id="container">
        <div id="content-wrap">
            <!-- create the navbar -->
            <nav class="navbar">
                <ul>
                    <li> <img src="../assets/logo.png" class="logo"> </li>
<<<<<<< HEAD
                    <li> <a href="index.php">Accueil</a> </li>
                    <li> <a href="etudiants.php">Étudiants</a> </li>
                    <li> <a href="amis.php" class="active">Amis</a> </li>
                    <li> <a href="./index.php?logout=1" class="deconnexion">Déconnexion</a> </li>
=======
                    <li> <a href="./accueil.php">Accueil</a> </li>
                    <li> <a href="./etudiants.php">Etudiants</a> </li>
                    <li> <a href="./Amis.php" class="active" >Amis</a> </li>
                    <li> <a href="./index.php?logout=1">Deconnexion</a> </li>
>>>>>>> ec356d9776cf0946c61de68649307e5c8ccc6ac2
                </ul>
            </nav>

            <h1><?php echo "Liste d'amis" ?></h1>
            <nav class="navbar">
                <ul>
                    <li> <a href="./Amis.php">Amis </a> </li>
                    <li> <a href="./Demande.php" class="active">Demande en cours</a> </li>
                    <li> 
                    <form class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" placeholder="Rechercher un ami" aria-label="Search">
                        <button class="btn btn-outline-success my-2 my-sm-0" values = 1 name= "rechercher_amis_submit" type="submit">Valider</button>
                     </form>                  
                </ul>
            </nav>
            <p id="afficheAmis">
                <?php 
                $mysqli = new mysqli($infoBdd["server"], $infoBdd["login"], $infoBdd["password"], $infoBdd["db_name"]);

                if ($mysqli->connect_errno) { exit("Problème de connexion à la BDD");}
                
                $sql = "SELECT statut, e.prenom, a.prenom 
                FROM Amis, Etudiant e, Etudiant a 
                WHERE e.idEtu = Amis.etudiant AND a.idEtu = Amis.amis AND statut = 'en attente' AND e.idEtu= '".$_SESSION["compte"]."'";
                
                $result = $mysqli->query($sql);
                if (!$result) { exit($mysqli->error); }
                while ($row=$result -> fetch_array()){
                    echo $row["prenom"]." ";
                    echo $row["statut"];
                    echo "<br>";
                    echo "<button type='submit' value='1' name='supp_submit'> Supprimer requête</button>";
                }
                if (isset($_POST["supp_submit"]) && $_POST["supp_submit"] == 1) {
                    $sql = "DELETE etudiant, amis, dateAjout, statut
                    FROM Amis, Etudiant e, Etudiant a
                    WHERE e.idEtu = Amis.etudiant AND a.idEtu = Amis.amis AND statut = 'en attente' AND e.idEtu= '".$_SESSION["compte"]."'";
                }
                ?>
            </p>
            <p id="barreRecherche">

            </p>

        </div>
    </div>
</body>