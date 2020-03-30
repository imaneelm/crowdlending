<?php
	/*
	*	Page traitement modification coordonnées utilisateur
	*	Créé par Rkhissi Maha
	*	Dernière modification : 10/12/2019
	*/
?>

<?php require "header.php" ?>

<?php
	$statusMsg = '';
	// File upload path
	$targetDir = "images/users/";
	$fileName = basename($_FILES["file"]["name"]);
	$targetFilePath = $targetDir . $fileName;
	$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
	

	if(!isset($_POST['idUtilisateur'])){
		echo "Erreur!";
	}else{
		if(!empty($_FILES["file"]["name"])){
			// Allow certain file formats
			$allowTypes = array('jpg','png','jpeg');
			if(in_array($fileType, $allowTypes)){
				// Upload file to server
				if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
					// Insert image file name into database
					$updateImg = $bdd->query("UPDATE Membre SET image = '".$fileName."' WHERE idMembre = " . $_SESSION['id']);
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
		// Modification  autre données dans BDD
		$updateQuery = $bdd->query("UPDATE Membre SET 
			pseudo = '" . $_POST['pseudoUtilisateur'] . "', 
			email = '" . $_POST['emailUtilisateur'] . "', 
			nom = '" . $_POST['nomUtilisateur']. "', 
			prenom = '". $_POST['prenomUtilisateur'] ."', 
			adresse = '". $_POST['adresseUtilisateur'] ."', 
			telephone = '". $_POST['telephoneUtilisateur'] ."' 
			WHERE idMembre = " . $_SESSION['id'] ); 
			
		if(!$updateQuery){
			echo '<section><h1 style="color : red;">Erreur : pseudo ou email déjà utilisé!</h1>
					<h1 style="color : red;">'.$statusMsg.'</h1>
				<a href="formEditProfil.php">Retour</a>
			</section>';
			die();
		}else{
				echo '<section><h1>Vos coordonnées ont été modifiées!</h1>
				<h1>'.$statusMsg.'</h1></section>';
		}
		
	}
?>

<?php require "footer.php" ?>