<?php require "header.php" ?>

<?php

// Modification dans la BDD
if (isset($_POST['selection']) && !isset ($_POST['reclame'])){

	if (isset ($_POST['validation'])){

		// Refus des conflits d'emprunt
		$conflit = $bdd->prepare('
			UPDATE Emprunt
			SET validation = 2
			WHERE idEmprunt IN
			-- E1 objet a valider, E2 objet a refuser
				(SELECT E2.idEmprunt
				FROM Emprunt E1, Emprunt E2 
				WHERE E1.idEmprunt = :idE
				AND E2.idObjet = E1.idObjet
				AND E1.idEmprunt <> E2.idEmprunt
				AND E2.validation = 0
				AND ((E2.dateDebut <= E1.dateDebut AND E2.dateFin >= E1.dateDebut)
				     OR (E2.dateDebut <= E1.dateFin AND E2.dateFin >= E1.dateFin)
			     OR (E2.dateDebut >= E1.dateDebut AND E2.dateFin <= E1.dateFin)))');

		$conflit->execute(array('idE' => $_POST['selection'])); 


		// Validation de l'emprunt
		$new = $bdd->prepare('UPDATE Emprunt SET validation= 1 WHERE idEmprunt=:idEmprunt'); 
		$new->execute(array(
			':idEmprunt' => $_POST['selection']
		)); 	
	}

	// Refus de l'emprunt
	if (isset ($_POST['refus'])){	
		$new = $bdd->prepare('UPDATE Emprunt SET validation= 2 WHERE idEmprunt=:idEmprunt'); 
		$new->execute(array(
			':idEmprunt' => $_POST['selection']
		)); 	
	}
header('Location: objetsEmpruntes.php');

		
}

if (isset ($_POST['reclame'])){	

    $idEmrunt= $_POST['selection'];
    $idpos=$_SESSION['id'];
	$motif=$_POST['comment_rec'];
	
	
	$emprunts = $bdd->prepare('SELECT idEmprunteur FROM Emprunt WHERE idEmprunt=?');
    $emprunts->execute(array($idEmrunt)); 


     
	
    while ($row = $emprunts ->fetch()){
		  $enprunteur=$row['idEmprunteur'];
		  $reqRec = $bdd->prepare('INSERT INTO Reclamation (idEmprunt,idEmprunteur, idPossesseur, motif) VALUES (?, ?, ?,?) ');
		  $r=$reqRec->execute(array($idEmrunt,$enprunteur,$idpos,$motif));	
		  if($r==false) {
			  echo "<section>";
			  echo "<div class=\"objet_emp\" ><image src=\"images/warning.png\" id=\"warning_icon\"> <p>Vous avez déjà réclamer cet emprunt!</p><div>";
			  echo "</section>";
			  
		   }
		  else{
			 echo "<section>";
			 echo "<div class=\"objet_emp\" ><image src=\"images/good.jpg\" id=\"warning_icon\"> <p>Votre réclamation a été bien envoyée à l'administrateur.</p><div>";
			 echo "</section>";
			  
		  }
			
	 }
	

}
?>

<?php require "footer.php" ?>
