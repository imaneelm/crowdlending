<?php require "header.php" ?>
<section>
<h1>Charte d'utilisation</h1>
<?php $ch = file_get_contents('Charte.html',true);?>
<form method = "POST" action="CharteModif.php">
  <textarea id="charte" name="charte" rows="50" cols="100"><?php echo $ch;?></textarea><br>
   <input type="submit" value="modifier" name="modif_charte">
</form>
</section>

</section>
<?php require "footer.php" ?>
