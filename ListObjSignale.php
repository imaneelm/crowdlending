
<?php require "header.php" ?>
<?php $req = $bdd->query("
			SELECT o.idObjet AS idObjet, o.nom AS nomObjet, e.nom AS nomEtat, c.nom AS nomCategorie, m.pseudo AS pseudoPossesseur, o.idPossesseur AS idP
			FROM Objet o, Categorie c, Membre m , Etat e
			WHERE o.idCategorie = c.idCategorie AND o.idPossesseur = m.idMembre AND o.idEtat = e.idEtat
				AND o.signale = 1"); ?>
	 

<section>

	<h1>Liste des objets signalés </h1>
	<form action="GestObjSign.php" method="post">
	 	
	<?php if($req->rowCount() > 0){
			echo"
			<table>
			<thead>
			<tr>
			<th>Nom</th>
			<th>Etat</th>
			<th>Catégorie</th>
			<th>Possesseur</th>
			<th>Selectionner</th>
			</tr>
			</thead>

			<tbody>";

				// On affiche les objets
			while ($donnees = $req->fetch()){
				echo"
				<tr>
				<td><a href='voirObjet.php?id=" .$donnees['idObjet'] . "'>" . $donnees['nomObjet'] . "</a></td>
				<td>". $donnees['nomEtat'] . "</td>
				<td>". $donnees['nomCategorie'] . "</td>
				<td><a href='voirMembre.php?id=" .$donnees['idP'] . "'>" .$donnees['pseudoPossesseur'] ."</td>
				<td><input type='radio' name='objetselect' value='".$donnees['idObjet']."'> </td>
				</tr>";
			}
			echo
			"</tbody>
			</table>";
		}
	?>
	
<input type="submit" name="Supprimer" value="Supprimer" />
<input type="submit" name="Ignorer" value="Ignorer" />

</form>	
</section>

