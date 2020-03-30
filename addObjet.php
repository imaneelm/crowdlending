<?php 
	/*
	*	Page traitement d'ajout objet + affichage résultat
	*	Créé par Rkhissi Maha
	*	Dernière modification : 12/10/2019
	*/
?>
<?php require "header.php" ?>

<section>
	<?php
		// Insertion dans DB
		$insertQuery = $bdd->prepare('INSERT INTO Objet(nom, idEtat, idCategorie, idPossesseur) VALUES(:nom, :idEtat, :idCategorie, :idPossesseur)'); 
		$insertQuery->execute(array(
			':nom' => $_POST['nomObjet'],
			':idEtat' => $_POST['etatObjet'],
			':idCategorie' => $_POST['categorieObjet'],
			':idPossesseur' => $_SESSION['id'] 
		));

		$id = $bdd->lastInsertId();
		
		$statusMsg = '';
		// File upload path
		$targetDir = "images/objects/";
		$fileName = basename($_FILES["file"]["name"]);
		$targetFilePath = $targetDir . $fileName;
		$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
		if(!empty($_FILES["file"]["name"])){
			// Allow certain file formats
			$allowTypes = array('jpg','png','jpeg');
			if(in_array($fileType, $allowTypes)){
				// Upload file to server
				if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
					// Insert image file name into database
					$updateImg = $bdd->query("UPDATE Objet SET image = '".$fileName."' WHERE idObjet = " . $id);
					if(!$updateImg){
						$statusMsg = "Chargement impossible. Veuillez réessayer.";
					} 
				}else{
					$statusMsg = "Désolé, une erreur lors du chargement est survenue.";
				}
			}else{
				$statusMsg = 'Désolé, seuls les fichiers JPG, JPEG, PNG sont autorisés.';
			}
		}
		
		echo '<h1>Objet ajouté !</h1>';
		echo '<h1>'.$statusMsg.'</h1>';
		echo "<a href ='voirObjet.php?id=". $id ."'> Voir l'objet </a>";
	?>
</section>

<?php require "footer.php" ?>