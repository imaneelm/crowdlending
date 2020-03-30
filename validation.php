<?php
	/*
	*	Page de la validation de l'adresse mail
	*/
?>
<?php require "header.php" ?>

<?php

	// Récupération des variables nécessaires à l'activation
	$pseudo = $_GET['log'];
	$cle = $_GET['cle'];
	 
	// Récupération de la clé correspondant au $pseudo dans la base de données
	$stmt = $dbh->prepare("SELECT cle,actif FROM Membre WHERE pseudo like :pseudo ");
	if($stmt->execute(array(':login' => $login)) && $row = $stmt->fetch())
	  {
	    $clebdd = $row['cle'];	// Récupération de la clé
	    $actif = $row['actif']; // $actif contiendra alors 0 ou 1
	   }

	    // On teste la valeur de la variable $actif récupéré dans la BD
	if($actif == '1') // Si le compte est déjà actif on prévient
	  {
	     echo "Votre compte est déjà actif !";
	  }
	else // Si ce n'est pas actif
	  {
	     if($cle == $clebdd) // On compare nos deux clés	
	       {
	          // Si elles correspondent on active le compte !	
	          echo "Votre compte a bien été activé !";
	 
	          // La requête qui va passer notre champ actif de 0 à 1
	          $stmt = $dbh->prepare("UPDATE Membre SET actif = 1 WHERE pseudo like :pseudo ");
	          $stmt->bindParam(':pseudo', $pseudo);
	          $stmt->execute();
	       }
	       else // Si les deux clés sont différentes on provoque une erreur...
       {
          echo "Erreur ! Votre compte ne peut être activé...";
       }
  }
 
?>
<?php require "footer.php" ?>