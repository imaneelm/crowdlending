<?php 
require "connexionBD.php";
require "voirObjet.php"; 


	$new = $bdd->prepare('INSERT INTO Favori(idObjet, idMembre) VALUES(:idObjet, :idMembre)'); 
	$new->execute(array(':idObjet' => $_GET['idObjetFav'], ':idMembre' => $_SESSION['id']));
	echo "<script>alert(\" Cet objet a été ajouté à vos favoris \");</script>";
	echo "<script type='text/javascript'>document.location.replace('voirObjet.php?id=".$_GET['idObjetFav'].".php');</script>";
?>



