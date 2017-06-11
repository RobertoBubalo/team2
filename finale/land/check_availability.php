<?php
require_once("config.php");


if(!empty($_POST["email"])) {
  $result = mysqli_query($conn,"SELECT count(*) FROM korisnik WHERE email='" . $_POST["email"] . "'");
  $row = mysqli_fetch_array($result,MYSQLI_BOTH);
  $user_count = $row[0];
  if($user_count>0) {
      echo "<span class='status-not-available'> E-mail is already used.</span>";
  }else{
      echo "<span class='status-available'> E-mail Available.</span>";
  }
}
?>
