<?php require "header.php"; ?>

<script type="text/javascript">
	//get id of  the star which the user put the mouse over that and change stars colors
   function change(id)
   {
      var ab=document.getElementById(id+"_hidden").value;
      document.getElementById("phprating").value=ab;

      for(var i=ab;i>=1;i--)
      {
         document.getElementById("php"+i).src="images/star2.png";
      }
      var id=parseInt(ab)+1;
      for(var j=id;j<=5;j++)
      {
         document.getElementById("php"+j).src="images/star1.png";
      }
   }

</script>
<section>
	<h1> Mes Objets Prêtés </h1>

	<form action="validationEmprunt.php" method="post" id="emprunt_form">
	<table>
	<tr>
		<th>Objet</th>
		<th>Date début</th>
		<th>Date de retour</th>
		<th>Emprunteur</th>
		<th>Statut</th>
		<th>Sélection</th>
		<th>Evaluation</th>

	</tr>

	<?php
	// Récupération des objets de l'utilisateur
		$emprunts = $bdd->prepare('SELECT Objet.idObjet, Objet.nom AS nomObjet, Membre.pseudo,
		-- DATE_FORMAT(Emprunt.dateDebut, \'%d / %m / %Y\') AS dateDebut,
		-- DATE_FORMAT(Emprunt.dateFin, \'%d / %m / %Y\') AS dateFin,
		Emprunt.dateDebut AS dateDebut,
		Emprunt.dateFin AS dateFin,
		Emprunt.idEmprunteur, Emprunt.validation, Emprunt.idEmprunt, Emprunt.noteEmprunteur as note

		FROM Emprunt
		INNER JOIN Objet ON Emprunt.idObjet=Objet.idObjet
		INNER JOIN Membre ON Emprunt.idEmprunteur=Membre.idMembre
		WHERE Objet.idPossesseur=:idPossesseur
		ORDER BY validation');
	$emprunts->execute(array('idPossesseur'=>$_SESSION['id']));

	while ($mesEmprunts=$emprunts->fetch())
	{
		echo"<tr><td><a href='voirObjet.php?id=". $mesEmprunts['idObjet'] . "'>". $mesEmprunts['nomObjet']."</a></td>";
		echo"<td>".$mesEmprunts['dateDebut']."</td>";
		echo"<td>".$mesEmprunts['dateFin']."</td>";
		echo"<td><a href='voirMembre.php?id=" . $mesEmprunts['idEmprunteur'] . "'>".$mesEmprunts['pseudo']."</a></td>";
		if($mesEmprunts['validation']=="0"){
			echo"<td>Non Validé</td>";
			echo"<td><input type='radio' name='selection' onchange='disable_reclame();' value='".$mesEmprunts['idEmprunt']."'></td>";
		}
		else if($mesEmprunts['validation']=="1"){
			echo"<td>Validé</td>";
			if($mesEmprunts['dateFin']< date('Y-m-d')){

			 echo"<td><input type='radio' name='selection' onchange='enable_reclame();' value='".$mesEmprunts['idEmprunt']."'></td>";
		 //juste pour ne pas avoir un tableau déformé
		 }else{
			 echo "<td></td>";
		 }
		}
		else if($mesEmprunts['validation']=="2"){
			echo"<td>Rejeté</td>";
		}

		//evaluate after object is returned
		setlocale(LC_TIME, "fr_FR", "French");
		$todayy = new DateTime();
		$today = $todayy->format('d / m / Y');

		//display score if it is not null
		if($mesEmprunts['note'] != 0){
			$img="";
			$i=1;
			while($i<=$mesEmprunts['note']){
			$img=$img."<img id='scoreExist' src=images/star2.png >";
			$i=$i+1;
			}
			while($i<=5){
			$img=$img."<img id='scoreExist' src=images/star1.png >";
			$i=$i+1;
			}
			echo "<td>".$img."</td>";
		}
		//possibility to evaluate if score is null
		if(($mesEmprunts['note'] == 0) && ($mesEmprunts['dateFin'] < date('Y-m-d')) && ($mesEmprunts['validation']=="1")){
			echo "<td><a href='objetsEmpruntes.php?idEmp=" . $mesEmprunts['idEmprunt'] . "'>Evaluer</a></td>";
		}

		echo"</tr>";
		}
	$emprunts->closeCursor();

	?>
	</table>

    <br>
    <input type='hidden' name='comment_rec' id='comment_rec'>

	<input id="val" type="submit" name="validation" value="Valider" disabled/>
	<input id="ruf" type="submit" name="refus" value="Refuser" disabled/>
	<input id="rec" type="submit" name="reclame" value="Réclamer" disabled/>



	</form>

<!-- evaluation -->
<?php
if(isset($_GET['idEmp'])){
	$id = $_GET['idEmp'];
	$emprunt = $bdd->prepare('SELECT m.pseudo as pseudo, o.nom as nomObjet, DATE_FORMAT(e.dateDebut, \'%d / %m / %Y\') as dateDebut
														FROM Emprunt e, Objet o, Membre m
														WHERE e.idEmprunt =:id AND e.idObjet = o.idObjet AND m.idMembre = e.idEmprunteur');
	$emprunt->execute(array(':id'=> $id));
	$infos = $emprunt->fetch(PDO::FETCH_BOTH);
	?>
	<form method="post" action="">
		<fieldset>
			<legend><h2>Evaluer <?php echo $infos['pseudo']; ?> pour l'emprunt de <?php echo $infos['nomObjet']; ?> à la date de <?php echo $infos['dateDebut']; ?> </h2></legend>

			<div class="div">
			  <input type="hidden" id="php1_hidden" value="1">
			  <img src="images/star1.png" onmouseover="change(this.id);" id="php1" >
			  <input type="hidden" id="php2_hidden" value="2">
			  <img src="images/star1.png" onmouseover="change(this.id);" id="php2" >
			  <input type="hidden" id="php3_hidden" value="3">
			  <img src="images/star1.png" onmouseover="change(this.id);" id="php3" >
			  <input type="hidden" id="php4_hidden" value="4">
			  <img src="images/star1.png" onmouseover="change(this.id);" id="php4" >
			  <input type="hidden" id="php5_hidden" value="5">
			  <img src="images/star1.png" onmouseover="change(this.id);" id="php5" >
  	</div>
		<input type="hidden" name="phprating" id="phprating" >
		<input type="submit" value="Submit" name="submit_rating">
		</fieldset>

	</form>

	<?php
	// store score given by user
	if(isset($_POST['submit_rating']))
	{
		$insert = $bdd->prepare("UPDATE Emprunt SET noteEmprunteur = ? WHERE idEmprunt = ?");
		if(!$insert->execute(array($_POST['phprating'], $id))){
			echo '<script>alert("Un problème est survenu, veuillez réessayer ultérieurement.")</script>';
			echo "<script>window.location.replace(\"objetsEmpruntes.php\");</script>";
		}else{
			echo '<script>alert("Votre évaluation est bien enregistrée")</script>';
			echo "<script>window.location.replace(\"objetsEmpruntes.php\");</script>";
		}
	}

	}

	?>


</section>

<script>
function enable_reclame() {
  document.getElementById("rec").disabled = false;
  document.getElementById("val").disabled = true;
  document.getElementById("ruf").disabled = true;
  comment=prompt('motif de réclamation:');
  document.getElementById("comment_rec").value = comment;


}
function disable_reclame() {
  document.getElementById("rec").disabled = true;
  document.getElementById("val").disabled = false;
  document.getElementById("ruf").disabled = false;
}

</script>

<?php require "footer.php" ?>
