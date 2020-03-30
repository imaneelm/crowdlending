<?php require "header.php";


$notifMes = $bdd->prepare("UPDATE NotificationMessage SET lu = TRUE 
					WHERE idNotification = :notif");
$notifMes->execute(array('notif' => $_POST['idNotificationMessage']));
header("Location: NotifMessage.php?recepteur=".$_SESSION['id']."");

require "footer.php" ?>