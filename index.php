<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();

//with this sentence connect to the database

function getConnection() {
    $dbhost="127.0.0.1";
    $dbuser="root";
    $dbpass="";
    $dbname="testing";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

//rute for see the created hostings:api/hostings
function listarHostings($response) {
    $sql = "SELECT * FROM hosting";
    try {
        $stmt = getConnection()->query($sql);
        $hosts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        return json_encode($hosts);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

//route for create one hosting api/crear/
//pass the information body of variables with action POST
//with the POST create the SQL sentence and insert the information.
function crearHosting($request) {

    $nombre = $request->getParsedBody()['nombre'];
    $cores = $request->getParsedBody()['cores'];
    $memoria = $request->getParsedBody()['memoria'];
    $disco = $request->getParsedBody()['disco'];
    $sql = "INSERT INTO hosting ( nombre, cores, memoria, disco) VALUES (:nombre, :cores, :memoria, :disco)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("nombre", $nombre);
        $stmt->bindParam("cores", $cores);
        $stmt->bindParam("memoria", $memoria);
        $stmt->bindParam("disco", $disco);
        $stmt->execute();
        $host->id = $db->lastInsertId();
        $db = null;
        
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
//route for update information api/actualizar/ID/
//pass the information body of variables with action PUT
//get the sentences and do a request for update the hosting. 
function actualizarHosting($request) {

   // $emp = json_decode($request->getBody());
    $id = $request->getAttribute('id');

    $nombre = $request->getParsedBody()['nombre'];
    $cores = $request->getParsedBody()['cores'];
    $memoria = $request->getParsedBody()['memoria'];
    $disco = $request->getParsedBody()['disco'];
    
    $sql = "UPDATE hosting SET nombre=:nombre, cores=:cores, memoria=:memoria, disco=:disco WHERE id=:id";

    echo("nombre = $nombre");

    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("nombre", $nombre);
        $stmt->bindParam("cores", $cores);
        $stmt->bindParam("memoria", $memoria);
        $stmt->bindParam("disco", $disco);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;

    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
//route for delete api/eliminar/id
//execute the URL with delete action
function eliminarHosting($request) {
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM hosting WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo '{"error":{"text":"El alojamiento se ha eliminado correctamente"}}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
