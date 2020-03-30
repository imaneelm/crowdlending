<?php require "header.php"?>

<section>
	<h1> Mes Emprunts </h1>

	<table>
		<tr>
			<th>Objet</th>
			<th>Date début</th>
			<th>Date de retour</th>
			<th>Possesseur</th>
			<th>Validation</th>
		</tr>

	<?php
	$emprunts = $bdd->prepare('SELECT Objet.nom AS nomObjet, Objet.idObjet,Emprunt.idEmprunt, Objet.idPossesseur, Membre.pseudo, Emprunt.validation,
									DATE_FORMAT(Emprunt.dateDebut, \'%d / %m / %Y\') AS dateDebut,
									DATE_FORMAT(Emprunt.dateFin, \'%d / %m / %Y\') AS dateFin
								FROM Emprunt
								INNER JOIN Objet ON Objet.idObjet=Emprunt.idObjet
								INNER JOIN Membre ON Objet.idPossesseur=Membre.idMembre
								WHERE idEmprunteur=:idEmprunteur
								ORDER BY Emprunt.validation, Emprunt.dateDebut DESC');
	$emprunts->execute(array('idEmprunteur'=>$_SESSION['id']));
	$todayy = new DateTime();
	$today = $todayy->format('d / m / Y');
	while ($mesEmprunts=$emprunts->fetch()) {
	// print_r($mesEmprunts);
		echo"<tr><td><a href='voirObjet.php?id=". $mesEmprunts['idObjet'] . "'>". $mesEmprunts['nomObjet']."</a></td>";
		echo"<td>".$mesEmprunts['dateDebut']."</td>";
		echo"<td>".$mesEmprunts['dateFin']."</td>";
		echo"<td><a href='voirMembre.php?id=" . $mesEmprunts['idPossesseur'] . "'>".$mesEmprunts['pseudo']."</a></td>";
		if ($mesEmprunts['validation']==0) {echo"<td>En cours de traitement</td>";}
		elseif ($mesEmprunts['validation']==1) {echo"<td>Validé</td>";}
		elseif ($mesEmprunts['validation']==2) {echo"<td>Refusé</td>";}
		if ( $today >= $mesEmprunts['dateDebut'] AND $mesEmprunts['validation'] == 1){
			echo"<td><a href='commenter.php?id=" .$mesEmprunts['idEmprunt'] . "'> Ajouter un commentaire</td>";
		}else{
			echo"<td></td>";
		}
	}
		$emprunts->closeCursor();
	?>
	</table>

</section>

<?php require "footer.php" ?>
