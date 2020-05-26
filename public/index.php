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

//Test
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hallo!");
    return $response;
});

//Alle Aufgaben eines Erstellers anzeigen
$app->get('/aufgaben', function (Request $request, Response $response, $args) {
    $aufgaben = R::findAll('aufgabe');
    $response->getBody()->write(json_encode(R::exportAll($aufgaben, TRUE)));
    return $response;
});

//Eine bestimmte Aufgabe (eines Erstellers) anzeigen
$app->get('/aufgaben/{id}', function (Request $request, Response $response, $args) {
 $response->getBody()->write("Eine bestimmte Aufgabe (eines Erstellers) anzeigen");
 return $response;
});

//Alle Aufgabenlisten (eines Erstellers) anzeigen
$app->get('/aufgabenlisten', function (Request $request, Response $response, $args) {
 $response->getBody()->write("Alle Aufgabenlisten (eines Erstellers) anzeigen");
 return $response;
});

//Alle Aufgaben einer Aufgabenliste (eines Erstellers) anzeigen
$app->get('/aufgabenlisten/{id}', function (Request $request, Response $response, $args) {
 $response->getBody()->write("Alle Aufgaben einer Aufgabenliste (eines Erstellers) anzeigen");
 return $response;
});

//Legt eine neue Aufgabe an
$app->post('/aufgaben', function (Request $request, Response $response, $args) {
 $response->getBody()->write("Legt eine neue Aufgabe an");
 return $response;
});

//Legt eine neue Aufgabenliste an
$app->post('/aufgabenlisten', function (Request $request, Response $response, $args) {
 $response->getBody()->write("Legt eine neue Aufgabenliste an");
 return $response;
});

//Ändert eine bestehende Aufgabe (eines Erstellers)
$app->put('/aufgaben/{id}', function (Request $request, Response $response, $args) {
 $response->getBody()->write("Ändert eine bestehende Aufgabe (eines Erstellers)");
 return $response;
});

//Ändert eine bestehende Aufgabenliste (eines Erstellers)
$app->put('/aufgabenlisten/{id}', function (Request $request, Response $response, $args) {
 $response->getBody()->write("Ändert eine bestehende Aufgabenliste (eines Erstellers)");
 return $response;
});

//Löscht eine bestehende Aufgabe (eines Erstellers)
$app->delete('/aufgaben/{id}', function (Request $request, Response $response, $args) {
 $response->getBody()->write("Löscht eine bestehende Aufgabe (eines Erstellers)");
 return $response;
});

//Löscht eine bestehende Aufgabenliste und alle darin befindlichen Aufgaben (eines Erstellers)
$app->delete('/aufgabenlisten/{id}', function (Request $request, Response $response, $args) {
 $response->getBody()->write("Löscht eine bestehende Aufgabenliste und alle darin befindlichen Aufgaben (eines Erstellers)");
 return $response;
});




$app->run();
?>
