<!DOCTYPE HTML>
<html>
  
  <head>
    <title>Login</title>
  </head>
  
  <body>

<?php

$password = $_POST['password'];
$name = $_POST['name'];

if(isset($_POST['name']) && !empty($_POST['name'])
&& isset($_POST['password']) && !empty($_POST['password'])){

  
  $mysqli = new mysqli('localhost', 'mattborn', 'u8EYfdwRGlb2AuAg', 'mattborn_perschke');
  
  if ($mysqli->error) {
    echo ('Verbindungsfehler (' . $mysqli->errno. '): ' . $mysqli->error);
  } else {

    $sql = "select passwort from anwender where name='$name'";
    
    if  ($result = $mysqli->query($sql)) {
      if ($rowObj = $result->fetch_object()){
        if ($rowObj->passwort == $_POST['password']) {
          echo "Login akzeptiert";
        } else
            echo "Fehlerhafter Login";
      } 
   
  }
}  
  $mysqli->close();

} else {
  echo "Ungültige Daten";
}
?>
 
  </body>
  
</html>

