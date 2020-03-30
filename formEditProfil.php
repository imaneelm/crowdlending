<?php
	/*
	*	Formulaire de modification coordonnées utilisateur
	*	Créé par Rkhissi Maha
	*	Dernière modification : 10/12/2019
	*/
?>
<?php require "header.php" ?>

<?php
	if (isset($_SESSION['id']))
	{
		// Récupérer l'utilisateur
		$query = $bdd->prepare
			("SELECT * from Membre WHERE idMembre = ? ");
		$query->execute(array($_SESSION['id']));
		$utilisateur = $query->fetch();
		$query->closeCursor();
	}
	else{ echo "Erreur!";}
?>
<section>
	<form  name="Form" action="editProfil.php" method="post" onsubmit="return validateForm()" enctype='multipart/form-data'>
		<fieldset>
			<legend><h1>Modifier mon profil</h1></legend>
			<div hidden>
				<input type="text" value="<?php echo $utilisateur['idMembre'] ?>" name="idUtilisateur" style="width : 150px;"/>
			</div>
			<div>
				<label>Nom</label>
				<input type="text" value="<?php echo $utilisateur['nom'] ?>" name="nomUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorNom">*</span>
			</div>
			<div>
				<label>Prénom</label>
				<input type="text" value="<?php echo $utilisateur['prenom'] ?>" name="prenomUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorPrenom">*</span>
			</div>
			<div>
				<label>Email</label>
				<input type="text" value="<?php echo $utilisateur['email'] ?>" name="emailUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorEmail">*</span>
			</div>
			<div>
				<label>Pseudo</label>
				<input type="text" value="<?php echo $utilisateur['pseudo'] ?>" name="pseudoUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorPseudo">*</span>
			</div>
			<div>
				<label>Adresse</label>
				<input type="text" value="<?php echo $utilisateur['adresse'] ?>" name="adresseUtilisateur" style="width : 400px;" required/>
				<span style="color : red;" id="errorAdresse">*</span>
			</div>
			<div>
				<label>Téléphone</label>
				<input type="text" value="<?php echo $utilisateur['telephone'] ?>" name="telephoneUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorTelephone">*</span>
			</div>
			<div>
				<label>Photo de profil</label>
				<input type='file' name='file' />
			<div>
			<div><label></label>
				<?php
					$query = $bdd->query("SELECT image FROM Membre WHERE idMembre = " . $_SESSION['id']);
					if($query->rowCount() > 0){
						while($row = $query->fetch(PDO::FETCH_ASSOC)){
							$imageURL = 'images/users/'.$row["image"];
				?>
					<div id="profil"><img src="<?php echo $imageURL; ?>" id="profil_image" /><div>
				<?php 	}
					}else{ ?>
					<div id="profil"><image src="images/users/profil.png" id="profil_image"><div>
				<?php 	} ?>
			</div>
			<div>
				<a href="formChangePwd.php">Modifier mon mot de passe</a>
			</div>
		</fieldset>
		<div>
			<input type="submit" value="Enregistrer"/>
		</div>
	</form>
</section>

<script type="text/javascript">
	// script validation formulaire avant envoi
	// TODO : améliorer code
	function validateForm(){
		document.getElementById("errorEmail").innerHTML = "*";
		document.getElementById("errorTelephone").innerHTML = "*";
		var email = document.forms["Form"]["emailUtilisateur"].value;
		var tel = document.forms["Form"]["telephoneUtilisateur"].value;
		
		
		if(email != null || email != ""){
			if(!emailIsValid(email)){
				document.getElementById("errorEmail").innerHTML = "* Adresse email non valide";
				return false;
			}
		}
		
		if(tel != null || tel != ""){
			if(!telIsValid(tel)){
				document.getElementById("errorTelephone").innerHTML = "* Téléphone non valide";
				return false;
			}
		}
		
	}
	
	function telIsValid (str){
		return /^\d{10}$/.test(str);
	}
	
	function emailIsValid (str) {
		return /\S+@\S+\.\S+/.test(str);
	}
</script>

<?php require "footer.php" ?>