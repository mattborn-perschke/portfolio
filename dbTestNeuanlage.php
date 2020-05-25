<?php

require 'rb.php';

R::setup('mysql:host=localhost;dbname=mattborn_perschke', 'mattborn', 'u8EYfdwRGlb2AuAg');

$a = 2;

while ($a < 7) {

$t = R::dispense('aufgabe');


$t->titel = "Testaufgabe {$a}";
$t->beschreibung = "Dies ist Test {$a}";
$t->gewichtung = 1; // 1 = unwichtig, 2 = weniger wichtig, 3 = normal, 4 = wichtig, 5 = sehr wichtig
$t->zeitpunkt = "27.05.202{$a}";
$t->status = 1; //1 = offen, 2 = in Bearbeitung, 3 = erledigt, 4 = verspätet erledigt, 5 = abgebrochen
$t->aufgabenliste = 1;

$id_t = R::store($t);

$a++;

}

$alle = R::findAll('aufgabe'); 

foreach($alle as $eintrag){
    echo "<br>";
    echo $eintrag ->titel . "<br>";
    echo $eintrag ->beschreibung . "<br>";
    echo $eintrag ->gewichtung . "<br>";
    echo $eintrag ->zeitpunkt . "<br>";
    echo $eintrag ->status . "<br>";
    echo $eintrag ->aufgabenliste . "<hr>";
    } 

    R::close();

?>