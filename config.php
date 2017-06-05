<?php
// ime varijable = funkcija za spajanje na bazu (adresa, korisnik, sifra, ime baze )
$conn=mysqli_connect("localhost","u691530715_team2","team22", "u691530715_team2");
// Provjera konekcije
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>