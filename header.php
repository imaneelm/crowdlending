<!DOCTYPE HTML>
<html lang="fr">
<head>
	<title>Prêt d'objets</title>
	<meta charset = "UTF-8">
	<link rel="stylesheet" media="screen" type="text/css" href="style/style.css"/>
	<script src="https://kit.fontawesome.com/d0d59c35f1.js" crossorigin="anonymous"></script>
	<link rel="icon" href="images/favicon.ico" />
</head>

<body>
	<?php
	session_start();

		// Connexion à la base de données
	require_once("connexionBD.php");

		// Récupération du pseudo de l'utilisateur
	if(isset($_SESSION['id'])){
		$query = $bdd->prepare("SELECT pseudo, idRole, banni FROM Membre WHERE idMembre = ?");
		$query->execute(array($_SESSION['id']));
		$row = $query->fetch();
		$query->closeCursor();
	}
	?>

	<header>
		<nav>
			<ul>
				<div>
					<li><a href="index.php"> <i class="fas fa-home"></i> Crowdlending</a></li>
				</div>
				<div>
				<?php
					if(isset($_SESSION['id'])){
						if($row['idRole'] == 1) {


							$nbsign = $bdd->query('SELECT COUNT(*) AS nbSign FROM Objet WHERE signale = 1');
							$rowsign = $nbsign->fetch();
							$nbsign->closeCursor();

							$nbrec = $bdd->query('SELECT COUNT(*) AS nbRec FROM Reclamation');
							$rowrec = $nbrec->fetch();
							$nbrec->closeCursor();

							echo ' <div class="dropdown">
									<button class="dropbtn"><i class="fas fa-cog"></i> Administration</button>
									<div class="dropdown-content">
										<a href="Charte.php"> Charte de l\'utilisation </a>
										<a href="gestionCategories.php"> Gestion catégories </a>
										<a href="ListObjSignale.php"> Objets signalés(' . $rowsign['nbSign'] . ')  </a>
										<a href="ListeReclamations.php"> Reclamations(' . $rowrec['nbRec'] . ')  </a>
									  </div>
									</div> ';
						}
						if($row['idRole'] == 2){

						$id_user=$_SESSION['id'];
						$nbrec1 = $bdd->query("SELECT COUNT(*) AS nb FROM Reclamation WHERE idEmprunteur =".$id_user);
						$rowrec = $nbrec1->fetch();
						echo '<li><a href="NotifReclamation.php"> Reclamations(' . $rowrec['nb'] . ')  </a></li>';
						$nbrec1->closeCursor();

						}
					}
					?>
				</div>
				<div>
					<li><a href="recherche.php"> Rechercher un objet </a></li>
					<li><a href="formAddObjet.php"> Ajouter un objet </a></li>
				</div>

				<div>
					<?php
					if(isset($_SESSION['id'])){
						$nbNotif = $bdd->prepare('SELECT COUNT(*) AS nbNotif FROM Notification WHERE idMembreNotif = :idMembre AND lu = FALSE');
						$nbNotif->execute(array('idMembre'=>$_SESSION['id']));
						$rowNotif = $nbNotif->fetch();
						echo '<li><a href="notifications.php"> <i class="fas fa-bell"></i> (' . $rowNotif['nbNotif'] . ')</a></li>';
						$nbNotif->closeCursor();

						echo '<li><a href="espacePerso.php"> <i class="fas fa-user"></i> ' . $row['pseudo'] . '</a></li>';
						$nbNotifMes = $bdd->prepare('SELECT COUNT(*) AS nbNotifMes FROM NotificationMessage WHERE idRecepteur = :idMembre AND lu = FALSE');
						$nbNotifMes->execute(array('idMembre'=>$_SESSION['id']));
						$rowNotifMes = $nbNotifMes->fetch();
						echo "<li><a title = 'Messagerie' href='messages.php'><i class='fas fa-envelope'></i></i> (" . $rowNotifMes['nbNotifMes'] . ")</a></li>";
						echo "<li><a title = 'Déconnexion' href='deconnexion.php'><i class='fas fa-sign-out-alt'></i></a></li>";
					}
					else{
						echo '<li><a href="login.php"> Se connecter </a></li>';
					}
					?>
				</div>
			</ul>
		</nav>
	</header>
