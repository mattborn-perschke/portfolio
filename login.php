<!DOCTYPE HTML>
<html>
  
  <head>
    <title>Login</title>
  </head>
  
  <body>

<?php

$passwort = $_POST['passwort'];
$name = $_POST['name'];

if(isset($_POST['name']) && !empty($_POST['name'])
&& isset($_POST['passwort']) && !empty($_POST['passwort'])){

  
  $mysqli = new mysqli('localhost', 'mattborn', 'u8EYfdwRGlb2AuAg', 'mattborn_perschke');
  
  if ($mysqli->error) {
    echo ('Verbindungsfehler (' . $mysqli->errno. '): ' . $mysqli->error);
  } else {

    $benutzername = "select name from anwender";
    if  ($result = $mysqli->query($benutzername)) {
      if ($rowObj = $result->fetch_object()){
        if ($rowObj->name == $_POST['name']) {
          
          $sql = "select passwort from anwender where name='$name'";
          if  ($result = $mysqli->query($sql)) {
            if ($rowObj = $result->fetch_object()){
              if ($rowObj->passwort == $_POST['passwort']) {
                header('Location:testseite.html');
                //echo "Login akzeptiert";
              } else
                  echo "Falsches Passwort";
            } 
          } 
        } else  
          echo "Benutzername unbekannt";
      } 
    }
  } 
  
  $mysqli->close();

} else 
  echo "Benutzer oder Kennwort falsch";

?>
 
</body>
  
</html>

