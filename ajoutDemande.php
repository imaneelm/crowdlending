<?php
require "connexionBD.php";
require "voirObjet.php";


if(isset($_POST['date_Debut']) && isset($_POST['date_Debut']) && isset($_POST['date_Debut'])){
	
	$dateDebut=$_POST['date_Debut'];
	$dateFin=$_POST['date_Fin'];
	$idObjet=$_POST['objetId'];
	$idEmprunteur=$_SESSION['id'];
//Si la date de fin est antérieur à la date de début.
	if ($dateDebut > $dateFin) 
	{  
		phpAlert("la date d'emprunt ne peut pas être avant la date de retour!"); exit();
		exit();

	}

// select les dates de début et dates de fin des empreint validé de l'objet pour vérifier que pendant les dates choisis l'ojet est disponible.
	$query_dates = $bdd->prepare
	("SELECT idEmprunt, DATE_FORMAT(dateDebut, '%Y-%m-%d') AS dateDebut , DATE_FORMAT(dateFin, '%Y-%m-%d') AS dateFin
		FROM Objet O, Emprunt E
		WHERE O.idObjet = ?
		AND O.idObjet = E.idObjet
		AND validation = '1'
		ORDER BY E.dateDebut");
	$query_dates->execute(array($idObjet));
	$nb=$query_dates->rowCount();

	
	while ($row = $query_dates ->fetch()){
		
		if(($dateDebut >= $row['dateDebut'] && $dateDebut <= $row ['dateFin'])
			|| ($dateFin >= $row['dateDebut'] && $dateFin <= $row ['dateFin']
				|| ($dateDebut <= $row['dateDebut'] &&  $dateFin >= $row ['dateFin'])) ){
							//Si l'objet n'est pas disponible
			phpAlert('Cet objet ne sera pas disponible à cette date! Veuillez choisir une autre date.'); exit();
	}

} 


$reqObjet = $bdd->prepare('INSERT INTO Emprunt (dateDebut, dateFin, idObjet, idEmprunteur, validation) VALUES (?, ?, ?,?,\'0\')');
$reqObjet->execute(array($dateDebut,$dateFin,$idObjet,$idEmprunteur));
//Si la demande a été bien effectué.
phpAlert('Votre demande a été envoyée au possesseur.');
}

else {
	
	phpAlert('Veuillez choisir  des dates pour effectuer un empreint!'); exit();
	
}
?>

<?php
	function phpAlert($msg) {
		echo '<script type="text/javascript">alert("' . $msg . '")</script>';
	}
?>
