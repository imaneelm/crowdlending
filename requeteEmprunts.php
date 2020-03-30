<?php
include('connexionBD.php');
 
$query = $bdd->prepare
('SELECT *
FROM Objet O, Emprunt E
WHERE O.idObjet = E.idObjet
AND O.idObjet = ?
AND (year(E.dateDebut) = ?
OR year(E.dateFin) = ?)
AND (month(E.dateDebut) = ? 
OR month(E.dateFin) = ?)');

$query->execute(array($_GET['id'], $_GET['annee'], $_GET['annee'], $_GET['mois'], $_GET['mois']));
$row = $query->fetch();
 
//echo $row['dateDebut'];
echo "3";
?>