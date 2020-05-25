<?php

require 'rb.php';

R::setup('mysql:host=localhost;dbname=mattborn_perschke', 'mattborn', 'u8EYfdwRGlb2AuAg');

//Neue Tabelle anlegen
$v = R::dispense('anwender');		                    // --> es wird eine Tabelle 'anwender' angelegt [KLEINSCHREIBUNG IST WICHTIG !!!)

$v->name = "Testuser";                                  // --> es wird eine Spalte 'name' mit dem Inhalt "Testuser" erstellt
$v->passwort = "test";                             



// ...und speichern
// Ist die Tabelle 'anwender' noch nicht vorhanden, dann wird die Tabelle hier angelegt.
// Anschließend werden die Daten übernommen, was zu einem ersten Datensatz führt.
// Sollte die Tabelle bereits existieren, dann fügt die Methode 'store()' ausschließlich den
// neuen Datensatz hinzu.
// Beim Anlegen der Tabelle wird immer eine Spalte namens 'id' hinzugefügt. Diese Spalte dient als Primärschlüssel!
$id = R::store($v);

R::close();

?>

