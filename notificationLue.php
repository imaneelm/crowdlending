<?php require "header.php";

$notif = $bdd->prepare("UPDATE Notification SET lu = TRUE WHERE idNotification = :notif AND idMembreNotif = :membre");
$notif->execute(array('notif' => $_POST['idNotification'], 'membre' => $_SESSION['id']));
header("Location: notifications.php");



require "footer.php" ?>