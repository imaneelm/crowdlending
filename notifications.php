<?php require "header.php"; ?>
<section>
	<?php if (isset($_SESSION['id'])){ ?>
		<h1>Notifications</h1>
		<?php

		$notif = $bdd->prepare('SELECT idNotification, O.nom AS nomObjet, M.idMembre, M.pseudo, O.idObjet, T.type, lu, dateNotif
			FROM TypeNotification T, Notification N
				LEFT JOIN Objet O ON N.idObjet = O.idObjet
				LEFT JOIN Membre M ON N.idMembreLien = M.idMembre
			WHERE N.idTypeNotification = T.idTypeNotification
			AND idMembreNotif = :idMembre
			ORDER BY lu, dateNotif DESC');

		$notif->execute(array('idMembre'=>$_SESSION['id']));


		if ($notif->rowCount() > 0)
		{
			echo "<hr>";
			while($row = $notif->fetch()){
				switch($row['type']){
					case "demandeEmprunt" :
					echo "<p> <a href=voirObjet.php?id=" . $row['idObjet'] . ">" . $row['nomObjet'] . "</a> - ";
					echo "Vous avez une nouvelle <a href=objetsEmpruntes.php>demande d'emprunt</a>
					de <a href=voirMembre.php?id=" . $row['idMembre'] . ">" . $row['pseudo'] . "</a> pour cet objet.";
					break;

					case "refusEmprunt" :
					echo "<p> <a href=voirObjet.php?id=" . $row['idObjet'] . ">" . $row['nomObjet'] . "</a> - ";
					echo "Votre demande d'emprunt a été rejetée par <a href=voirMembre.php?id=" . $row['idMembre'] . ">" . $row['pseudo'] . "</a>.";
					break;

					case "validationEmprunt" :
					echo "<p> <a href=voirObjet.php?id=" . $row['idObjet'] . ">" . $row['nomObjet'] . "</a> - ";
					echo "Votre demande d'emprunt a été acceptée par <a href=voirMembre.php?id=" . $row['idMembre'] . ">" . $row['pseudo'] . "</a>.";
					break;

					case "nouveauFavori" :
					echo "<p> <a href=voirObjet.php?id=" . $row['idObjet'] . ">" . $row['nomObjet'] . "</a> - ";
					echo "<a href=voirMembre.php?id=" . $row['idMembre'] . ">" . $row['pseudo'] . "</a> a marqué cet objet comme favori.";
					break;

					case "nouveauCommentaire" :
					echo "<p> <a href=voirObjet.php?id=" . $row['idObjet'] . ">" . $row['nomObjet'] . "</a> - ";
					echo "<a href=voirMembre.php?id=" . $row['idMembre'] . ">" . $row['pseudo'] .  "</a> a laissé un commentaire.";
					break;

					case "objetSupprime" :
					echo "Un de vos objets a été supprimé.";
					break;

					case "avertissement" :
					echo "<p> <a href=voirObjet.php?id=" . $row['idObjet'] . ">" . $row['nomObjet'] . "</a> - ";
					echo "<font color='red'>Attention ! vous avez reçu un avertissement pour cet objet.</font>";
					break;
				}

				if (!$row['lu']){
					echo "<form action='notificationLue.php' method='post'>";
					echo "<input type='hidden' name='idNotification' value='".$row['idNotification']."'>";
					echo "<input type='submit' value='Marquer comme lu' >";
					echo "</form>";
				}
				echo '</p><hr>';
			}
		}
		else{
			echo "Pas de notifications";
		}

		$notif->closeCursor();
	}
	else{
		echo "Veuillez <a href='login.php'>vous connecter</a> pour accéder à cette page.";
	}
	?>
</section>
<?php require "footer.php" ?>
