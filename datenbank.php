<?php

require 'rb.php';

R::setup('mysql:host=localhost;dbname=mattborn_perschke', 'mattborn', 'u8EYfdwRGlb2AuAg');

//Tabelle 'anwender' anlegen
$a = R::dispense('anwender');		                    // --> es wird eine Tabelle 'anwender' angelegt [KLEINSCHREIBUNG IST WICHTIG !!!)

$a->name = "Testuser";                                  // --> es wird eine Spalte 'name' mit dem Inhalt "Testuser" erstellt
$a->passwort = "test";  

//Tabelle 'aufgabe' anlegen
$t = R::dispense('aufgabe');

$t->titel = "Testaufgabe";
$t->beschreibung = "Dies ist ein Test";
$t->gewichtung = 1; // 1 = unwichtig, 2 = weniger wichtig, 3 = normal, 4 = wichtig, 5 = sehr wichtig
$t->zeitpunkt = "27.05.2020";
$t->status = 1; //1 = offen, 2 = in Bearbeitung, 3 = erledigt, 4 = verspätet erledigt, 5 = abgebrochen
$t->aufgabenliste = 1;

//Tabelle 'aufgabenliste' anlegen
$l = R::dispense('aufgabenliste');

$l->ersteller = "Testuser";

// ...und speichern
// Ist die Tabelle noch nicht vorhanden, dann wird die Tabelle hier angelegt.
// Anschließend werden die Daten übernommen, was zu einem ersten Datensatz führt.
// Sollte die Tabelle bereits existieren, dann fügt die Methode 'store()' ausschließlich den
// neuen Datensatz hinzu.
// Beim Anlegen der Tabelle wird immer eine Spalte namens 'id' hinzugefügt. Diese Spalte dient als Primärschlüssel!
$id_a = R::store($a);
$id_l = R::store($l);
$id_t = R::store($t);

R::close();

?>

