<?php require 'config.php'; ?>
<?php
session_start();

// google client id 	1082983336226-ph2q77qr0crnhgijo1sa9ib5efijsl2h.apps.googleusercontent.com
// google client secret 	j6EK8cNcaeanb2vgr4_7BTo-


if(isset($_POST['reg'])){
	
	if(isset($_POST['ch'])){
	//session_start() creates a session or resumes the current one based on a session identifier passed via a GET or POST request, or passed via a cookie.
	
	// spremanje podataka dobivenih $_POST['podatak']; metodom u varijable
	$email= $_POST['inputEmail'];
	$uname= $_POST['username'];
	$sifra= $_POST['inputPws'];
	$sifra2= $_POST['inputConfirmPws'];
	
	// provjera passworda i confirm passworda
	if ( $sifra == $sifra2){
	
	// hashiranje  string password_hash ( string $password , integer $algo [, array $options ] )
	// PASSWORD_BCRYPT - Use the CRYPT_BLOWFISH algorithm to create the hash. This will produce a standard crypt() compatible hash using the "$2y$" identifier.
	// The result will always be a 60 character string, or FALSE on failure.
	$PWhash = password_hash($sifra, PASSWORD_BCRYPT, array ('cost' => 10 ));
	
	// Performs a query on the database
	$sql = mysqli_query ($conn, "select id from korisnik where email = '".$email."'  ");
		// Returns an associative array that corresponds to the fetched row or NULL if there are no more rows.
		if ( mysqli_fetch_assoc($sql) != 0)
		{
			// treba zamjeniti na neki normalan nacin
			echo "<br><br>User with that e-mail already exists";
		}else{
			// spremam u varijablu random broj pomocu funkcije rand();
			$confirmcode = rand();
			// hashirana sifra
			$sql2 = mysqli_query($conn, "INSERT INTO korisnik (email, sifra, ime, status, confirmcode)VALUES ('$email', '$PWhash', '$uname', '0', '$confirmcode')");
			
			// isto kao i za echo
			echo "<Account created successfully!!)";
			//poruka koju saljemo na mail
			$message = "
			Account activation - 
			username = $uname; password = $sifra
			Click the link below to verify your account
			http://www.todolista.esy.es/emailconfirm.php?email=$email&username=$uname&code=$confirmcode
			";
			// slanje maila pomocu mail funkcije
			mail($email,"$uname Account confirmation",$message,"From: noreply@exevio.com");
			echo "Registration complete! Please activate your email.";
			$_SESSION['forgot']='reg';
		}
		
	}else{
	echo "Password and Confirm password are not the same!";
	}
	
	}else{
		echo "Checkbox is not checked";
	}
	
}

?>


<?php 

	
		session_start();
	
  if(isset($_POST['Update']))
  {
	  $PW = $_POST['oldPassword'];
	  
	  if (password_verify($PW, $_SESSION["UserPW"]) ) {
		$UpdatePW = $_POST['newPassword'];
		$UpdatePW2 = $_POST['newPassword2'];
		if ( $UpdatePW == $UpdatePW2 ) {
			
		$id = $_SESSION["UserID"];	
		$PWhash = password_hash($UpdatePW, PASSWORD_BCRYPT, array ('cost' => 10 ));
		$sql = mysqli_query($conn, "UPDATE korisnik SET  sifra = '".$PWhash."' where id = '".$id."' ");
		//echo "uspjeh, nova sifra je  ".$UpdatePW." ";
		$_SESSION['forgot']='testa';
		// brisanje session varijable -- zaobilazni nacin updateanja vrijednosti varijable
		unset($_SESSION['UserPW']);
		// ubacivanje nove vrijednosti u prethodno izbrisanu varijablu
		$_SESSION['UserPW'] = $PWhash;
		 $_SESSION['ulogiran']='test';
		
		 
		}else{
			
			echo "New password and Confirm new password are not the same";
			// da ostane logged in jer nemam bolje rjesenje zasad
			$result = mysqli_query($conn, "SELECT * FROM korisnik where email='".$id."' ");
			
		}
		
	  }else{
		  echo "Incorrect Old Password";
	  }
  }
	
