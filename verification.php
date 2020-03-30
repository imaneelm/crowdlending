<?php require "header.php" ?>
<section>
<?php

    $message='';
    if (empty($_POST['pseudo']) || empty($_POST['password']) ) //Oublie d'un champ
    {
        $message = '<p>une erreur s\'est produite pendant votre identification.
                    Vous devez remplir tous les champs</p>
                    <p>Cliquez <a href="login.php">ici</a> pour revenir</p>';
    }
    
    else 
    {//On check le mot de passe
        $query=$bdd->prepare('SELECT mdp, idMembre, pseudo, actif
        FROM Membre WHERE pseudo = :pseudo');
        $query->bindValue(':pseudo',$_POST['pseudo'], PDO::PARAM_STR);
        $query->execute();
        $data=$query->fetch();
        if ($data['mdp'] == $_POST['password'] && $data['actif'] == '1' ) // Acces OK !
          {
            $_SESSION['pseudo'] = $data['pseudo'];
            $_SESSION['id'] = $data['idMembre'];
            //Redirection vers la page d'accueil
            header('Location: index.php');
            exit();             
            }
        // Acces pas OK !
        elseif ($data['actif'] <> '1') {
                $message = '<p>ce compte n\'est pas activé !! </p>';
            }
        elseif ($data['mdp'] <> $_POST['password']) {
                $message = '<p>Une erreur s\'est produite pendant votre identification.<br /> Le mot de passe ou le pseudo entré n\'est pas correct.</p><p>Cliquez <a href="login.php">ici</a> pour revenir à la page précédente';
            }    

        $query->CloseCursor();
    }
    echo $message ;
?>
</section>
<?php require "footer.php" ?>