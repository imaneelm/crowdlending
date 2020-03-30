<?php require "header.php" ?>

<section>
	<h1> Formulaire de modification d'objet </h1>

	<form action="insertionModifObjet.php" method="post" enctype='multipart/form-data'> 

	<?php  
	if (isset($_POST['objetModif'])){	
		$objet = $bdd->prepare('SELECT * FROM Objet WHERE idPossesseur=:idPossesseur AND idObjet=:idObjet');
		$objet->execute(array('idPossesseur'=>$_SESSION['id'],
							  'idObjet'=>$_POST['objetModif']));
		$donneesObjet = $objet->fetch();
		$objet->closeCursor(); 
	}

	if (isset($_POST['objetModif'])) { ?> 

	<div>
		<label>Nom :</label>
		<input type="text" name="nomObjet" value="<?php echo $donneesObjet['nom'] ?>" />
	</div>
	<div>
		<label>Etat :</label>
		<select name="etatObjet">
			<?php 
		  	$req="SELECT idEtat, nom FROM Etat ";
		  	$res=$bdd->query($req);
		  	while ($data= $res->fetch())
		  	{?>
		  		<?php if ($donneesObjet['idEtat']!=$data['idEtat']) { ?> 
		  			<option value="<?php echo $data['idEtat'];?>" ><?php echo $data['nom'];?></option> <?php } ?>
		  		<?php if ($donneesObjet['idEtat']==$data['idEtat']) { ?> 
		  			<option value="<?php echo $data['idEtat'];?>" selected><?php echo $data['nom'];?></option> <?php } ?>
		  	<?php	   
		  	}
			?>
		</select>
	</div>
	<div>
		<label>Catégorie :</label>
		<select name="categorieObjet" >
			<?php 
			  	$req="SELECT idCategorie, nom FROM Categorie ";
			  	$res=$bdd->query($req);
			  	while ($data= $res->fetch())
			  	{?>
			  		<?php if ($donneesObjet['idCategorie']!=$data['idCategorie']) { ?> 
			  			<option value="<?php echo $data['idCategorie'];?>" ><?php echo $data['nom'];?></option> <?php } ?>
			  		<?php if ($donneesObjet['idCategorie']==$data['idCategorie']) { ?> 
			  			<option value="<?php echo $data['idCategorie'];?>" selected><?php echo $data['nom'];?></option> <?php } ?>
			  	<?php	   
			  	}
			?>
		    
		</select>
	</div>
		<div>
				<label>Photo</label>
				<input type='file' name='file' />
			<div>
			<div><label></label>
				<?php
					$query = $bdd->query("SELECT image FROM Objet WHERE idObjet = " . $_POST['objetModif']);
					if($query->rowCount() > 0){
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$imageURL = 'images/objects/'.$row["image"];
				?>
					<div id="profil"><img src="<?php echo $imageURL; ?>" id="profil_image" /><div>
				<?php 	}
					}else{ ?>
					<div id="profil"><image src="images/objects/profil.png" id="profil_image"><div>
				<?php 	} ?>
			</div>
		<p>
			<?php echo '<input type="hidden" name="idModif" value="',$_POST['objetModif'],'">'; ?>
		</br>
		<input type="submit" value="Valider" />
		</br>
		</p>

		<?php 
	}
	else {
		echo '<input type="hidden" name="idSup" value="',$_POST['objetSup'],'">'; 
		echo 'Voulez-vous vraiment supprimer cet objet ?</br>';?>
		<input type="submit" value="Valider" />
	<?php } ?> 
		
	</form> 
</section>

<?php require "footer.php" ?>