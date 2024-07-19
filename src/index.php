<?php
use Models\Currency;
    ini_set('memory_limit', '256M');
    // require_once "Autoloader.php";

    // Autoloader::register()

    require_once 'Config/Database.php';
    require_once 'Models/Currency.php';
    require_once 'Routes/Api.php';
    require_once __DIR__ . '/vendor/autoload.php';

    use Routes\Api;
    
    $api = new Api();
    $api->handleRequest()

?>