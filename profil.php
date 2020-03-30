<?php require "header.php"?>

<section>
	
	<div id="profil"><image src="images/profil.png" id="profil_image"><div>
			
	<nav>
		<ul>
			<li><a href="objetsProposesModif.php"> Mes Objets</a></li>
			<?php
			echo '<li><a href="formEditProfil.php?id=' . $_SESSION['id'] . '"> Modifier mon profil</a></li>'
			?>
			<li><a href="objetsEmpruntes.php"> Les demandes d'emprunt</a></li>
		</ul>
	</nav>

</section>
<?php require "footer.php"?>
