<?php require "header.php" ?>
<?php

echo"<section>";

	$id_user=$_SESSION['id'];

	$req = $bdd->prepare('
				SELECT idRec As idR,reponse, m.pseudo AS possesseur, motif,r.idEmprunt,e.idObjet, o.nom AS objet_n, e.dateDebut AS date_D, e.dateFin AS date_f
				from Reclamation r 
				JOIN Membre m ON
				r.idPossesseur = m.idMembre AND r.idEmprunteur=?
				JOIN Emprunt e ON
				r.idEmprunt = e.idEmprunt
				Join Objet o ON
				e.idObjet = o.idObjet');			

	$req -> execute(array($id_user));
	$nb = $req -> rowCount();
	
	echo "<h1> Reclamations </h1>";
	echo"<hr>";
	
	if($nb == 0){
	echo "<p> Vous n'avez reçu aucune réclamation.</p>";	
		
	}

	while ($donnees = $req->fetch()){
		
		$idR=$donnees['idR'];
		echo "<p> Votre emprunt de l'objet <B>".$donnees['objet_n']." </B> du <b>".$donnees['date_D'] ."</b> au <b>".$donnees['date_f']." </b>a été réclamé par son possesseur <B> (".$donnees['possesseur'].").</b><br> <b>motif : </b>".$donnees['motif']."</p>";

	  if($donnees['reponse']==null){	
			echo "<a href=\"NotifReclamation?idR=$idR\">Répondre</a>";
		
	  }
	  else{ 
	  echo "<p><i>Vous avez répondu à cette réclamation</i></p>";}
		echo"<hr>";
		
		
	}
	
	
if(isset($_GET['idR'])){

	echo"<div>";
	echo"<form method =\"post\" action=\"\">";
	echo"<textarea placeholder=\"tapez votre réponse ...\" Rows = '10' cols ='70' name='reponse_t'></textarea><br>";
	echo"<input type=\"submit\" value=\"Répondre\" name=\"reponse\">";	
	echo"</div>";

}
if(isset($_POST['reponse'])){
	
	 $req1 = $bdd->prepare('UPDATE reclamation SET reponse = ? WHERE idRec=?');
    $etat= $req1 -> execute(array($_POST['reponse_t'],$_GET['idR']));
	if($etat){
		
		echo '<script>alert("Votre réponse a été bien envoyé à l\'administrateur")</script>';
		
	}
	
	
}
echo "</section>";
?>
<?php require "footer.php"?>
