<?php 
require "connexionBD.php";
require "voirObjet.php"; 	


	$new = $bdd->prepare('DELETE FROM Favori WHERE idFavori=:idFavori'); 
	$new->execute(array(':idFavori' => $_GET['idFavori'])); 
	echo "<script>alert(\" Cet objet a été supprimé de vos favoris \");</script>";
	echo "<script type='text/javascript'>document.location.replace('voirObjet.php?id=".$_GET['idObjetFav']."');</script>";


?>