<?php require 'config.php'; ?>
<?php
session_start();

$email = $_GET['email'];
$username = $_GET['username'];
$code = $_GET['code'];

$result = mysqli_query($conn, "SELECT * FROM korisnik where email='".$email."' ");
		// mysqli_fetch_array() is an extended version of the mysqli_fetch_row() function. In addition to storing the data in the numeric indices of the result array,
		// the mysqli_fetch_array() function can also store the data in associative indices, using the field names of the result set as keys.
		$row = mysqli_fetch_array($result,MYSQLI_BOTH);
		

		$_SESSION["UserID"] = $row[0];
		$_SESSION["UserEmail"] = $row[1];
		$_SESSION["UserPW"] = $row[2];
		$_SESSION["UserName"] = $row[3];
		$dbcode = $row['confirmcode'];
		$id = $_SESSION["UserID"];
		
		
		if ( $dbcode == $code)
		{
			echo "$username your account has been activated";
			$sql = mysqli_query($conn, "UPDATE korisnik SET  status = '1', confirmcode = '0' where id = '".$id."' ");
		}else{
			echo "$username your account has already been activated!";
		}

	//	$_SESSION['ulogiran']='true';  		ne funkcionira iz nekog razloga
		
		$link_address = 'http://todolista.esy.es/';
		
		echo "<br><a href='".$link_address."'>Homepage</a>";
?>