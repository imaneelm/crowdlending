<?php 
require "header.php";

?>

<section>

<form method="post" action="renmdp.php">
	<fieldset>
	<legend>Rénitialiser votre mot de passe</legend>
	
	<p>
    <label for="mail" >mail:</label><input name="mail" type="text" required><br />

	<label for="pseudo">nouveau mot de passe:</label><input name="nvmdp" type="password" id="mail"><br />
    <label for="pseudo">confirmer le mot de passe:</label><input name="c_nvmdp" type="password" id="mail"><br />


	</p>
	
	</fieldset>
	<p><input name="ren" type="submit" value="rénitialiser" id="ren"  /></p>
	</form>

	<a href="formNewAccount.php">Pas encore inscrit ? </a>

</section>
<?php
if (isset ($_POST['ren'])){
	
    $mail = $_POST['mail'];
		
    $mdp = $_POST['nvmdp'];
    $mdp_p = $_POST['c_nvmdp'];


	$ren = $bdd->prepare('SELECT email FROM membre');
	$ren->execute();
 	$n=0;


	while ($mails = $ren->fetch()){

		if($mails['email']==$mail && $mdp == $mdp_p){
			
			$ren1 = $bdd->prepare('UPDATE Membre SET mdp = ? WHERE email=?');
			$ren1->execute(array($mdp,$mail));		$n=1;
			
		}
		
		
	}	
	if ($n==0){
			echo "<div class=\"objet_emp\" ><image src=\"images/warning.png\" id=\"warning_icon\"> <p>Une erreur est survenue!</p><div>";
	}	
	else {
		
			echo "<div class=\"objet_emp\" ><image src=\"images/good.jpg\" id=\"warning_icon\"> <p>Votre mot de passe a été bien modifié!.</p><div>";
	
	}
}



?>



<?php require "footer.php" ?>