<?php
	/*
	*	Formulaire de modification mot de passe
	*	Créé par Rkhissi Maha
	*	Dernière modification : 30/10/2019
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
	<form  name="Form" action="editPwd.php" method="post" onsubmit="return validateForm()">
		<fieldset>
			<legend><h1>Modifier mon mot de passe</h1></legend>
			<div hidden>
				<input type="text" value="<?php echo $utilisateur['idMembre'] ?>" name="idUtilisateur" style="width : 150px;"/>
			</div>
			<div>
				<label>Ancien mot de passe</label>
				<input type="password" name="oldMdpUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorOldMdp">*</span>
			</div>
			<div>
				<label>Nouveau mot de passe</label>
				<input type="password" name="mdpUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorNewMdp">*</span>
			</div>
			<div>
				<label>Confirmer le mot de passe</label>
				<input type="password" name="confirmMdpUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorConfirm">*</span>
			</div>
		</fieldset>
		<div>
			<input type="submit" value="Enregistrer"/>
		</div>
	</form>
</section>

<script type="text/javascript">
	// script validation formulaire avant envoi
	function validateForm(){
		// reset error messages in case user submit the form a second time
		document.getElementById("errorOldMdp").innerHTML = "*";
		document.getElementById("errorNewMdp").innerHTML = "*";
		document.getElementById("errorConfirm").innerHTML = "*";

		var oldMdp = document.forms["Form"]["oldMdpUtilisateur"].value;
		var newMdp = document.forms["Form"]["mdpUtilisateur"].value;
		var confirm = document.forms["Form"]["confirmMdpUtilisateur"].value;

		if(oldMdp != null || oldMdp != ""){
			if(oldMdp != <?php echo '"' . $utilisateur['mdp'] . '"' ?>){
				document.getElementById("errorOldMdp").innerHTML = "* Le mot de passe ne correspond pas";
				return false;
			}
		}
		if(confirm != null || confirm != ""){
			if(newMdp != confirm){
				document.getElementById("errorNewMdp").innerHTML = "* Les mots de passe sont différents";
				return false;
			}
		}
	}
</script>
