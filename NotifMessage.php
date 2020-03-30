<?php require "header.php" ?>
<?php

echo"<section>";


$notifMes = $bdd->prepare('SELECT N.idNotification, N.idRecepteur, N.idEmetteur, N.idMessage, N.dateNotif, N.lu, M.pseudo, Mes.idEmetteur
					FROM NotificationMessage AS N 
					INNER JOIN Membre AS M ON N.idEmetteur = M.idMembre
					INNER JOIN Message AS Mes ON N.idMessage = Mes.idMessage
					WHERE N.idRecepteur = :idMembre
					ORDER BY N.lu, N.dateNotif DESC');

		$notifMes->execute(array('idMembre'=>$_GET['recepteur']));

		if ($notifMes->rowCount() > 0)
		{
			echo "<hr>";
			while($rowMes = $notifMes->fetch())
			{
				echo "Vous avez un <a href=conversation.php?recepteur=".$rowMes['idEmetteur'].">nouveau message</a>
					de <a href=voirMembre.php?id=" . $rowMes['idEmetteur'] . ">" . $rowMes['pseudo'] . "</a> pour cet objet.";
				if (!$rowMes['lu']){
					echo "<form action='NotifMessageLue.php' method='post'>";
					echo "<input type='hidden' name='idNotificationMessage' value='".$rowMes['idNotification']."'>";
					echo "<input type='submit' value='Marquer comme lu' >";
					echo "</form>";
				}
			}
		}
		$notifMes->closeCursor();

echo"</section>";

?>