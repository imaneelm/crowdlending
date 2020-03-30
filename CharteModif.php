<?php require "header.php" ?>
<?php
if(isset($_POST['modif_charte'])){
	
	if(isset($_POST['charte'])){
		
		$file='Charte.html';
		$data=$_POST['charte'];
		$stat=file_put_contents($file, $data);
		if($stat == false){
			echo"<section>";
			echo "<div class=\"objet_emp\" ><image src=\"images/warning.png\" id=\"warning_icon\"> <p>Une erreur s'est produite!</p><div>"; exit();
			echo "</section>";
		}
		else{
			echo"<section>";
			echo "<div class=\"objet_emp\" ><image src=\"images/good.jpg\" id=\"warning_icon\"> <p>La charte a été bien modifié!</p><div>";
			echo"</section>";	
			
		}
		
		
		
	}
	
	
	
}

?>
<?php require "footer.php" ?>