?>
 <?php
if(isset($_POST['forgo'])){
	
$email = $_POST['email3'];


$result = mysqli_query($conn, "SELECT * FROM korisnik where email='".$email."' ");
		// mysqli_fetch_array() is an extended version of the mysqli_fetch_row() function. In addition to storing the data in the numeric indices of the result array,
		// the mysqli_fetch_array() function can also store the data in associative indices, using the field names of the result set as keys.
		$row = mysqli_fetch_array($result,MYSQLI_BOTH);
		

		$_SESSION["UserID"] = $row[0];
		$_SESSION["UserEmail"] = $row[1];
		$_SESSION["UserPW"] = $row[2];
		$_SESSION["UserName"] = $row[3];
		$id = $_SESSION["UserID"];
		$uname = $_SESSION["UserName"];
		
		
		if ( $id )
		{
			$_SESSION['forgot']='test';
			
			$sentsifra = substr(GeraHash($id), 5, 10);
			$PWhash = password_hash($sentsifra, PASSWORD_BCRYPT, array ('cost' => 6 ));
			
			$sql = mysqli_query($conn, "UPDATE korisnik SET  status = '1', confirmcode = '0', sifra ='".$PWhash."'  where id = '".$id."' ");
			
			$message = "Your new password is  $sentsifra";
			
			mail($email,"$uname Forgotten password",$message,"From: noreply@exevio.com");
			//echo "$uname password has been sent to your mail, $sentsifra";
			
		}else{
			//echo "neki fail";
			
		}
		
		
		
}

?>


<?php 
// funkcija za random hashiranje i resetiranje sifre - nasao na netu
// http://php.net/manual/en/function.rand.php
function GeraHash($qtd){ 
//Under the string $Caracteres you write all the characters you want to be used to randomly generate the code. 
$Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789'; 
$QuantidadeCaracteres = strlen($Caracteres); 
$QuantidadeCaracteres--; 

$Hash=NULL; 
    for($x=1;$x<=$qtd;$x++){ 
        $Posicao = rand(0,$QuantidadeCaracteres); 
        $Hash .= substr($Caracteres,$Posicao,1); 
    }
return $Hash; 
} 
//Here you specify how many characters the returning string must have 
//echo GeraHash(10); 
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>JustDoIT</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--bootstrap-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	
	<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
    <!--alert-->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-growl/1.0.0/jquery.bootstrap-growl.min.js"></script>

    <!--style-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

	<!--old crap-->
	
	  
  
    <link rel="stylesheet" type="text/css" href="style3.css">
	
	<meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="1082983336226-ph2q77qr0crnhgijo1sa9ib5efijsl2h.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
	

	
    <!--show/hide pass-->

    <script>
        $(document).ready(function() {
            $("#password_show_button").click(function() {
                $("#test1").attr("type", "password");
            });
            $("#password_show_button").mousedown(function() {
                $("#test1").attr("type", "text");
            });
        });
    </script>

</head>

<body>


    <div id="page">

        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
                 </button>
                    <a class="navbar-brand" href="main/index.html">JustDoIT</a>
                </div>
					
	<!--notifikacija-->

<script type="text/javascript">
	 function popic(){
		$.bootstrapGrowl('You are logged in!', {
			type: 'success',
			delay: 8000,
		});
	};
	function popic2(){
		$.bootstrapGrowl('Password has been reset, and sent to your mail!', {
			type: 'warning',
			delay: 10000,
		});
	};
	function popic3(){
		$.bootstrapGrowl('Password has been succesfully updated!', {
			type: 'danger',
			delay: 10000,
		});
	};
	function popic4(){
		$.bootstrapGrowl('You have logged out', {
			type: 'warning',
			delay: 10000,
		});
	};
	function popicAktiv(){
		$.bootstrapGrowl('Account has not been activated yet! Please go to your mail for activation link.', {
			type: 'warning',
			delay: 10000,
		});
	};
	function popic5(){
		$.bootstrapGrowl('You have created new account!', {
			type: 'success',
			delay: 15000,
		});
		$.bootstrapGrowl('Please go to your email to verify it', {
			type: 'danger',
			delay: 15000,
		});
	};

	
