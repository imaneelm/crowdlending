<?php require "header.php"; ?>
<?php


if(isset($_GET['recl'])){

  $id = $_GET['recl'];
  echo $id;
  $rec = $bdd->prepare('SELECT r.idEmprunteur as idEmprunteur, r.idEmprunt as idEmprunt, e.idObjet as idObjet
                        FROM Reclamation r, Emprunt e
                        WHERE r.idEmprunt = e.idEmprunt AND idRec = ? ');

  $rec->execute(array($id));
  $infos = $rec->fetch();

  $idEmprunteur = $infos['idEmprunteur'];
  $idObjet = $infos['idObjet'];
  $datee = new DateTime();
  $datee = $datee->format('Y-m-d');
  $idAver = 8;
  $rec->closeCursor();

  $insert_q = $bdd->prepare("INSERT INTO Notification (idMembreNotif, dateNotif, idObjet, idTypeNotification) VALUES (?, ?, ?, ?)");
  $averti = $bdd->prepare('UPDATE Reclamation SET averti = 1 WHERE idRec = ?');
    if ($insert_q->execute(array($idEmprunteur, $datee, $idObjet, $idAver))) {
      $averti->execute(array($id));
      echo "<script type= 'text/javascript'>alert('Avertissemet bien envoye.');</script>";
      echo "<script>window.location.replace(\"ListeReclamations.php\");</script>";
    }
    else{
      echo "<script type= 'text/javascript'>alert('Un problème est survenu, Veuillez réessayer une autre fois.');</script>";
      echo "<script>window.location.replace(\"ListeReclamations.php\");</script>";
    }
  $insert_q->closeCursor();
  $averti->closeCursor();
}


    $req = $bdd->prepare('
			SELECT   m1.pseudo AS emprunteur, m2.pseudo  AS posesseur, motif,reponse, idRec, averti
			FROM Reclamation r
			JOIN Membre m1 ON r.idEmprunteur = m1.idMembre
			JOIN Membre m2 ON r.idPossesseur = m2.idMembre');


	 $req -> execute();
    echo "<section>";
	echo"<h1>Les réclamations </h1>";
	 if($req->rowCount() != 0){
			echo"
			<table>
			<thead>
			<tr>
			<th>Posesseur</th>
			<th>Emprunteur</th>
			<th>motif</th>
			<th>Réponse</th>
			</tr>
			</thead>

			<tbody>";

				// On affiche les réclamations
			while ($donnees = $req->fetch()){
				echo"
				<tr>
				<td>".$donnees['posesseur']."</td>
				<td>". $donnees['emprunteur'] . "</td>
				<td>". $donnees['motif'] . "</td>
				<td>". $donnees['reponse'] . "</td>";
        if($donnees['averti'] == 0){
          echo "<td><a href='ListeReclamations.php?recl=" . $donnees['idRec'] . "'> Avertir </a></td>";
        }else{
          echo "<td> DEJA AVERTI";
        }
				echo "</tr>";
			}
			echo "</tbody></table>";
		}
	echo "</section>";
$req->closeCursor();
?>

<?php require "footer.php"; ?>
