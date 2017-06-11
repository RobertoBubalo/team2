<?php
// ime varijable = funkcija za spajanje na bazu (adresa, korisnik, sifra, ime baze )
$conn=mysqli_connect("localhost","justdoit_team22","team22", "justdoit_exevio");
// Provjera konekcije
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>