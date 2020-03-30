<?php
	/*
	*	Formulaire d'inscription
	*	Créé par Rkhissi Maha
	*	Dernière modification : 05/11/2019
	*/
?>
<?php require "header.php" ?>
<section>
	<form  name="Form" action="addProfil.php" method="post" onsubmit="return validateForm()">
		<fieldset>
			<legend><h1>Créer un nouveau compte</h1></legend>
			<div>
				<label>Nom</label>
				<input type="text" name="nomUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorNom">*</span>
			</div>
			<div>
				<label>Prénom</label>
				<input type="text" name="prenomUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorPrenom">*</span>
			</div>
			<div>
				<label>Email</label>
				<input type="text" name="emailUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorEmail">*</span>
			</div>
			<div>
				<label>Pseudo</label>
				<input type="text" name="pseudoUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorPseudo">*</span>
			</div>
			<div>
				<label>Adresse</label>
				<input type="text" name="adresseUtilisateur" style="width : 400px;" required/>
				<span style="color : red;" id="errorAdresse">*</span>
			</div>
			<div>
				<label>Téléphone</label>
				<input type="text" name="telephoneUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorTelephone">*</span>
			</div>
			<div>
				<label>Mot de passe</label>
				<input type="password" name="mdpUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorMdp">*</span>
			</div>
			<div>
				<label>Confirmer le mot de passe</label>
				<input type="password" name="confirmMdpUtilisateur" style="width : 150px;" required/>
				<span style="color : red;" id="errorConfirm">*</span>
			</div>
			<div>
                 <input type="checkbox" name="agree" value="agree" REQUIRED> J'accepte les <a href="Charte.html" target="_blank">conditions d'utilisation</a>. <br>
			</div>

		</fieldset>
		<div>
			<input type="submit" value="Enregistrer" style="border-color : skyBlue; background-color : skyBlue; border-radius : 5px;"/>
		</div>
	</form>
</section>

<script type="text/javascript">
	// script validation formulaire avant envoi
	function validateForm(){
		// reset error messages in case user submit the form a second time
		document.getElementById("errorMdp").innerHTML = "*";
		document.getElementById("errorConfirm").innerHTML = "*";
		document.getElementById("errorEmail").innerHTML = "*";
		document.getElementById("errorTelephone").innerHTML = "*";

		var mdp = document.forms["Form"]["mdpUtilisateur"].value;
		var confirm = document.forms["Form"]["confirmMdpUtilisateur"].value;
		var email = document.forms["Form"]["emailUtilisateur"].value;
		var tel = document.forms["Form"]["telephoneUtilisateur"].value;


		if(mdp != confirm){
			document.getElementById("errorMdp").innerHTML = "* Les mots de passe sont différents";
			return false;
		}

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