</script>

				
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav navbar-left">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="main/index.html">To-Do</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#" data-toggle="modal" id="t1" data-target="#myModal"><span class="glyphicon glyphicon-log-in"></span> Login</a> </li>
                        <li><a href="#" data-toggle="modal" id="t2" data-target="#myModal2"><span class="glyphicon glyphicon-user"></span> Sign Up</a> </li>
						
							
							<?php
					
					
					// 		<<<<<---- LOGIN PHP KOD ---->>>>>
					// 		<<<<<---- LOGIN PHP KOD ---->>>>>
					
	
	if(isset($_POST['Login']) ){
		
		$EM = $_POST['email2'];
		$PW = $_POST['pws'];
		
		
		$result = mysqli_query($conn, "SELECT * FROM korisnik where email='".$EM."' ");
		// mysqli_fetch_array() is an extended version of the mysqli_fetch_row() function. In addition to storing the data in the numeric indices of the result array,
		// the mysqli_fetch_array() function can also store the data in associative indices, using the field names of the result set as keys.
		$row = mysqli_fetch_array($result,MYSQLI_BOTH);
		// spremamo u varijablu status korisnika
		$status = $row[4];
		
		// provjeravamo jeli korisnik aktiviran
		
		
		// Verifies that the given hash matches the given password.
		if (password_verify($PW, $row['sifra'])) {	
		
			if ($status == '1'){
			//session_start() creates a session or resumes the current one based on a session identifier passed via a GET or POST request, or passed via a cookie.
			session_start();
		
		$_SESSION["UserID"] = $row[0];
		$_SESSION["UserEmail"] = $row[1];
		$_SESSION["UserPW"] = $row[2];
		$_SESSION["UserName"] = $row[3];
		//echo "<span id='".qwer."'> E-mail is already used.</span>"; 	TEST
		
		
		?>
	
		 <li><a href="#" id="t3" ><span class="glyphicon glyphicon-user"></span> <? printf ( $_SESSION["UserName"]); ?></a> </li>
		 <li><a href="#" id="t4" data-toggle="modal" data-target="#myModal3"><span class="glyphicon glyphicon-edit"></span> Update</a> </li>
		 <li><a href="logout.php" ><span class="glyphicon glyphicon-log-out"></span> Log-out</a> </li>
		<script> document.getElementById("t1").style.display="none";
				 document.getElementById("t2").style.display="none";
				 
			
			 popic(); 
		
				

		</script>
		<?
			}else{
				$_SESSION['forgot']='aktiv';
				//echo "Account has not been activated yet! Please go to your mail for activation link.";
				}
			
		}else{
			session_start();

			 echo "Failed to connect to MySQL: " . mysqli_connect_error();	
		}
	
	}
?>

<?php


			// 		<<<<<---- LOGIN PHP KOD NAKON UPDATEA ---TEST ---->>>>>
			// 		<<<<<---- LOGIN PHP KOD NAKON UPDATEA ---TEST ---->>>>>
	
	while(isset($_SESSION['ulogiran'])){

		$EM=$_SESSION["UserEmail"];
		$result = mysqli_query($conn, "SELECT * FROM korisnik where email='".$EM."' ");
		// mysqli_fetch_array() is an extended version of the mysqli_fetch_row() function. In addition to storing the data in the numeric indices of the result array,
		// the mysqli_fetch_array() function can also store the data in associative indices, using the field names of the result set as keys.
		$row = mysqli_fetch_array($result,MYSQLI_BOTH);
		
		?>
	
		 <li><a href="#" id="t3" ><span class="glyphicon glyphicon-user"></span> <? printf ( $_SESSION["UserName"]); ?></a> </li>
		 <li><a href="#" id="t4" data-toggle="modal" data-target="#myModal3"><span class="glyphicon glyphicon-edit"></span> Update</a> </li>
		 <li><a href="logout.php" ><span class="glyphicon glyphicon-log-out"></span> Log-out</a> </li>
		<script> document.getElementById("t1").style.display="none";
				 document.getElementById("t2").style.display="none";
				

		</script>
		<?
		$_SESSION['forgot']='testa';
	unset($_SESSION['ulogiran']);
	}
