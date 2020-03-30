<?php
	/*
	*	Page traitement modification mot de passe
	*	Créé par Rkhissi Maha
	*	Dernière modification : 17/10/2019
	*/
?>

<?php require "header.php" ?>

<?php

	//var_dump($_POST);
	if(!isset($_POST['idUtilisateur'])){
		echo "Erreur!";
	}else{
		// Modification dans BDD
		$updateQuery = $bdd->prepare('UPDATE Membre SET 
			mdp = :mdpUtilisateur 
			WHERE idMembre = :idMembre'); 
			
		$updateQuery->execute(array(
			'mdpUtilisateur' => $_POST['mdpUtilisateur'],
			'idMembre' => $_SESSION['id']
		));		
		
		echo '<section><h1>Mot de passe modifié!</h1></section>';
	}
?>

<?php require "footer.php" ?>