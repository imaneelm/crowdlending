<?php require "header.php" ?>

<?php

if (isset($_POST['Supprimer'])){	
	
	$rep = $bdd->prepare('DELETE FROM Objet WHERE idObjet=:idObjet'); 
	$rep->execute(array('idObjet'=>$_POST['objetselect'])); 
	echo "<script>alert(\" L'objet a bien été supprimé !! Merci. \");</script>";
	header("Refresh: 0;url=ListObjSignale.php");
}

if (isset($_POST['Ignorer'])){	
	$rep = $bdd->prepare('UPDATE Objet SET signale = 0 WHERE idObjet = :idObjet');
	$rep->execute(array('idObjet'=>$_POST['objetselect'])); 
	header("Refresh: 0;url=ListObjSignale.php");

}
?>

<?php require "footer.php" ?>