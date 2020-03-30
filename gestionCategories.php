<?php
	/*
	*	Page gestion de catégories
	*	Créé par Rkhissi Maha
	*	Dernière modification : 23/11/2019
	*/
?>

<?php require "header.php" ?>
<?php 
	// Accès page avec GET : 2 cas
	// cas 1 : Modification -> Récupérer catégorie
	if(isset($_GET['idCat'])){
		$query = $bdd->prepare
			("SELECT * from Categorie WHERE idCategorie = ? ");
		$query->execute(array($_GET['idCat']));
		$catModif = $query->fetch();
		$query->closeCursor();
	}
	
	// cas 2 : Suppression
	if(isset($_GET['idCatDelete'])){
		// On ne peut pas supprimer la catégorie par défaut
		if($_GET['idCatDelete'] == 1){
			phpAlert(   'Erreur : Vous ne pouvez pas supprimer la catégorie par défaut!'   );
		}else{
			// Changer catégorie = cat par déf idCategorie = 1
			$DefaultCatUpdate = $bdd->prepare("UPDATE Objet SET idCategorie=1 WHERE idCategorie= :oldCat");
			if($DefaultCatUpdate->execute(array(':oldCat' => $_GET['idCatDelete']))){
				$deleteQuery = $bdd->prepare('DELETE FROM Categorie WHERE idCategorie=:idCategorie');
				if($deleteQuery->execute(array(':idCategorie' => $_GET['idCatDelete']))){
					phpAlert(   'Catégorie supprimée!'   );
				}
			}else{
				phpAlert(   'Une erreur s\'est produite. Veuillez réessayer.'   );
			}
		}
	}
	
	// Accès avec POST : 2 cas
	// cas 1 : Ajout
	if(!isset($_POST['idCategorie']) && isset($_POST['nomCategorie'])){
		// Insertion dans DB
		$insertQuery = $bdd->prepare('INSERT INTO Categorie(nom) VALUES(:nom)'); 
		if(!$insertQuery->execute(array(':nom' => $_POST['nomCategorie']))){
			phpAlert(   'Vous ne pouvez pas ajouter la catégorie ' . $_POST['nomCategorie'] . ' car elle existe déjà!'  );
		}else{
			$id = $bdd->lastInsertId();
			$query = $bdd->prepare('SELECT * FROM Categorie WHERE idCategorie =?');
			$query->execute([$id]);
			$categorie = $query->fetch();			
			phpAlert(   'Catégorie ajoutée : ' . $categorie['nom']   );
		}
	}
	// cas 2 : Modification
	elseif(isset($_POST['idCategorie'])){
		// Insertion dans DB
		$updateQuery = $bdd->prepare('UPDATE Categorie SET nom = :nom WHERE idCategorie = :idCategorie'); 
		if(!$updateQuery->execute(array(
			':nom' => $_POST['nomCategorie'],
			':idCategorie' => $_POST['idCategorie']))){
			phpAlert(   'Erreur : Cette catégorie existe déjà! Choisissez un autre nom!'  );
		}else{
			phpAlert(   'Catégorie modifiée!'   );
		}
	}
	
?>
<?php $response = $bdd->query('SELECT * FROM Categorie'); ?>

<section>
	<h1>Gestion de catégories</h1>	
	<table>
		<thead>
			<tr>
				<th>Catégorie</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				while($categories = $response->fetch()){
					echo '<tr><td>' . $categories['nom'] . '</td>'
						. '<td><a href="gestionCategories.php?idCat=' . $categories['idCategorie'] . '">Modifier</a>'
						. ' | <a href="gestionCategories.php?idCatDelete=' . $categories['idCategorie'] . '" ' 
						. 'onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette catégorie?\');">Supprimer</a></td></tr>';
				}
			?>
		</tbody>
	</table>
	
	
	<form name="Form" action="gestionCategories.php" method="post"> 
		<fieldset>
			<?php 
				if(isset($_GET['idCat'])){
					echo '<legend><h2>Modifier une catégorie</h2></legend>';
				}else{
					echo '<legend><h2>Ajouter une catégorie</h2></legend>';
				}
			?>
			<div>
				<label>Nom :</label>
				<?php
					if(isset($_GET['idCat'])){
						echo '<input type="text" hidden="true" name="idCategorie" value="' . $_GET['idCat'] . '"/>';
					}
				?>
				<input type="text" name="nomCategorie" style="width : 200px;" 
					<?php 
						if(isset($_GET['idCat'])){
							echo ' value="' . $catModif['nom'] . '" ';
						}
					?>
				required="true" />
				<input type="submit" value="Valider" />
			</div>
		</fieldset>
	</form>
	
</section>

<?php
	function phpAlert($msg) {
		echo '<script type="text/javascript">alert("' . $msg . '")</script>';
	}
?>
<?php require "footer.php" ?>