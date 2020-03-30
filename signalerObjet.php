<?php 
require "header.php";
//require "voirObjet.php" 
?>


<?php

if (isset($_POST['objetsign'])){	
	
	$new = $bdd->prepare('UPDATE Objet SET signale = 1 WHERE idObjet = :idObjet'); 
	$new->execute(array(':idObjet' => $_POST['objetsign'])); 
	echo "<script>alert(\" L'objet a bien été signalé !! Merci. \");</script>";
	header("Refresh: 0;url=voirObjet.php?id=".$_POST['objetsign']."");
}

?>