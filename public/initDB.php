<?php

// Diese Datei muss einmal zu Beginn ausgeführt werden, damit die Datenbank mit allen
// notwendigen Tabellen und Einträgen existiert


// Folgende Informationen sind bekannt:
// 
// Datenbankserver:   localhost
// Datenbankname:     mattborn_perschke
// Datenbankuser:     mattborn 
// Datenbankkennwort: u8EYfdwRGlb2AuAg

require 'rb.php';

R::setup('mysql:host=localhost;dbname=mattborn_perschke', 'mattborn', 'u8EYfdwRGlb2AuAg');


//#########  Tabelle 'benutzer' anlegen  ##############

//enthält die vordefinierten Benutzernamen
$benutzerArrayName = [
    0 => "Peter", 
    1 => "Wolf",
];

//enthält die vordefinierten Passwörter
$benuterArrayPasswort = [
    0 => "peter",
    1 => "wolf",
];

//Tabelle anlegen
for ($a=0; $a < 2; $a++) {
    $benutzer = R::dispense('benutzer');

    $benutzer->name = $benutzerArrayName[$a];
    $benutzer->passwort = $benuterArrayPasswort[$a];

    R::store($benutzer);
}


//#########  Tabelle 'aufgabenliste' anlegen  ##############

//enthält die vordefinierten Titel
$aufgabenlisteArrayTitel = [
    0 => "To Do im Juni", 
    1 => "Urlaubsvorbereitung",
];

//enthält die vordefinierten Status
//0 = geschlossen, 1 = offen, 
$aufgabenlisteArrayStatus = [
    0 => 1,
    1 => 0,
];

//Tabelle anlegen
for ($a=0; $a < 2; $a++) {
    $aufgabenliste = R::dispense('aufgabenliste');

    $aufgabenliste->titel = $aufgabenlisteArrayTitel[$a];
    $aufgabenliste->status = $aufgabenlisteArrayStatus[$a];

    //Benutzer der Aufgabenliste zuordnen (1:1)
    $b = R::load('benutzer', ($a+1));
    $aufgabenliste->benutzer = $b;

    R::store($aufgabenliste);
}


//#########  Tabelle 'aufgabe' anlegen  ##############

//enthält die vordefinierten Titel
$aufgabeArrayTitel = [
    0 => "Blumen pflanzen", 
    1 => "Auto waschen",
    2 => "Haare waschen",
    3 => "Neue Socken kaufen",
    4 => "Sonnencreme einpacken",
    5 => "Handy laden",
];

//enthält die vordefinierten Beschreibungen
//0 = geschlossen, 1 = offen, 
$aufgabeArrayBeschreibung = [
    0 => "Damit unser Balkon genau so schön ist, wie der der Nachbarn.", 
    1 => "Das Auto war mal weiß!",
    2 => "Mit kreisenden Bewegungen.",
    3 => "Die alten haben Löcher.",
    4 => "LF 50!!",
    5 => "Sonst geht es aus, bevor die Haustüre zu ist.",
];

//enthält die vordefinierten Zeitpunkte
//0 = geschlossen, 1 = offen, 
$aufgabeArrayZeitpunkt = [
    0 => "22.06.2020", 
    1 => "25.06.2020",
    2 => "26.06.2020",
    3 => "04.08.2020",
    4 => "04.08.2020",
    5 => "05.08.2020",
];

//enthält die vordefinierten Status
//1 = offen, 2 = in Bearbeitung, 3 = erledigt, 4 = verspätet erledigt, 5 = abgebrochen
$aufgabeArrayStatus = [
    0 => 3, 
    1 => 5,
    2 => 4,
    3 => 2,
    4 => 1,
    5 => 1,
];

//enthält die vordefinierten Gewichtungen
// 1 = unwichtig, 2 = weniger wichtig, 3 = normal, 4 = wichtig, 5 = sehr wichtig
$aufgabeArrayGewichtung = [
    0 => 5, 
    1 => 2,
    2 => 1,
    3 => 3,
    4 => 4,
    5 => 5,
];

//Tabelle anlegen
for ($a=0; $a < 6; $a++) {
    $aufgabe = R::dispense('aufgabe');

    $aufgabe->titel = $aufgabeArrayTitel[$a];
    $aufgabe->beschreibung = $aufgabeArrayBeschreibung[$a];
    $aufgabe->zeitpunkt = $aufgabeArrayZeitpunkt[$a];
    $aufgabe->status = $aufgabeArrayStatus[$a];
    $aufgabe->gewichtung = $aufgabeArrayGewichtung[$a];

    // Aufgabe der Aufgabenliste zuordnen (1:n)
    $al = R::load('aufgabenliste', 2);
    if ($a < 3) {
        $al = R::load('aufgabenliste', 1);
    }
    $al->xownAufgabeList[] = $aufgabe;

    // Benutzer der Aufgabenliste zuordnen (1:1)
    $b = R::load('benutzer', 2);
    if ($a < 3) {
        $b = R::load('benutzer', 1);
    }
    $al->benutzer = $b;
    $aufgabe->benutzer = $b;

    $id = R::store($al);   
}


// ########## Zur Kontrolle ausgeben #############

echo "<h3>Tabellen erfolgreich angelegt</h3>";
echo "&nbsp;<br>";

echo "<h3>Benutzer</h3>";
$benutzer = R::findAll('benutzer');
    foreach($benutzer as $b) {
        $b->benutzer;
        echo "Name: " . $b->name . "<br>";
        echo "Passwort: " . $b->passwort . "<br>";
        echo "&nbsp;<br>";
        }

echo "--------------";

echo "<h3>Aufgabenliste</h3>";
$aufgabenliste = R::findAll('aufgabenliste');
    foreach($aufgabenliste as $l) {
        $l->aufgabenliste;
        echo "Titel: " . $l->titel . "<br>";
        echo "Status: " . $l->status . "<br>";
        echo "&nbsp;<br>";
        }

echo "--------------";

echo "<h3>Aufgaben</h3>";
$aufgaben = R::findAll('aufgabe');
    foreach($aufgaben as $a) {
        $a->aufgaben;
    echo "Titel: " . $a->titel . "<br>";
    echo "Beschreibung: " . $a->beschreibung . "<br>";
    echo "Fälligkeit: " . $a->zeitpunkt . "<br>";
    echo "Bearbeitungsstatus: " . $a->status . "<br>";
    echo "Gewichtung: " . $a->gewichtung . "<br>";
    echo "&nbsp;<br>";
}
	
R::close();

?>