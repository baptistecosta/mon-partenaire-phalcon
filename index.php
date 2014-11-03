<?php

use Phalcon\Mvc\Application,
    Phalcon\Exception;

$di = new Phalcon\DI\FactoryDefault();

//Specify routes for modules
$di->set('router', function () {

    $router = new Phalcon\Mvc\Router();
    $router->setDefaultModule('www');
    $router->add('/login', [
        'module' => 'www',
        'controller' => 'login',
        'action' => 'index',
    ]);

    $router->add('/admin/products/:action', [
        'module' => 'backend',
        'controller' => 'products',
        'action' => 1,
    ]);

    $router->add('/products/:action', [
        'controller' => 'products',
        'action' => 1,
    ]);

    return $router;

});

try {

    //Create an application
    $app = new Application($di);

    // Register the installed modules
    $app->registerModules([
        'frontend' => [
            'className' => 'MonPartenaire\\Www\\Module',
            'path' => '../apps/www/Module.php',
        ],
        'backend' => [
            'className' => 'MonPartenaire\\api\\Module',
            'path' => '../apps/api/Module.php',
        ]
    ]);

    //Handle the request
    echo $app->handle()->getContent();

} catch (Exception $e) {
    echo $e->getMessage();
}




//$loader = new \Phalcon\Loader();
//
//$loader->registerDirs([
//    __DIR__ . '/models/'
//])->register();
//
//$di = new \Phalcon\DI\FactoryDefault();
//
//$di->set('db', function(){
//    return new \Phalcon\Db\Adapter\Pdo\Mysql([
//        "host" => "127.0.0.1",
//        "username" => "root",
//        "password" => "",
//        "dbname" => "my_tennis_pal"
//    ]);
//});
//
//$app = new \Phalcon\Mvc\Micro($di);
//
//$app->get('/', function() use ($app) {
//
//    $phql = "SELECT * FROM places LIMIT 10";
//    $places = $app->modelsManager->executeQuery($phql);
//
//    $data = [];
//    foreach ($places as $place) {
//        $data[] = [
//            'id' => $place->id,
//            'name' => $place->name
//        ];
//    }
//
//    $response = new Phalcon\Http\Response();
//    return $response->setJsonContent($data)
//        ->setContentType('application/json');
//
//});
//
//$app->get('/api/places/{id:[0-9]+}', function($id) use ($app) {
//
//    $phql = "SELECT * FROM place WHERE id = :id";
//    $place = $app->modelsManager->executeQuery($phql, [
//        'id' => $id
//    ])->getFirst();
//
//    $response = new Phalcon\Http\Response();
//
//    if (!$place) {
//        $response->setStatusCode(400, 'Bad request');
//    } else {
//        $response->setJsonContent([
//            'data' => [
//                'id' => $place->id,
//                'name' => $place->name
//            ]
//        ]);
//    }
//
//    return $response;
//});
//
//
//$app->notFound(function () use ($app) {
//    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
//    echo 'This is crazy, but this page was not found!';
//});
//
//$app->handle();