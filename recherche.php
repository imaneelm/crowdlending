<?php require "header.php" ?>

<body>
	<section>
		<h1> Rechercher un objet </h1>
		<form action="recherche.php"  method="get">
			<p>
				Rechercher un objet en fonction de :
			</p>

			<p>
				Son nom :
				<input type="text" name="nom">

				Son état :
				<select name="etat">
					<option value="">Aucun</option>
					<option value="1">Bon</option>
					<option value="2">Pas mal</option>
					<option value="3">Mauvais</option>
				</select>

				Sa catégorie :
				<select name="categorie">
					<option value="">Aucune</option>
					<?php
					$req="SELECT idCategorie, nom FROM Categorie ";
					$res=$bdd->query($req);
					while ($data= $res->fetch())
					{
						echo "<option value='" . $data['idCategorie'] . "'>" . $data['nom'] . "</option>";
					}
					?>
				</select>
				<br>

			Ses dates de disponibilité : </br>
			Date d'emprunt : <input type="date" id="debut" name="dateD" >
			Date de retour : <input type="date" id="fin" name="dateF" >
		</p>

		<p>
			<input type="submit" value="Chercher" />
		</p>
	</form>

	<?php
// Récupération des données du formulaire
	$nom = isset($_GET['nom']) ? $_GET['nom'] : '';
	$etat = isset($_GET['etat']) ? $_GET['etat'] : '';
	$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : '';
	$dateD = isset($_GET['dateD']) ? $_GET['dateD'] : '';
	$dateF = isset($_GET['dateF']) ? $_GET['dateF'] : '';

	setlocale(LC_TIME, "fr_FR", "French");
	$todayy = new DateTime();
	$today = $todayy->format('Y-m-d');

	$flag = true;
	if ($dateD != '' and $dateF != ''){
		$dateD = date('Y-m-d', strtotime($dateD));
		$dateF = date('Y-m-d', strtotime($dateF));
		
			// Tests sur les dates entrées
		if($dateD < $today){
			echo "<script>alert(\"La date de début doit être plus grande ou égale à la date d'aujourd'hui\");</script>";
			echo "<script>window.location.replace(\"recherche.php\");</script>";
			$flag = false;
		}
		if($dateF < $dateD){
			echo "<script>alert(\"La date de retour doit être plus grande à la date d'emprunt\");</script>";
			echo "<script>window.location.replace(\"recherche.php\");</script>";
			$flag = false;
		}
	}

	else if (($dateD != '' and $dateF == '') or ($dateD == '' and $dateF != '')){
		echo "<script>alert(\"Vous devez saisir les deux dates, de début et de fin d'emprunt\");</script>";
		echo "<script>window.location.replace(\"recherche.php\");</script>";
		$flag = false;
	}

		// Recherche des objets s'il n'y a pas de conflits dans les dates entrées
	if($flag == true){
			// Requête avec les filtres demandés
		$req = $bdd->prepare("
			SELECT res.id as idObjet, o.idPossesseur AS idP, o.nom AS nomObjet,
			et.nom AS nomEtat, c.nom AS nomCategorie, m.pseudo AS pseudoPossesseur
			FROM (
			SELECT o.idObjet AS id,
			SUM(IF(idEmprunt IS NULL, 0, '$dateD' <= dateFin AND '$dateF' >= dateDebut AND e.validation = 1)) AS ConflictingReservations
			FROM Objet o LEFT JOIN Emprunt e USING (idObjet)
			GROUP BY idObjet
			HAVING ConflictingReservations = 0) res
			, Objet o, Categorie c, Membre m , Etat et
			WHERE o.idObjet = res.id
			AND o.idCategorie = c.idCategorie AND o.idPossesseur = m.idMembre AND o.idEtat = et.idEtat
				AND (UPPER(o.nom) LIKE CONCAT('%',UPPER(:nom),'%')) -- recherche contenue dans le nom de l'objet
				AND (:etat = '' OR o.idEtat = :etat)
				AND (:categorie = '' OR o.idCategorie = :categorie)");

		$req->execute(array('nom' => $nom,
			'etat' => $etat,
			'categorie' => $categorie));

			// S'il y a des résultats :
		if($req->rowCount() > 0){
			echo"
			<table>
			<tbody>";

				// On affiche chaque objet et ses caractéristiques
			while ($donnees = $req->fetch()){
				echo"
				<tr>
				<td width='25%'>
				";

				$query = $bdd->query("SELECT image FROM Objet WHERE idObjet = " . $donnees['idObjet']);
				if($query->rowCount() > 0){
					while($row = $query->fetch(PDO::FETCH_ASSOC)){
						$imageURL = 'images/objects/'.$row["image"];
				?>
    <div id="profil"><img src="<?php echo $imageURL; ?>" id="profil_image" /><div>
				<?php 	}
					}else{ ?>
    <div id="profil"><image src="images/objects/default.jpg" id="profil_image"><div>
			<?php 	}
				echo"
				</td>
				<td><ul><li><a href='voirObjet.php?id=" .$donnees['idObjet'] . "'>" . $donnees['nomObjet'] . "</a></li><li>
				". $donnees['nomEtat'] . "<//li>
				<li>". $donnees['nomCategorie'] . "</li>
				<li>Possesseur: <a href='voirMembre.php?id=" .$donnees['idP'] . "'>" .$donnees['pseudoPossesseur'] ."</li></td>
				</tr>";
			}
			echo
			"</tbody>
			</table>";
		}

		// S'il n'y a aucun objet
		else{
			echo "Aucun objet trouvé";
		}

		$req->closeCursor();
	}
	?>
</section>

<?php require "footer.php" ?>
