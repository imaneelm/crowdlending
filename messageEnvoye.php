<?php require "header.php"; ?>


<?php
if (isset($_POST['message']))
{
	$message = $bdd->prepare('INSERT INTO Message (idEmetteur, idRecepteur, Message) VALUES(:idEmetteur, :idRecepteur, :Message)');
	$message->execute(array(':idEmetteur' => $_SESSION['id'], ':idRecepteur' => $_POST['recepteur'], ':Message' => $_POST['message'] ));
}
	header('Location: conversation.php?recepteur='.$_POST['recepteur'].'');
?>