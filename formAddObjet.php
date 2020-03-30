<?php 
	/*
	*	Formulaire d'ajout d'objet
	*	Créé par Rkhissi Maha
	*	Dernière modification : 30/10/2019
	*/
?>
<?php require "header.php" ?>
<?php
	// Récupérer les états depuis la DB
	$response = $bdd->query('SELECT * FROM Etat');
	/*
	*	Construire un tableau d'états
	*	chaque ligne récupérée avec '$response->fetch();' est de la forme: array([idEtat] => #valeurId, [nom] => #nomEtat)
	*	
	*	But : avoir un tableau des états de la forme: 
	*		$etats = array(
	*						[0] => array([idEtat] => 1, [nom] => 'Bon'),
	*						[1] => array([idEtat] => 2, [nom] => 'Pas mal'),
	*						[2] => array([idEtat] => 3, [nom] => 'mauvais')
	*					)
	*/
	$etats[] = $response->fetch();
	while($donneesEtat = $response->fetch()){
		$etats[] = $donneesEtat;
	}
	$response->closeCursor();
	//print_r(etats);
	
	// Récupérer les catégories depuis la DB
	$response = $bdd->query('SELECT * FROM Categorie');
	/*
	*	Construire un tableau de catégories
	*	même raisonnement que les états
	*/
	$categories[] = $response->fetch();
	while($donneesCategorie = $response->fetch()){
			$categories[] = $donneesCategorie;
	}
	$response->closeCursor();
	//print_r(categories);
	
	if(isset($_SESSION['id']) && $row['banni'] == 0){ 
?>
<section>
	<form name="Form" action="addObjet.php" method="post" onsubmit="return validateForm()" enctype='multipart/form-data'> 
		<fieldset>
			<legend><h1>Ajouter un objet</h1></legend>
			<div>
				<label>Nom :</label>
				<input type="text" name="nomObjet" required/>
			</div>
			<div>
				<label>Catégorie :</label>
				<select name="categorieObjet" >
					<?php
						// Pour chaque catégorie récupérée, ajouter une option dans la liste. 
						// Les ids et noms de catégories se trouvent dans le tableau $categories. Voir plus haut.
						foreach($categories as $element){
								echo '<option value="' . $element['idCategorie'] .'">' . $element['nom'] . '</option>';
						}
					?>
				</select>
			</div>
			<div>
				<label>Etat :</label>
			</div>
			<br />
			<div style="margin-left: 15px;">
				<?php
					// ce $i servira à séléctionner le premier radio button au chargement de la page.
					$i = 1;
					// Pour chaque état récupéré, afficher un radio button. 
					// Les ids et noms d'états se trouvent dans le tableau $etats. Voir plus haut.
					foreach($etats as $element){
						echo '<input type="radio" name="etatObjet" value="' . $element['idEtat'] .'" id="' . $element['idEtat'] . '"';
						// si premier radio button = checked!
						if($i == 1) echo ' checked="true"';
						echo ' />'
								. '<label for="' .$element['idEtat'] . '">' . $element['nom'] . "</label> </br>";
						$i++;
					}
					
				?>
			</div>
			<br />
			<div>
				<label>Photo</label>
				<input type='file' name='file' />
			<div>
		</fieldset>
		<div>
			<input type="submit" value="Valider" />
		</div>
	</form> 
</section>

<script type="text/javascript">
	// script vérification champ nom n'est pas vide avant validation du formulaire
	function validateForm(){
		var nom = document.forms["Form"]["nomObjet"].value;
		if(nom == null || nom == ""){
			alert("Veuillez saisir le nom de l'objet!");
			return false;
		}
	}
</script>

<?php
}
elseif (!isset($_SESSION['id'])){
	echo "<section><p><a href='login.php'>Se connecter</a> pour ajouter un objet.</p></section>";
}
else{
	echo "<section><p>Vous avez été banni, vous ne pouvez plus ajouter d'objet.</p></section>";	
}
require "footer.php" ?>
