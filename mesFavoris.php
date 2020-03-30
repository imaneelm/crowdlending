<?php require "header.php"?>

<section>
	<h1> Mes favoris </h1>

	<table>
		<tr>
			<th>Objet</th>
			<th>Etat</th>
			<th>Catégorie</th>
			<th>Possesseur</th>
		</tr>

	<?php
	$favoris = $bdd->prepare('SELECT Objet.nom AS nomObjet, Objet.idObjet,Favori.idFavori, Objet.idPossesseur, Membre.pseudo, Etat.nom AS Etat, 								Categorie.nom AS Categorie
								FROM Favori
								INNER JOIN Objet ON Objet.idObjet=Favori.idObjet
								INNER JOIN Etat ON Objet.idEtat=Etat.idEtat
								INNER JOIN Categorie ON Objet.idCategorie=Categorie.idCategorie
								INNER JOIN Membre ON Objet.idPossesseur=Membre.idMembre
								WHERE Favori.idMembre=:idMembre
								ORDER BY Objet.nom DESC');
	$favoris->execute(array('idMembre'=>$_SESSION['id']));
	$todayy = new DateTime();
	$today = $todayy->format('d / m / Y');
	while ($mesFavoris=$favoris->fetch()) {
	// print_r($mesEmprunts);
		echo"<tr><td><a href='voirObjet.php?id=". $mesFavoris['idObjet'] . "'>". $mesFavoris['nomObjet']."</a></td>";
		echo"<td>".$mesFavoris['Etat']."</td>";
		echo"<td>".$mesFavoris['Categorie']."</td>";
		echo"<td><a href='voirMembre.php?id=" . $mesFavoris['idPossesseur'] . "'>".$mesFavoris['pseudo']."</a></td>";

	}
		$favoris->closeCursor();
	?>
	</table>

</section>

<?php require "footer.php" ?>
