<?php require 'config.php'; ?>
<?php 

	
		session_start();
		
		$UpdatePW = $_POST['novaSifra'];
		$id = $_SESSION["UserID"];	
		$PWhash = password_hash($UpdatePW, PASSWORD_BCRYPT, array ('cost' => 10 ));
		$sql = mysqli_query($conn, "UPDATE korisnik SET  sifra = '".$PWhash."' where id = '".$id."' ");
		header ('Location: index.php');
		
	
?>