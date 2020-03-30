<?php require "messages.php" ?>

<section>

<?php	
	
	if(isset($_GET['recepteur'])){ $membreMessage = $_GET['recepteur']; }
	// Récupération des 10 derniers messages
	if (isset($_POST['recepteur'])) { $membreMessage = $_POST['recepteur']; }
	// Récupération des 10 derniers messages
	$rep = $bdd->prepare('SELECT Message.idMessage, Message.idEmetteur, Message.idRecepteur, Message.Message, Message.dateMessage, Membre.idMembre, Membre.pseudo 
							FROM Message 
							INNER JOIN Membre ON Message.idEmetteur = Membre.idMembre 
							WHERE (Message.idEmetteur=:idEmetteur1 AND Message.idRecepteur=:idRecepteur1) OR (Message.idEmetteur=:idEmetteur2 AND Message.idRecepteur=:idRecepteur2)
							ORDER BY idMessage DESC LIMIT 0, 10');
	$rep->execute(array('idEmetteur1'=>$_SESSION['id'], 'idRecepteur1'=>$membreMessage, 'idEmetteur2'=>$membreMessage, 'idRecepteur2'=>$_SESSION['id'] )); 
	while ($donnees = $rep->fetch())
	// Affichage de chaque message 
	{
		echo '<p><strong>'.$donnees['pseudo'].'</strong> : '.$donnees['Message'].'        '.$donnees['dateMessage'].'</p>';
	}
	$rep->closeCursor();
?>

	<?php
	// Envoie d'un message 
	if (isset($_POST['recepteur'])) {?>
	<form action="messageEnvoye.php" method="post">
		<input type="hidden" name="recepteur" value=<?php echo $_POST['recepteur']?>>
		<TEXTAREA name="message" cols="30" rows="5"></TEXTAREA></br>
	        <input type="submit" value="Envoyer"/>
	</form>
	<?php } 
	elseif (isset($_GET['recepteur'])) {?>
	<form action="messageEnvoye.php" method="post">
		<input type="hidden" name="recepteur" value=<?php echo $_GET['recepteur']?>>
		<TEXTAREA name="message" cols="30" rows="5"></TEXTAREA></br>
	        <input type="submit" value="Envoyer"/>
	</form>
	<?php } ?>
</section>