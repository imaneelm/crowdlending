<?php require "header.php"?>

<section>
	<h1> Ajouter un commentaire </h1>

  <?php
	if (isset($_GET['id'])){
    $id = $_GET['id'];
    echo '<form method="post" ><input type="text" id="comment" size="60" name="comment" value="" placeholder="Commentez l\'état de l\'objet reçu " /> <input type="submit" name="submit" id="submit" value="Ajouter" /> </form>';
    if(isset($_POST['submit'])) {
      $datee = new DateTime();
	    $datee = $datee->format('Y-m-d h:i');
      //$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = $bdd->prepare("SELECT commentaire, date_commentaire FROM Emprunt WHERE idEmprunt = ?");
      $sql->execute(array($id));
      $previous = $sql->fetch();
      $date = $datee."!$".$previous['date_commentaire'];
      $comment = $_POST['comment']."!$".$previous['commentaire'];
      //echo $comment;
      $query = $bdd->prepare("UPDATE Emprunt SET commentaire = ? , date_commentaire = ? WHERE idEmprunt = ? ");
      if ($query->execute(array($comment, $date, $id))) {
        echo "<script type= 'text/javascript'>alert('Votre commentaire est bien ajouté.');</script>";
				echo "<script>window.location.replace(\"mesEmprunts.php\");</script>";
      }
      else{
        echo "<script type= 'text/javascript'>alert('Un problème est survenu, Veuillez réessayer une autre fois.');</script>";
        echo "<script>window.location.replace(\"mesEmprunts.php\");</script>";
      }
    }
  }
  ?>
</section>



<?php require "footer.php" ?>
