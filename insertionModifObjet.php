<?php require "header.php" ?>
<section>
<?php

// Modification dans la BDD
if (isset($_POST['idModif'])){
	
	$statusMsg = '';
	// File upload path
	$targetDir = "images/objects/";
	$fileName = basename($_FILES["file"]["name"]);
	$targetFilePath = $targetDir . $fileName;
	$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
	
	if (!empty($_POST['nomObjet'])){

		echo '<h1>Objet modifié !</h1>';

		$new = $bdd->prepare('UPDATE Objet SET nom=:nom, idEtat=:idEtat, idCategorie=:idCategorie WHERE idObjet=:idObjet'); 
		$new->execute(array(
			':nom' => $_POST['nomObjet'],
			':idEtat' => $_POST['etatObjet'],
			':idCategorie' => $_POST['categorieObjet'],
			':idObjet' => $_POST['idModif']
		)); 
	}
	else{

		echo '<h1>Objet modifié !</h1>';

		$new = $bdd->prepare('UPDATE Objet SET idEtat=:idEtat, idCategorie=:idCategorie WHERE idObjet=:idObjet'); 
		$new->execute(array(
			':idEtat' => $_POST['etatObjet'],
			':idCategorie' => $_POST['categorieObjet'],
			':idObjet' => $_POST['idModif']
		)); 
	}
	if(!empty($_FILES["file"]["name"])){
		// Allow certain file formats
		$allowTypes = array('jpg','png','jpeg');
		if(in_array($fileType, $allowTypes)){
			// Upload file to server
			if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
				// Insert image file name into database
				$updateImg = $bdd->query("UPDATE Objet SET image = '".$fileName."' WHERE idObjet = " . $_POST['idModif']);
				if($updateImg){
					$statusMsg = "Le fichier ".$fileName. " a été chargé";
				}else{
					$statusMsg = "Chargement impossible. Veuillez réessayer.";
				} 
			}else{
				$statusMsg = "Désolé, une erreur lors du chargement est survenue.";
			}
		}else{
			$statusMsg = 'Désolé, seuls les fichiers JPG, JPEG, PNG sont autorisés.';
		}
	}
	echo '<h1>'.$statusMsg.'</h1>';
	
}
elseif (isset($_POST['idSup'])){

	
	$new = $bdd->prepare('DELETE FROM Objet WHERE idObjet=:idObjet'); 
	$new->execute(array(':idObjet' => $_POST['idSup'])); 
	echo '<h1>Objet supprimé !</h1>';
}
?>
</section>
<?php require "footer.php" ?>
