<?php require "header.php"?>

<section>
<?php
	$query = $bdd->query("SELECT image FROM Membre WHERE idMembre = " . $_SESSION['id']);
	if($query->rowCount() > 0){
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$imageURL = 'images/users/'.$row["image"];
?>
    <div id="profil"><img src="<?php echo $imageURL; ?>" id="profil_image" /><div>
<?php 	}
	}else{ ?>
    <div id="profil"><image src="images/users/profil.png" id="profil_image"><div>
<?php 	} ?>

				
	<nav>
		<ul>
			<li><a href="objetsProposesModif.php"> Mes objets</a></li>
			<li><a href="mesEmprunts.php"> Mes emprunts</a></li>
			<li><a href="mesFavoris.php"> Mes Favoris</a></li>
			<li><a href="objetsEmpruntes.php"> Les demandes d'emprunt</a></li>
			<li><a href="formEditProfil.php"> Modifier mon profil</a></li>
		</ul>
	</nav>

</section>
<?php require "footer.php"?>
