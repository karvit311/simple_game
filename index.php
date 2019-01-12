<?php
ini_set('display_errors', 1);

define('ROOT', dirname(__FILE__));
require_once(ROOT.'/vendor/autoload.php');
require_once(ROOT.'/application/core/route.php');
 
// подключаем конфигурацию URL
session_start([
    'cookie_lifetime' => 86400,
]);
$routes=ROOT.'/application/routes.php';
$router = new Application\core\App();
$router->run();