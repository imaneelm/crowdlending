<?php require "header.php" ?>

<section>

	<?php
	if (isset($_GET['id']))
	{
		setlocale(LC_TIME, "fr_FR", "French");
		// Récupération de l'objet et de ses informations
		$query = $bdd->prepare
		("SELECT O.nom AS nom, C.nom AS cat, E.nom AS etat, M.pseudo AS prop, M.idMembre AS idP
			FROM Objet O, Categorie C, Etat E, Membre M
			WHERE O.idObjet = :idObjet
			AND O.idCategorie = C.idCategorie
			AND O.idEtat = E.idEtat
			AND O.idPossesseur = M.idMembre");

		$query->execute(array('idObjet' => $_GET['id']));

		// L'objet existe
		if ($query->rowCount() > 0)
		{
			$row = $query->fetch();
			$proprietaire = (isset($_SESSION['id']) && $row['idP'] == $_SESSION['id']);

			// Récupération des favoris de l'utilisateur
			if (isset($_SESSION['id'])){
				$favoris = $bdd->prepare
				("SELECT * FROM Favori
					WHERE idObjet = :idObjet AND idMembre = :idMembre");
				$favoris->execute(array('idObjet' => $_GET['id'],
									'idMembre' => $_SESSION['id']));
				$mesFavoris=$favoris->fetch();

				$todayy = new DateTime();
				$today = $todayy->format('d / m / Y');

				// Récupération des emprunts de l'utilisateur concernant cet objet seulement
				$emprunts = $bdd->prepare('SELECT idObjet, idEmprunt, idEmprunteur, validation, 
											DATE_FORMAT(dateDebut, \'%d / %m / %Y\') AS dateDebut
										FROM Emprunt
										WHERE idEmprunteur=:idEmprunteur AND idObjet = :idObjet AND validation = :validation AND dateDebut>=:dateDebut
										ORDER BY dateDebut DESC');
				$emprunts->execute(array('idEmprunteur' => $_SESSION['id'], 'idObjet' => $_GET['id'], 'validation' => 1,'dateDebut' => $today));
				$mesEmprunts=$emprunts->fetch();
			}

			// Affichage des informations sur l'objet
			echo '<h1>' . $row['nom'] . '</h1>'; 
			$queryImg = $bdd->prepare("SELECT image FROM Objet WHERE idObjet =:idObjet ");
			$queryImg->execute(array('idObjet' => $_GET['id']));
			if($queryImg->rowCount() > 0){
				while($rowImg = $queryImg->fetch(PDO::FETCH_ASSOC)){
					$imageURL = 'images/objects/'.$rowImg["image"];
			?>
    <img src="<?php echo $imageURL; ?>" />
			<?php 	}
				}else{ ?>
    <image src="images/objects/default.jpg">
			<?php 	}

				// Bouton Favoris
			if (isset($_SESSION['id'])){
				$idObjet = $_GET['id'];
				if ($favoris->rowCount() > 0) // Objet dans la liste
				{
					echo '<a href="supprimerFavori.php?idFavori='.$mesFavoris['idFavori'].'&idObjetFav='.$idObjet.'"> <i class="far fa-heart"></i></a>';
				}
				elseif ($favoris->rowCount() == 0) // Objet pas dans la liste
				{
					echo '<a href="ajoutFavori.php?idObjetFav='.$idObjet.'"> <i class="fas fa-heart"></i></a>';
				}
			}	
				//Bouton signaler

			if (isset($_SESSION['id'])){
					$idObjet = $_GET['id'];
					echo "<form action=\"signalerObjet.php?id=$idObjet\" method=\"post\">
							<input type=\"submit\" value=\"Signaler\"  >
							<input type=\"hidden\" name=\"objetsign\" value='".$_GET['id']."'>
						 </form>";
			}
			//
						
			echo "<p>Catégorie : " . $row['cat'] . "</p>";
			echo "<p>Etat : " . $row['etat'] . "</p>";
			if($proprietaire){
				echo "Vous êtes le propriétaire de l'objet";
			}
			else{
				echo "<p>Propriétaire : <a href='voirMembre.php?id=" . $row['idP'] . "'>". $row['prop'] . "</a></p>";
			}

			// Disponibilités et demande d'emprunt
			if(!$proprietaire){
				afficherDisponibilite($bdd);
				echo "<h2>Demande d'emprunt </h2>";
				if(isset($_SESSION['id'])){
					// Formulaire de demande d'emprunt
					$idObjet = $_GET['id'];
					echo "<form action=\"ajoutDemande.php?id=$idObjet\" method=\"post\" onsubmit=\" return TDate();\">";
					echo "Date d'emprunt : <input id =\"debut\" name=\"date_Debut\" type=\"date\"required ><br><br>";
					echo "Date de retour : <input id =\"fin\" name=\"date_Fin\" type=\"date\"  required ><br>";
					echo "<input  name=\"objetId\" type=\"hidden\" value=\"$idObjet\">";
					echo "<input type=\"submit\" value=\"Effectuer la demande\" >";
					echo "</form>";
				}
				else{
					echo "<a href='login.php'>Se connecter</a> pour effectuer un emprunt";
				}
			}

			// Affichage des commentaires
			echo "<h2>Commentaires sur l'objet</h2>";
			$rqt_cmt = $bdd->prepare("SELECT commentaire, date_commentaire, pseudo, idObjet
				FROM Emprunt, Membre
				WHERE Emprunt.idEmprunteur = Membre.idMembre
				AND idObjet = ?");

			$rqt_cmt->execute(array($_GET['id']));
			$exist = false;
			if($rqt_cmt->rowCount() > 0){
				while ($comments = $rqt_cmt->fetch()){
					if($comments['commentaire'] != NULL AND $comments['date_commentaire'] != NULL){
						$comment = explode("!$", $comments['commentaire']);
						$date = explode("!$", $comments['date_commentaire']);
						for($i=0; $i < sizeof($comment); $i++){
							if($date[$i] != NULL and $comment[$i] != NULL){
								$exist = true;
								echo "<p>".$comments['pseudo']." - ". $date[$i] . "</br>" .$comment[$i] ."</p>";
							}

						}
					}
				}
			}
			if($exist == false){
				echo "Pas de commentaires.";
			}

			// Affichage des emprunts futurs et de l'historique des emprunts
			// adapté en fonction de l'utilisateur
			if(isset($_SESSION['id'])){
				if(!$proprietaire){
					echo '<hr>';
					echo "<h2> Mes emprunts</h2>";
				}
				else{
					echo "<h2>Emprunts</h2>";
				}
				afficherEmpruntsFuturs($bdd, $proprietaire);
				afficherHistoriqueEmprunts($bdd, $proprietaire);
			}

		}

		else {
			echo "L'objet n'existe pas";
		}

		$query->closeCursor();
	}
	else {
		echo "Lien invalide";
	}
	?>

	<?php

	function afficherHistoriqueEmprunts($bdd, $proprietaire){
		echo "<h3> Historique des emprunts</h3>";

		if($proprietaire){
			$query = $bdd->prepare
			("SELECT E.idEmprunt, E.idEmprunteur, dateDebut, dateFin, M.pseudo AS emprunteur
				FROM Emprunt E, Membre M
				WHERE E.idEmprunteur = M.idMembre
				AND E.idObjet = :idObjet
				AND dateFin < CURDATE()
				AND validation = 1
				ORDER BY dateDebut DESC");

			$query->execute(array('idObjet' => $_GET['id']));

			// Liste des emprunts
			if($query->rowCount() > 0) {
				echo "<ul>";
				while($row = $query->fetch()){
					$dateDeb = new DateTime($row['dateDebut']);
					$dateFin = new DateTime($row['dateFin']);

					echo "<li> du " . strftime('%A %d %B ', $dateDeb->getTimeStamp()) .
					" au " . strftime('%A %d %B %G', $dateFin->getTimeStamp()) .
					" par <a href ='voirMembre.php?id=" . $row['idEmprunteur'] . "'>" . $row['emprunteur'] . "</a></li>";
				}
				echo "</ul>";
			}
			else{
				echo "L'objet n'a jamais été emprunté.";
			}
			$query->closeCursor();
		}
		else {
			$query = $bdd->prepare
			("SELECT idEmprunt, dateDebut, dateFin
				FROM Emprunt
				WHERE idEmprunteur = :emprunteur
				AND idObjet = :objet
				AND dateFin < CURDATE()
				AND validation = 1
				ORDER BY dateDebut DESC");

			$query->execute(array('emprunteur' => $_SESSION['id'],
				'objet'=> $_GET['id']));

			if($query->rowCount() > 0) {
				echo "<ul>";
				while($row = $query->fetch()){
					$dateDeb = new DateTime($row['dateDebut']);
					$dateFin = new DateTime($row['dateFin']);

					echo "<li> du " . strftime('%A %d %B ', $dateDeb->getTimeStamp()) .
					" au " . strftime('%A %d %B %G', $dateFin->getTimeStamp()) . "</li>";
				}
				echo "</ul>";
			}
			else{
				echo "Vous n'avez jamais emprunté cet objet.";
			}
			$query->closeCursor();
		}
	}

	function afficherEmpruntsFuturs($bdd, $proprietaire){
		echo "<h3> Emprunts prévus </h3>";

		if($proprietaire){
			$query = $bdd->prepare
			("SELECT E.idEmprunt, E.idEmprunteur, dateDebut, dateFin, M.pseudo AS emprunteur
				FROM Emprunt E, Membre M
				WHERE E.idEmprunteur = M.idMembre
				AND E.idObjet = :objet
				AND dateFin >= CURDATE()
				AND validation = 1
				ORDER BY dateDebut");

			$query->execute(array('objet' => $_GET['id']));

			if($query->rowCount() > 0) {
				echo "<ul>";
				while($row = $query->fetch()){
					$dateDeb = new DateTime($row['dateDebut']);
					$dateFin = new DateTime($row['dateFin']);

					echo "<li> du " . strftime('%A %d %B ', $dateDeb->getTimeStamp()) .
					" au " . strftime('%A %d %B %G', $dateFin->getTimeStamp()) .
					" par <a href ='voirMembre.php?id=" . $row['idEmprunteur'] . "'>" . $row['emprunteur'] . "</a></li>";
				}
				echo "</ul>";
			}
			else{
				echo "Pas d'emprunts prévus.";
			}
			$query->closeCursor();
		}
		else {
			$query = $bdd->prepare
			("SELECT idEmprunt, dateDebut, dateFin
				FROM Emprunt
				WHERE idEmprunteur = :emprunteur
				AND idObjet = :objet
				AND dateFin >= CURDATE()
				AND validation = 1
				ORDER BY dateDebut");

			$query->execute(array('emprunteur' => $_SESSION['id'],
				'objet' => $_GET['id']));

			if($query->rowCount() > 0) {
				echo "<ul>";
				while($row = $query->fetch()){
					$dateDeb = new DateTime($row['dateDebut']);
					$dateFin = new DateTime($row['dateFin']);

					echo "<li> du " . strftime('%A %d %B ', $dateDeb->getTimeStamp()) .
					" au " . strftime('%A %d %B %G', $dateFin->getTimeStamp()) ."</li>";
				}
				echo "</ul>";
			}
			else{
				echo "Pas d'emprunts prévus.";
			}
			$query->closeCursor();
		}
	}
	?>
</section>

<?php
function afficherDisponibilite($bdd){
		// Récupération des emprunts présents/futurs
	$query2 = $bdd->prepare
	("SELECT E.idEmprunt, E.idEmprunteur, dateDebut, dateFin, M.pseudo AS emprunteur
		FROM Emprunt E, Membre M
		WHERE E.idEmprunteur = M.idMembre
		AND E.idObjet = :objet
		AND dateFin >= CURDATE()
		AND validation = 1
		ORDER BY dateDebut");

	$query2->execute(array('objet' => $_GET['id']));
	echo "<h2>Disponibilités </h2>";

	if($query2->rowCount() > 0) {
			// Date du jour
		$dateAjd = new DateTime();

			// Dates de début et de fin du premier emprunt
		$row2 = $query2->fetch();
		$dateDeb1 = new DateTime($row2['dateDebut']);
		$dateFin1 = new DateTime($row2['dateFin']);

			// Liste des dates de disponibilités
		echo "<ul>";

			// Disponibilité entre aujourd'hui et le premier emprunt
		$dateDeb1->modify('-1 day');
		if($dateDeb1->format('Y-m-d') > $dateAjd->format('Y-m-d')){
			echo "<li> jusqu'au " . strftime('%A %d %B', $dateDeb1->getTimeStamp()) . "</li>";
		}
		else if($dateDeb1->format('Y-m-d') == $dateAjd->format('Y-m-d')){
			echo "<li> aujourd'hui</li>";
		}
		$dateDeb1->modify('+1 day');

			// Disponibilité entre 2 emprunts
		while($row2 = $query2->fetch()){
				// Date de début de l'emprunt suivant
			$dateDeb2 = new DateTime($row2['dateDebut']);

				// Disponibilité entre les deux emprunts
			if($dateFin1->modify('+1 day') < $dateDeb2){
				echo "<li> du " . strftime('%A %d %B ', $dateFin1->getTimeStamp()) .
				" au " . strftime('%A %d %B', $dateDeb2->modify('-1 day')->getTimeStamp()) . "</li>";
			}

				// Date de fin de cet emprunt
			$dateFin1 = new DateTime($row2['dateFin']);
		}

			// Disponibilité à partir du dernier emprunt
		echo "<li> à partir du " . strftime('%A %d %B', $dateFin1->modify('+1 day')->getTimeStamp()) . "</li>";
		echo "</ul>";
	}

		// Pas d'emprunts
	else{
		echo "Pas d'emprunts prévus, l'objet est disponible.";
	}
}
?>

<script>
	function TDate() {
    var debut = document.getElementById("debut").value;
    var fin = document.getElementById("fin").value;

/*Comparaison des dates choisis avec la date d'aujourd'hui.
- Si l'année courante est inférieure STRICTEMENT de l'année choisis la fonction retourne TRUE.
- Si l'année courante est supérieure STRICTEMENT que l'année choisis alors la fonction retourne FALSE.
- Si le mois courant est supérieure STRICTREMENT que l'année choisis alors la fonction retourne TRUE.
- Si le mois courant est inférieure STRICTEMENT que l'année choisis alors la fonction retourne FALSE.
- Si l'année courante et le mois courant sont égaux aux années et mois choisis alors on compare les jours:
	- si la date du jour courant est inférieure à la date choisis alors la fonction retourne TRUE.
	- sinon la fonction retourne FALSE.
*/
	
	var date_debut =  new Date (debut);
	var d_dd= date_debut.getDate();
	var d_mm= date_debut.getMonth();
	var d_yy= date_debut.getFullYear();
	
	var date_fin =  new Date (fin);
	var f_dd=date_fin.getDate();
	var f_mm=date_fin.getMonth();
	var f_yy=date_fin.getFullYear();
	


	var date = new Date();
    var c_dd= date.getDate();
    var c_mm= date.getMonth();
    var c_yy= date.getFullYear();

	
	if((c_yy > d_yy || c_yy > f_yy) )
	{  alert("Vous avez choisis une année dans le passé!");return false; }

    if((c_yy < d_yy || c_yy < f_yy) )
	{  return true; }


	if( (c_mm > d_mm || c_mm > f_mm)){
		
		alert ("Vous avez choisis un mois dans le passé"); return false;
	}
	if( (c_mm < d_mm || c_mm < f_mm)){
		
		return true;
	}
	
	if ((c_yy == d_yy) && (c_mm == d_mm ) && (c_mm == f_mm) && (c_yy == f_yy)){
		if( (c_dd > d_dd) || c_dd > f_dd  ) { alert ("Jour dans le passé!");return false;}
	}
	
	return true;

}

</script>

<?php require "footer.php" ?>