?>

						</ul>

            </div>
        </nav>
		
        <!--Modal update-->
	<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    <h4 class="modal-title" id="myModalLabel2">Update form</h4>
                </div>
                <div class="modal-body">
                    <form action="" method="post" >
						
						
                        <div class="form-group">
                            <label for="email">Old Password</label>
                            <div class="input-group pb-modalreglog-input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                <input type="password" required="required" class="form-control" name="oldPassword" id="oldPassword" placeholder="Old Password">

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <div class="input-group pb-modalreglog-input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                                <input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="New Password" pattern=".{8,20}" required title="8 to 20 characters" >
                            </div>
                        </div>
						<div class="form-group">
                            <label for="password">Confirm New Password</label>
                            <div class="input-group pb-modalreglog-input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                                <input type="password" class="form-control" name="newPassword2" id="newPassword2" placeholder="Confirm New Password" pattern=".{8,20}" required title="8 to 20 characters" >
                            </div>
							<span id="verifynote2" class="hidden" > Passwords do not match</span>
                        </div>
                   
				</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" name="Update" id="Update" class="btn btn-primary">Update</button>
						
					</div>
					</form>
            </div>
        </div>
    </div>
	<!-- provjera passworda -->
										<script type="text/javascript">
								$(document).ready(function() {
									$('#newPassword2').keyup(function() {
										if( $(this).val() == $('#newPassword').val()){
											$('#verifynote2').addClass('hidden');
										}else{
											$('#verifynote2').removeClass('hidden');
										}
									
									});
								
								});
								
								</script>



        <!-- Modal Login-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    <h4 class="modal-title" id="myModalLabel">Login form</h4>
                </div>
                <div class="modal-body">
                    <form action="" method="post" >
						
						
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <div class="input-group pb-modalreglog-input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                <input type="email" required="required" class="form-control" name="email2" id="email2" placeholder="Email">

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group pb-modalreglog-input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                                <input type="password" required="required" class="form-control" name="pws" id="pws" placeholder="Password">
								<!-- working show / hide password -->
								<button type="button" id="eye" onclick="if(pws.type=='text')pws.type='password'; else pws.type='text';"> toggle </button>
								
								<span style="width:0%" id="password_show_button" class="input-group-addon"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
								<!--			WORKING rjesenje 2.0 --- biraj sta ti vise odgovara gogi
								
											<input type="password" placeholder="Password" id="pwd" class="masked" name="password" />
			<button type="button" id="eye">
				<img src="https://cdn0.iconfinder.com/data/icons/feather/96/eye-16.png" alt="eye" />
			</button>
								
													<script>
											
												

					function show() {
						var p = document.getElementById('pwd');
						p.setAttribute('type', 'text');
					}

					function hide() {
						var p = document.getElementById('pwd');
						p.setAttribute('type', 'password');
					}

					var pwShown = 0;

					document.getElementById("eye").addEventListener("click", function () {
						if (pwShown == 0) {
							pwShown = 1;
							show();
						} else {
							pwShown = 0;
							hide();
						}
					}, false);
											</script>
											
											-->
								
								
                            </div>
                        </div>
						
						
						<div class="form-group">
                                    <a data-toggle="modal" data-target="#myModal5" data-dismiss="modal"> Forgot your password? </a>
                                </div>
								 <div class="form-group">
                                  <a data-toggle="modal" data-target="#myModal2" data-dismiss="modal"> Don't have an account yet? Register here</a>
                                </div>
								
                   
				</div>
					<div class="modal-footer">
					<div style="float:left">
						<!--<div class="fb-login-button" data-max-rows="1" data-size="medium" data-button-type="login_with" data-show-faces="false" data-auto-logout-link="true" data-use-continue-as="true"></div>
						Google</a> --></div>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" name="Login" id="Login" class="btn btn-primary">Log in</button>
						
					</div>
					</form>
            </div>
        </div>
    </div>

        <!--Modal Registracija-->
        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form class="pb-modalreglog-form-reg" action="" method="post">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title text-center" id="myModalLabel">Registration</h4>
                        </div>
                        <div class="modal-body">


                            <div class="form-group">
                            <label for="email">Email address</label>
                            <div id="frmCheckUsername" class="input-group pb-modalreglog-input-group">
							
						
							
                                <span class="input-group-addon" ><span class="glyphicon glyphicon-user"></span></span>
								<input type="email" name="inputEmail" id="inputEmail" class="form-control" onBlur="checkAvailability()" placeholder="Email" 
								pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required="required" title="Incorret e-mail format, 'example@yahoo.com' "> 
                            </div>
							<p><img src="ikona.gif" id="loaderIcon" style="display:none"  <!-- animacija   treba namjestit na sredinu  -->
							<span id="user-availability-status"></span>   <!-- text optinal na sredinu -->

                        </div>
                        <div class="form-group">
                            <label for="username">Nickname</label>
                            <div class="input-group pb-modalreglog-input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                <input type="text" class="form-control"  name="username" id="username" placeholder="Nickname" pattern="^[a-zA-Z0-9-_\.]{5,20}$" type="text" required="required" 
								title="Minimum 5 characters. Only numbers and letters">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group pb-modalreglog-input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                                <input type="password" class="form-control" name="inputPws" id="inputPws" placeholder="Password"  pattern=".{8,20}" required title="8 to 20 characters" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirmpassword">Confirm password</label>
                            <div class="input-group pb-modalreglog-input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
								<input type="password" class="form-control" name="inputConfirmPws" id="inputConfirmPws" placeholder="Confirm Password" pattern=".{8,20}" required title="8 to 20 characters" >
								
                            </div>
							<span id="verifynote" class="hidden" > Passwords do not match</span>
                        </div>

                            <div class="form-group">
                                <input type="checkbox" id="ch" required name="ch"> I'm not a robot.
                            </div>
							<div class="form-group">
                                    <a data-toggle="modal" data-target="#myModal5" data-dismiss="modal"> Forgot your password? </a>
                                </div>
                           
								<div class="form-group">
                               <a data-toggle="modal" data-target="#myModal" data-dismiss="modal">  Already have an account? Login here </a>
                            </div>

                        </div>
						
										<!-- provjera passworda -->
										<script type="text/javascript">
								$(document).ready(function() {
									$('#inputConfirmPws').keyup(function() {
										if( $(this).val() == $('#inputPws').val()){
											$('#verifynote').addClass('hidden');
										}else{
											$('#verifynote').removeClass('hidden');
										}
									
									});
								
								});
								
								</script>
								

                        <div class="modal-footer">
                            <div style="float:left">
								<!--<div class="fb-login-button" data-max-rows="1" data-size="medium" data-button-type="login_with" data-show-faces="false" data-auto-logout-link="true" data-use-continue-as="true"></div>
								Google</a>--></div> 
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="reg" id="reg" class="btn btn-primary">Sign up</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
		
		
		
		
		
		
		<!--Modal forgot password-->
				
				
				
		<div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
					<h4 class="modal-title" id="myModalLabel">Forgotten password form</h4>
				</div>
				<div class="modal-body">
					<form action="" method="post" >
						
						
						<div class="form-group">
							<label for="email">Email address</label>
							<div class="input-group pb-modalreglog-input-group">
								<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
								<input type="email" required="required" class="form-control" name="email3" id="email3" placeholder="Email">

							</div>
						</div>
						
						
								<div class="form-group">
								  <a data-toggle="modal" data-target="#myModal2" data-dismiss="modal"> Don't have an account yet? Register here</a>
								</div>
								<div class="form-group">
							   <a data-toggle="modal" data-target="#myModal" data-dismiss="modal">  Already have an account? Login here </a>
							</div>
				   
				</div>
					<div class="modal-footer">
					<div style="float:left">
						<div class="fb-login-button" data-max-rows="1" data-size="medium" data-button-type="login_with" data-show-faces="false" data-auto-logout-link="true" data-use-continue-as="true"></div>
						Google</a></div>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" name="forgo" id="forgo" class="btn btn-primary">Submit</button>
						<?php
						if ($_SESSION['forgot']=="test"){
							?>
							<script>
						popic2();
						</script>
						
						<?php
							
							
						}else if ($_SESSION['forgot']=="testa"){
							?>
							<script>
						popic3();
						</script>
						
						<?php
							
						}else if ($_SESSION['forgot']=="beta"){
							?>
							<script>
						popic4();
						</script>
						
						<?php
							
						}else if ($_SESSION['forgot']=="reg"){
							?>
							<script>
						popic5();
						</script>
						
						<?php
							
						}else if ($_SESSION['forgot']=="aktiv"){
							?>
							<script>
						popicAktiv();
						</script>
						
						<?php
							
						}else{
							
						}
						unset($_SESSION['forgot']);
							
						?>
						
					</div>
					</form>
			</div>
		</div>
	</div>


        <!--naslovslika-->
        <div class="jumbotron" id="box-wrapper">

            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 wow bounceInLeft">
                        <h1>To do list</h1>
                        <p> The best app of all time. Never forget about anything ever again. This app will change your life forever. Now available on your phone, pc or tablet.</p>
                        <button class="btn btn-lg btn-primary prvib">Download App</button>
                        <button class="btn btn-lg plavi">Download Extension</button>
                    </div>

                </div>
            </div>

        </div>
        <!--prvi paragraf-->
        <div class="prvi">
            <div class="container">

                <div class="row" style="margin-bottom:60px">
                    <div class="col-md-6 col-md-offset-3 text-center">
                        <h2>Simple yet elegant</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6 text-center">
                        <div class="funkcije">
                            <span class="icon">
                        <i class="fa fa-globe " ></i>
                    </span>
                            <div class="desc">
                                <h3>Plan for everything</h3>
                                <p>Make your life easier with this simple app. Never forget important things again.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 text-center">
                        <div class="funkcije">
                            <span class="icon">
                        <i class="fa fa-calendar-check-o" ></i>
                    </span>
                            <div class="desc">
                                <h3>Due date</h3>
                                <p>Set a due date and get reminded so you dont miss your tasks.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 text-center">
                        <div class="funkcije">
                            <span class="icon">
                        <i class="fa fa-history" ></i>
                    </span>
                            <div class="desc">
                                <h3>History</h3>
                                <p>See what tasks you have already done. Dont do them again or do.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 text-center">
                        <div class="funkcije">
                            <span class="icon">
                        <i class="fa fa-list" ></i>
                    </span>
                            <div class="desc">
                                <h3>Categories</h3>
                                <p>Place your tasks in different categories. Manage every aspect of your life.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 text-center">
                        <div class="funkcije">
                            <span class="icon">
                        <i class="fa fa-map-marker" ></i>
                    </span>
                            <div class="desc">
                                <h3>Maps</h3>
                                <p>You want to set your task on a map. We got you covered.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 text-center">
                        <div class="funkcije">
                            <span class="icon">
                        <i class="fa fa-desktop" ></i>
                    </span>
                            <div class="desc">
                                <h3>Themes</h3>
                                <p>Choose from a variety of different themes.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 text-center">
                        <div class="funkcije">
                            <span class="icon">
                        <i class="fa fa-sign-in" ></i>
                    </span>
                            <div class="desc">
                                <h3>Sign up</h3>
                                <p>Sign up with us and be part of the growing community.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 text-center">
                        <div class="funkcije">
                            <span class="icon">
                        <i class="fa fa-globe" ></i>
                    </span>
                            <div class="desc">
                                <h3>Everywhere</h3>
                                <p>Access our app from anywhere.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--drugi paragraf-->
        <div class="drugi">
            <div class="container">
                <div class="row">
                    <div class="col-sm-5 text-center">
                        <h2>Get started it's free</h2>
                    </div>
                    <div class="cold-sm-5 text-center botuni">
                     <!--  <div class="fb-login-button" data-max-rows="1" data-size="large" data-button-type="login_with" data-show-faces="false" data-auto-logout-link="true" data-use-continue-as="true"></div> -->

                        <button class="loginBtn google">Login with Google
                        </button>
                        <button class="login2" data-toggle="modal" data-target="#myModal2"><span class="glyphicon glyphicon-user"></span> Sign Up</button>

                    </div>

                </div>
            </div>
        </div>

        <!--team paragraf 3-->

        <div id="team" class="team">
            <div class="container">
                <div class="row text-center">
                    <div class="col-sm-8 col-sm-offset-2">
                        <h2 class="title-one">Meet The Team</h2>

                        <p>The best team out there.</p>
                    </div>
                </div>

                <div class=" col-md-3">
                    <div class="single-team">
                        <img class="img-circle slikamem" src="slika1.jpg" />
                        <h3>Marina Bašić</h3>
                        <h4>CEO</h4>
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod</p>

                    </div>
                </div>
                <div class=" col-md-3">
                    <div class="single-team">
                        <img class="img-circle slikamem" src="slika1.jpg" alt="team member" />
                        <h3>Roberto Bubalo</h3>
                        <h4>CEO</h4>
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod</p>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="single-team">
                        <img class="img-circle slikamem" src="slika1.jpg" alt="team member" />
                        <h3>Andrea Paljuh</h3>
                        <h4>CEO</h4>
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod</p>

                    </div>
                </div>
                <div class=" col-md-3">
                    <div class="single-team">
                        <img class="img-circle slikamem " src="slika1.jpg" alt="team member" />
                        <h3>Goran Zorkić</h3>
                        <h4>CEO</h4>
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismodsed diam nonummy</p>

                    </div>
                </div>
            </div>
        </div>

        <!--broj-->

        <div class="brojac">
            <div class="container">


                <div class="row">
                    <div class="col-md-3 col-sm-6 text-center bootcamp">
                        <i class="fa fa-users fa-4x"></i>
                        <div class="desc">
                            <h1>20</h1>
                            <h3>People </h3>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 text-center bootcamp">
                        <i class="fa fa-clock-o fa-4x"></i>
                        <div class="desc">
                            <h1>500</h1>
                            <h3>Work hours </h3>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 text-center bootcamp">
                        <i class="fa fa-code-fork fa-4x"></i>
                        <div class="desc">
                            <h1>5</h1>
                            <h3>Projects</h3>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 text-center bootcamp">
                        <i class="fa fa-handshake-o fa-4x"></i>
                        <div class="desc">
                            <h1>1</h1>
                            <h3>Demo day </h3>
                        </div>
                    </div>
                </div>

            </div>
        </div>



        <!--Testemonials-->
        <section id="carousel">
            <div class="container">
                <div class="row" style="margin-bottom:30px; margin-top:-40px;">
                    <div class="col-md-6 col-md-offset-3 text-center">
                        <h2>Recommended by</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">

                        <div class="carousel slide" id="fade-quote-carousel" data-ride="carousel" data-interval="5000">
                            <!-- Carousel indicators -->
                            <ol class="carousel-indicators">
                                <li data-target="#fade-quote-carousel" data-slide-to="0"></li>
                                <li data-target="#fade-quote-carousel" data-slide-to="1"></li>
                                <li data-target="#fade-quote-carousel" data-slide-to="2" class="active"></li>
                                <li data-target="#fade-quote-carousel" data-slide-to="3"></li>
                                <li data-target="#fade-quote-carousel" data-slide-to="4"></li>
                                <li data-target="#fade-quote-carousel" data-slide-to="5"></li>
                            </ol>
                            <!-- Carousel items -->
                            <div class="carousel-inner">
                                <div class="item">
                                    <div class="profile-circle slikapoz" style="background-image:url(slika1.jpg);"></div>
                                    <blockquote><i class="fa fa-quote-left fa-3x" aria-hidden="true"></i>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus.</p>
                                        <small>Jana</small>
                                    </blockquote>
                                </div>
                                <div class="item">
                                    <div class="profile-circle slikapoz" style="background-image:url(slika1.jpg);"></div>
                                    <blockquote><i class="fa fa-quote-left fa-3x" aria-hidden="true"></i>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus.</p>
                                        <small>Lionel Messi</small>
                                    </blockquote>
                                </div>
                                <div class="active item">
                                    <div class="profile-circle slikapoz" style="background-image:url(slika1.jpg);"></div>
                                    <blockquote><i class="fa fa-quote-left fa-3x" aria-hidden="true"></i>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus.</p>
                                        <small>Donald Trump</small>
                                    </blockquote>
                                </div>
                                <div class="item">
                                    <div class="profile-circle slikapoz" style="background-image:url(slika1.jpg);"></div>
                                    <blockquote><i class="fa fa-quote-left fa-3x" aria-hidden="true"></i>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus.</p>
                                        <small>Rihanna</small>
                                    </blockquote>
                                </div>
                                <div class="item">
                                    <div class="profile-circle slikapoz" style="background-image:url(slika1.jpg);"></div>
                                    <blockquote><i class="fa fa-quote-left fa-3x" aria-hidden="true"></i>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus.</p>
                                        <small>Ivo Sanader</small>
                                    </blockquote>
                                </div>
                                <div class="item">
                                    <div class="profile-circle slikapoz" style="background-image:url(slika1.jpg);"></div>
                                    <blockquote><i class="fa fa-quote-left fa-3x " aria-hidden="true"></i>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus.</p>
                                        <small>Michael Jackson</small>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--footer-->
        <div class="container footer">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="col-xs-6 col-sm-3"><img src="exevio1.png" /></div>
                    <div class="col-xs-6 col-sm-3"><img src="ctk.png" /></div>
                    <div class="col-xs-6 col-sm-3 duo"><img src="stepri.png" /></div>


                </div>


            </div>
        </div>





    </div>
	


<!-- automatska provjera emaila-->
<script>
function checkAvailability() {
	$("#loaderIcon").show();
	jQuery.ajax({
	url: "check_availability.php",
	data:'email='+$("#inputEmail").val(),
	type: "POST",
	success:function(data){
		$("#user-availability-status").html(data);
		$("#loaderIcon").hide();
	},
	error:function (){}
	});
}
</script>
	
	

<script>
	//facebook login *****
	  <script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '665616430302213',
      xfbml      : true,
      version    : 'v2.9'
    });
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
	
	
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.9&appId=665616430302213";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<script>
  logInWithFacebook = function() {
    FB.login(function(response) {
      if (response.authResponse) {
        alert('You are logged in &amp; cookie set!');
        // Now you can redirect the user or do an AJAX request to
        // a PHP script that grabs the signed request from the cookie.
      } else {
        alert('User cancelled login or did not fully authorize.');
      }
    });
    return false;
  };
  window.fbAsyncInit = function() {
    FB.init({
      appId: '665616430302213',
      cookie: true, // This is important, it's not enabled by default
      version: 'v2.9'
    });
  };

  (function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
</body>

</html>
