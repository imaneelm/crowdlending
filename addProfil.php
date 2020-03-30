<?php require "header.php" ?>

<section>
	<?php
		// Insertion dans DB
		$insertQuery = $bdd->prepare('INSERT INTO Membre(pseudo, email, mdp, nom, prenom, adresse, telephone, idRole,actif) 
		VALUES(:pseudo, :email, :mdp, :nom, :prenom, :adresse, :telephone, 2,0)'); 
		$insertQuery->execute(array(
			':pseudo' => $_POST['pseudoUtilisateur'],
			':email' => $_POST['emailUtilisateur'],
			':mdp' => $_POST['mdpUtilisateur'],
			':nom' => $_POST['nomUtilisateur'],
			':prenom' => $_POST['prenomUtilisateur'],
			':adresse' => $_POST['adresseUtilisateur'],
			':telephone' => $_POST['telephoneUtilisateur']
		));
			// Génération aléatoire d'une clé
			$cle = md5(microtime(TRUE)*100000);

			// Insertion de la clé dans la bd
			$stmt = $bdd->prepare("UPDATE Membre SET cle=:cle WHERE pseudo like :pseudo");
			$stmt->bindParam(':cle', $cle);
			$stmt->bindParam(':pseudo', $_POST['pseudoUtilisateur']);
			$stmt->execute();
			 
			 
			// Préparation du mail contenant le lien d'activation
			$destinataire = $_POST['emailUtilisateur'];
			$sujet = "Activer votre compte" ;
			$entete = "From: inscription@EmpruntSite.com" ;

			// Le lien d'activation est composé du login(log) et de la clé(cle)
			$message = 'Bienvenue sur Empruntsite,
			 
			Pour activer votre compte, veuillez cliquer sur le lien ci dessous
			ou copier/coller dans votre navigateur internet.
			 
			http://votresite.com/vation.php?log='.urlencode($_POST['pseudoUtilisateur']).'&cle='.urlencode($cle).'
			 
			 
			---------------
			Ceci est un mail automatique, Merci de ne pas y répondre.';
			 
			mail($destinataire, $sujet, $message, $entete) ; // Envoi du mail

			echo '<section><h1>Un mail d\'activation de compte a été envoyé !</h1>';
			echo 'Veuillez confirmer votre compte pour pouvoir utiliser la plateforme!</section>';
			
		 	
	?>
</section>

<?php require "footer.php" ?>