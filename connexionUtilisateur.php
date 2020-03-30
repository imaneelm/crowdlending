<?php
session_start();

// Changement de session
$id = $_GET['id'];
$_SESSION['id'] = $id;

//Redirection vers la page d'accueil
header('Location: index.php');
exit();

?>