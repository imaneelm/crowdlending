


<?php require "header.php" ?>

<section>

	<form method="post" action="verification.php">
	<fieldset>
	<legend>Connexion</legend>
	<p>
	<label for="pseudo">Pseudo :</label><input name="pseudo" type="text" id="pseudo" required><br />
	<label for="password">Mot de Passe :</label><input type="password" name="password" id="password" required><br>
	<a href ="renmdp.php">Mot de passe oublié?</a>

	</p>
	</fieldset>
	<p><input type="submit" value="Connexion" /></p>
	</form>

	<a href="formNewAccount.php">Pas encore inscrit ? </a>

</section>

<?php require "footer.php" ?>