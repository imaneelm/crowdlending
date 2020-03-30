<?php require "header.php"; ?>

<section>
	<h1> Mes Objets </h1>

	<form action="modificationObjet.php" method="post"> 
	<table >
	<tr>
		<th>Objet</th>
		<th>Modifier</th>
		<th>Supprimer</th>
	</tr>

	<?php 
	$objets = $bdd->prepare('SELECT * FROM Objet WHERE idPossesseur=:idPossesseur');
	$objets->execute(array('idPossesseur'=>$_SESSION['id']));
	while ($mesObjets=$objets->fetch()) 
	{
		echo"<tr><td><a href = 'voirObjet.php?id=". $mesObjets['idObjet'] . "''>" .$mesObjets['nom']."</a></td>";
		echo"<td><input type='radio' name='objetModif' value='".$mesObjets['idObjet']."'></td>";
		echo"<td><input type='radio' name='objetSup' value='".$mesObjets['idObjet']."'></td>";
		echo"</tr>";
	}
	$objets->closeCursor();
	?>
	</table>
	<input type="submit" value="Valider"  />
	</form> 

</section>

<?php require "footer.php" ?>