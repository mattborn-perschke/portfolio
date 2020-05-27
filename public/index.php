<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require 'rb.php';

R::setup('mysql:host=localhost;dbname=mattborn_perschke', 'mattborn', 'u8EYfdwRGlb2AuAg');

$app = AppFactory::create();
$app->setBasePath((function () {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $uri = (string) parse_url('http://a' . $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    if (stripos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
        return $_SERVER['SCRIPT_NAME'];
    }
    if ($scriptDir !== '/' && stripos($uri, $scriptDir) === 0) {
        return $scriptDir;
    }
    return '';
})());



//URLs:

//Benutzername und Kennwort validieren
$app->get('/login/{name}/{passwort}', function (Request $request, Response $response, $args) {
    $benutzer = R::findOne('benutzer', 'name = ?  AND passwort = ?', [$args['name'], [$args['passwort'], PDO::PARAM_STR]]);  
    $response->getBody()->write(json_encode(R::exportAll($benutzer, TRUE)));
    return $response;
});

//Alle Aufgabenlisten anzeigen
$app->get('/aufgabenlisten', function (Request $request, Response $response, $args) {
    $aufgabenlisten = R::findAll('aufgabenliste');
    foreach($aufgabenlisten as $aufgabenliste) {
        $aufgabenliste->benutzer;
        }
    $response->getBody()->write(json_encode(R::exportAll($aufgabenlisten, TRUE)));
    return $response;
});

//Alle Aufgabenlisten (eines Erstellers) anzeigen
$app->get('/aufgabenlisten/{benutzerID}', function (Request $request, Response $response, $args) {
    $aufgabenlisten = R::findAll('aufgabenliste', 'benutzer_id = ?', [$args['benutzerID']]);  
    $response->getBody()->write(json_encode(R::exportAll($aufgabenlisten, TRUE)));
    return $response;
});

//Eine bestimmte Aufgabenliste (eines Erstellers) anzeigen
$app->get('/aufgabenlisten/{benutzerID}/{aufgabenlistenID}', function (Request $request, Response $response, $args) {
    $aufgabenlisten = R::findAll('aufgabenliste', 'benutzer_id = ? AND id = ?', [$args['benutzerID'], [$args['aufgabenlistenID'], PDO::PARAM_STR]]); 
    $response->getBody()->write(json_encode(R::exportAll($aufgabenlisten, TRUE)));
    return $response;
});

//Alle Aufgaben einer Aufgabenliste anzeigen
$app->get('/aufgaben/{aufgabenlistenID}', function (Request $request, Response $response, $args) {
    $aufgaben = R::findAll('aufgabe', 'aufgabenliste_id = ?', [$args['aufgabenlistenID']]); 
    $response->getBody()->write(json_encode(R::exportAll($aufgaben, TRUE)));
    return $response;
});

//Legt eine neue Aufgabe in einer Aufgabenliste an
$app->post('/aufgaben/{aufgabenlistenID}', function (Request $request, Response $response, $args) {
    $parsedBody = $request->getParsedBody();
    $aufgabe = R::dispense('aufgabe');
    $aufgabe->titel = $parsedBody['titel'];
    $aufgabe->beschreibung = $parsedBody['beschreibung'];
    $aufgabe->zeitpunkt = $parsedBody['zeitpunkt'];
    $aufgabe->status = $parsedBody['status'];
    $aufgabe->gewichtung = $parsedBody['gewichtung'];

    $a = R::load('aufgabenliste', $parsedBody['aufgabenliste_id']);
    $aufgabe->aufgabenliste = $a;
    $aufgabe->aufgabenliste_id = $args['aufgabenlistenID'];

    R::store($aufgabe);
    $response->getBody()->write(json_encode($aufgabe));
     return $response;
});

//Legt eine neue Aufgabenliste an
$app->post('/aufgabenlisten/{benutzerID}', function (Request $request, Response $response, $args) {
    $parsedBody = $request->getParsedBody();
    $aufgabenliste = R::dispense('aufgabenliste');
    $aufgabenliste->titel = $parsedBody['titel'];
    $aufgabenliste->status = $parsedBody['status'];

    $b = R::load('benutzer', $parsedBody['benutzer_id']);
    $aufgabenliste->benutzer = $b;
    $aufgabenliste->benutzer_id = $args['benutzerID'];

    R::store($aufgabenliste);
    $response->getBody()->write(json_encode($aufgabenliste));
     return $response;
});

//Ändert eine bestehende Aufgabe
$app->put('/aufgaben/{aufgabenID}', function (Request $request, Response $response, $args) {
    $parsedBody = json_decode((string)$request->getBody(), true);
    $aufgabe = R::load('aufgabe', $args['aufgabenID']);
    $aufgabe->titel = $parsedBody['titel'];
    $aufgabe->beschreibung = $parsedBody['beschreibung'];
    $aufgabe->zeitpunkt = $parsedBody['zeitpunkt'];
    $aufgabe->status = $parsedBody['status'];
    $aufgabe->gewichtung = $parsedBody['gewichtung'];
    $aufgabe->aufgabenliste_id = $parsedBody['aufgabenliste_id'];

    R::store($aufgabe);
    $response->getBody()->write(json_encode($aufgabe));
     return $response;
    
});

//Ändert eine bestehende Aufgabenliste
$app->put('/aufgabenlisten/{aufgabenlistenID}', function (Request $request, Response $response, $args) {
    $parsedBody = json_decode((string)$request->getBody(), true);
    $aufgabenliste = R::load('aufgabenliste', $args['aufgabenlistenID']);
    $aufgabenliste->titel = $parsedBody['titel'];
    $aufgabenliste->status = $parsedBody['status'];
    $aufgabenliste->benutzer_id = $parsedBody['benutzer_id'];

    R::store($aufgabenliste);
    $response->getBody()->write(json_encode($aufgabenliste));
     return $response;
});

//Löscht eine bestehende Aufgabe (eines Erstellers)
$app->delete('/aufgaben/{aufgabenID}', function (Request $request, Response $response, $args) {
    $aufgabe = R::load('aufgabe', $args['aufgabenID']);
    R::trash($aufgabe);
    $response->getBody()->write(json_encode($aufgabe));
    return $response;
    
});

//##MUSS NOCH GEMACHT WERDEN
//Löscht eine bestehende Aufgabenliste und alle darin befindlichen Aufgaben (eines Erstellers)
// $app->delete('/aufgabenlisten/{aufgabenlistenID}', function (Request $request, Response $response, $args) {
//     $aufgabenliste = R::load('aufgabenliste', $args['aufgabenlistenID']);
//     R::trash($aufgabenliste);
//     $response->getBody()->write(json_encode($aufgabenliste));
//     return $response;
// });


$app->run();
?>