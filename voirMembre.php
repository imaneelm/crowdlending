<?php require "header.php" ?>

<section>
	<?php
		if (isset($_GET['id'])){
			$idMembre = $_GET['id'];
			
			$query = $bdd->query("SELECT image FROM Membre WHERE idMembre = " . $idMembre);
			if($query->rowCount() > 0){
				while($row = $query->fetch(PDO::FETCH_ASSOC)){
					$imageURL = 'images/users/'.$row["image"];
	?>
    
	<div id="profil"><img src="<?php echo $imageURL; ?>" id="profil_image" /><div>

	<?php 	}
		}else{ ?>
    
	<div id="profil"><image src="images/users/profil.png" id="profil_image"><div>
	
	<?php 	}

      $rqt = $bdd -> prepare("SELECT pseudo, email, nom, prenom, telephone
                            FROM Membre
                            WHERE idMembre = ?");
			$rqtScore = $bdd -> prepare("SELECT sum(noteEmprunteur) as note, count(*) as total
																 FROM Emprunt
																 WHERE idEmprunteur = ? and noteEmprunteur > 0 ");
			$rqtObjets = $bdd -> prepare("SELECT o.nom as nomO, o.idCategorie, o.idEtat, c.nom as nomC, e.nom as nomE, o.idObjet as idO
                            FROM Objet o, Categorie c, Etat e
                            WHERE o.idPossesseur = ? AND o.idCategorie = c.idCategorie AND o.idEtat = e.idEtat");

      $rqt->execute([$idMembre]);
			$rqtScore->execute([$idMembre]);
			$rqtObjets->execute([$idMembre]);

			$resu = $rqtScore->fetch();
			if($resu['total'] == 0){
				$score = 0;
			}else{
				$score = ($resu['note'] / $resu['total']) * 100 / 5;
			}


      if ($rqt->rowCount() > 0){

				$res = $rqt->fetch();

				echo '<h1>' . $res['pseudo'] . '</h1>';
				echo "<p>Nom : " . $res['nom'] . "</p>";
				echo "<p>Prénom : " . $res['prenom'] . "</p>";
        echo "<p>Email : <a href='mailto:" .$res['email']. "'>" . $res['email'] . "</a></p>";
        echo "<p>Numéro de téléphone : " . $res['telephone'] . "</p>";
				echo "<p>Score : </p>";
				echo '<p><div class="containerdiv">
					    <div>
					    <img id="userScore" src="https://image.ibb.co/jpMUXa/stars_blank.png" alt="img">
					    </div>
					    <div class="cornerimage" style="width:'.$score.'%;">
					    <img id="userScore" src="https://image.ibb.co/caxgdF/stars_full.png" alt="">
					    </div>
							<div></p>';
				echo "<h1> Objets de ".$res['pseudo']." : </h1>";
				if ($rqtObjets->rowCount() > 0){
					echo "<table>
					<tr>
						<th>Objet</th>
						<th>Catégorie</th>
						<th>Etat</th>
					</tr>";
					while ($objet = $rqtObjets->fetch()) {
						echo"<tr><td><a href='voirObjet.php?id=". $objet['idO'] . "'>".$objet['nomO']."</a></td>";
						echo "<td>".$objet['nomC']."</td>";
						echo "<td>".$objet['nomE']."</td></tr>";
					}
					echo "</table>";
					$rqtObjets->closeCursor();
				}else{
					echo "<p>". $res['pseudo'] ." n\'a aucun objet listé sur le site.</p>";
				}

      }
      else{
        echo "L'utilisateur n'existe pas.";
      }
    }
    else{
      echo "Lien invalide.";
    }
		$rqtScore->closeCursor();
		$rqt->closeCursor();
  ?>
</section>

<?php require "footer.php" ?>
