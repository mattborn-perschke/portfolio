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

//Tabelle 'benutzer' anlegen
$benutzer = R::dispense('benutzer');		                    // --> es wird eine Tabelle 'anwender' angelegt [KLEINSCHREIBUNG IST WICHTIG !!!)

$benutzer->name = "Testuser";                                  // --> es wird eine Spalte 'name' mit dem Inhalt "Testuser" erstellt
$benutzer->passwort = "test"; 


//Tabelle 'aufgabenliste' anlegen
$aufgabenliste = R::dispense('aufgabenliste');

$aufgabenliste->titel = "Titel der Aufgabenliste";
$aufgabenliste->status = true;

//Tabelle 'aufgabe' anlegen
$aufgabe = R::dispense('aufgabe');

$aufgabe->titel = "Titel der Aufgabe";
$aufgabe->beschreibung = "Beschreibung der Aufgabe";
$aufgabe->zeitpunkt = "27.05.2020";
$aufgabe->status = 1; //1 = offen, 2 = in Bearbeitung, 3 = erledigt, 4 = verspätet erledigt, 5 = abgebrochen
$aufgabe->gewichtung = 1; // 1 = unwichtig, 2 = weniger wichtig, 3 = normal, 4 = wichtig, 5 = sehr wichtig


// ### Aufgabe der Aufgabenliste zuordnen (1:n) ###

$aufgabenliste->xownAufgabeList[] = $aufgabe;


// ### Person der Aufgabenliste zuordnen (1:1) ###

$aufgabenliste->benutzer = $benutzer;

// ...Aufgabenliste inkl. Person und Aufgabe speichern

$id = R::store($aufgabenliste);   // RedBean untersucht die erstellten Beans und erstellt, falls noch nicht vorhanden
                           // für jedes Bean eine eigene Tabelle. Die Spalten der Tabelen werden durch die
                           // Eigenschaften der Beans festgelegt. Typen werden dabei automatisch erkannt.

// ### Zur Kontrolle wird das eben angelegte Rezept geladen und inklusiv aller verknüpften Daten ausgegeben ###




$aufgabenliste = R::load('aufgabenliste', $id);     // In der Tabelle 'aufgabenliste' wird nach dem Datensatz mit der 'id' $id gesucht.

//Ausgabe der Aufgabenliste

echo "<h3>Aufgabenliste</h3>";
echo "Titel: " . $aufgabenliste->titel . "<br>";
echo "Status: " . $aufgabenliste->status . "<br>";

echo "--------------";

echo "<h3>Person</h3>";
$benutzer = $aufgabenliste->benutzer;
echo "Name: " . $benutzer->name . "<br>";
echo "Kennwort: " . $benutzer->passwort . "<br>";
echo "&nbsp;<br>";

echo "--------------";

echo "<h3>Aufgaben</h3>";
foreach($aufgabenliste->xownAufgabeList as $x) {
    echo "Titel: " . $x->titel . "<br>";
    echo "Beschreibung: " . $x->beschreibung . "<br>";
    echo "Fälligkeit: " . $x->zeitpunkt . "<br>";
    echo "Bearbeitungsstatus: " . $x->status . "<br>";
    echo "Gewichtung: " . $x->gewichtung . "<br>";
    echo "&nbsp;<br>";
}
	

R::close();

?>