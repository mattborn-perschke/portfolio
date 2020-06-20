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

//Zugriff erlauben
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


//#######URLs##########

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

//Alle Aufgaben einer Aufgabenliste anzeigen (=Filter nach Aufgabenliste)
$app->get('/aufgaben/{aufgabenlistenID}', function (Request $request, Response $response, $args) {
    $aufgaben = R::findAll('aufgabe', 'aufgabenliste_id = ?', [$args['aufgabenlistenID']]); 
    $response->getBody()->write(json_encode(R::exportAll($aufgaben, TRUE)));
    return $response;
});

//Legt eine neue Aufgabe in einer Aufgabenliste an
$app->post('/aufgaben/{aufgabenlistenID}/{benutzerID}', function (Request $request, Response $response, $args) {
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

    $b = R::load('benutzer', $parsedBody['benutzer_id']);
    $aufgabe->benutzer = $b;
    $aufgabe->benutzer_id = $args['benutzerID'];

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

    $aufgabenliste->ownAufgabe = $parsedBody['ownAufgabe']; //ownAufgabe = null, falls keine Aufgaben drin

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

//Löscht eine bestehende Aufgabe
$app->delete('/aufgaben/{aufgabenID}', function (Request $request, Response $response, $args) {
    $aufgabe = R::load('aufgabe', $args['aufgabenID']);
    R::trash($aufgabe);
    $response->getBody()->write(json_encode($aufgabe));
    return $response;
});

//Löscht eine bestehende Aufgabenliste und alle darin befindlichen Aufgaben
$app->delete('/aufgabenlisten/{aufgabenlistenID}', function (Request $request, Response $response, $args) {
    $aufgaben = R::findAll('aufgabe', 'aufgabenliste_id = ?', [$args['aufgabenlistenID']]);
    foreach($aufgaben as $aufgabe) {
        R::trash($aufgabe);
    }
    $aufgabenliste = R::load('aufgabenliste', $args['aufgabenlistenID']);
    R::trash($aufgabenliste);
    $response->getBody()->write(json_encode($aufgabenliste));
    return $response;
});


// ##### Filter und Sortierung ##############

//Sortierung aufwärts nach Zeitpunkt
$app->get('/aufgaben/zeitpunkt/sortauf/{benutzerID}', function (Request $request, Response $response, $args) {
    $aufgaben = R::findAll('aufgabe', 'benutzer_id = ? order by zeitpunkt asc', [$args['benutzerID']] ); 
    $response->getBody()->write(json_encode(R::exportAll($aufgaben, TRUE)));
    return $response;
});

//Sortierung abwärts nach Zeitpunkt
$app->get('/aufgaben/zeitpunkt/sortab/{benutzerID}', function (Request $request, Response $response, $args) {
    $aufgaben = R::findAll('aufgabe', 'benutzer_id = ? order by zeitpunkt desc', [$args['benutzerID']] ); 
    $response->getBody()->write(json_encode(R::exportAll($aufgaben, TRUE)));
    return $response;
});

//Sortierung aufwärts nach Status
$app->get('/aufgaben/status/sortauf/{benutzerID}', function (Request $request, Response $response, $args) {
    $aufgaben = R::findAll('aufgabe', 'benutzer_id = ? order by status asc', [$args['benutzerID']] ); 
    $response->getBody()->write(json_encode(R::exportAll($aufgaben, TRUE)));
    return $response;
});

//Sortierung abwärts nach Status
$app->get('/aufgaben/status/sortab/{benutzerID}', function (Request $request, Response $response, $args) {
    $aufgaben = R::findAll('aufgabe', 'benutzer_id = ? order by status desc', [$args['benutzerID']] ); 
    $response->getBody()->write(json_encode(R::exportAll($aufgaben, TRUE)));
    return $response;
});

//Sortierung aufwärts nach Gewichtung
$app->get('/aufgaben/gewichtung/sortauf/{benutzerID}', function (Request $request, Response $response, $args) {
    $aufgaben = R::findAll('aufgabe', 'benutzer_id = ? order by gewichtung asc', [$args['benutzerID']] ); 
    $response->getBody()->write(json_encode(R::exportAll($aufgaben, TRUE)));
    return $response;
});

//Sortierung abwärts nach Gewichtung
$app->get('/aufgaben/gewichtung/sortab/{benutzerID}', function (Request $request, Response $response, $args) {
    $aufgaben = R::findAll('aufgabe', 'benutzer_id = ? order by gewichtung desc', [$args['benutzerID']] ); 
    $response->getBody()->write(json_encode(R::exportAll($aufgaben, TRUE)));
    return $response;
});



//Filter Aufgaben nach konkretem Status
//1 = offen, 2 = in Bearbeitung, 3 = erledigt, 4 = verspätet erledigt, 5 = abgebrochen
$app->get('/aufgaben/status/filter/{benutzerID}/{status}', function (Request $request, Response $response, $args) {
    $aufgaben = R::findAll('aufgabe', 'benutzer_id = ? AND status = ?', [$args['benutzerID'], [$args['status'], PDO::PARAM_STR]]); 
    $response->getBody()->write(json_encode(R::exportAll($aufgaben, TRUE)));
    return $response;
});

//Filter Aufgaben nach konkreter Gewichtung
// 1 = unwichtig, 2 = weniger wichtig, 3 = normal, 4 = wichtig, 5 = sehr wichtig
$app->get('/aufgaben/gewichtung/filter/{benutzerID}/{gewichtung}', function (Request $request, Response $response, $args) {
    $aufgaben = R::findAll('aufgabe', 'benutzer_id = ? AND gewichtung = ?', [$args['benutzerID'], [$args['gewichtung'], PDO::PARAM_STR]]); 
    $response->getBody()->write(json_encode(R::exportAll($aufgaben, TRUE)));
    return $response;
});

//Filter Aufgaben nach konkretem Zeitpunkt
$app->get('/aufgaben/zeitpunkt/filter/{benutzerID}/{zeitpunkt}', function (Request $request, Response $response, $args) {
    $aufgaben = R::findAll('aufgabe', 'benutzer_id = ? AND zeitpunkt = ?', [$args['benutzerID'], [$args['zeitpunkt'], PDO::PARAM_STR]]); 
    $response->getBody()->write(json_encode(R::exportAll($aufgaben, TRUE)));
    return $response;
});


$app->run();
?>