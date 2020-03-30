<?php require "header.php" ?>

<section>

	<p>
	<?php

	// Nombre de notifications 
	$nbNotifMes = $bdd->prepare('SELECT COUNT(*) AS nbNotifMes FROM NotificationMessage WHERE idRecepteur = :idMembre AND lu = FALSE');
	$nbNotifMes->execute(array('idMembre'=>$_SESSION['id']));
	$rowNotifMes = $nbNotifMes->fetch();

	echo '<h1> Messagerie Privée <a href=NotifMessage.php?recepteur='.$_SESSION['id'].'><i class="far fa-envelope-open"></i> (' . $rowNotifMes['nbNotifMes'] . ') </a></h1>';
	
		// Récupération des utilisateurs 
		$recepteurs = $bdd->prepare('SELECT idMembre, pseudo 
								FROM Membre 
								WHERE idMembre != :idMembre');
		$recepteurs->execute(array('idMembre'=>$_SESSION['id'])); 
	?>
	<form action="conversation.php" method="post"> 
		Conversation :  
		<select name="recepteur">
		<?php 
		while ($data = $recepteurs->fetch())
		{ ?>
			<option value="<?php echo $data['idMembre'];?>"><?php echo $data['pseudo'];?></option>;
		<?php 
			var_dump($data['idMembre']) ;
		} 
		?>
		</select>
		<input type=submit value="Nouveau Message"  >
	</form>


	</p>	
</section>